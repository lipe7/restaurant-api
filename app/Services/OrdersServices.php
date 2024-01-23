<?php

namespace App\Services;

use App\Helpers\CollectionHelper;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\ListRequest;
use App\Jobs\ProcessOrder;
use App\Models\ItemOrder;
use App\Models\Order;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class OrdersServices
{
    private Order $order;
    private ItemOrder $itemOrder;
    private ProcessOrder $processOrder;

    public function __construct(
        Order $order,
        ItemOrder $itemOrder,
        ProcessOrder $processOrder
    ){
        $this->order = $order;
        $this->itemOrder = $itemOrder;
        $this->processOrder = $processOrder;
    }

    public function createOrder(CreateOrderRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $order = $this->order->newInstance();
            $order->fill([
                'client_name' => $request->input('client_name'),
                'table' => $request->input('table'),
                'status' => $this->order::PENDING
            ]);
            $order->save();

            $itemsData = $request->input('items');
            $items = [];

            foreach ($itemsData as $itemData) {
                $item = new $this->itemOrder([
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity'],
                ]);
                $items[] = $item;
            }

            $order->items()->saveMany($items);

            $this->processOrder::dispatch($order);

            DB::commit();

            return response()->json([
                'success' => true,
                'id' => $order->id,
                'message' => 'Order created successfully'
            ], 201);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $ex->getMessage()], 400);
        }
    }

    public function listOrdersInProcessing(ListRequest $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        $orderBy = $filters['order_by'] ?? 'id';
        $orderByType = $filters['order_by_type'] ?? 'asc';
        $searchTerm = $filters['search_term'] ?? '';

        $keys = Redis::keys('orders_in_processing:*');
        $orders = [];

        foreach ($keys as $key) {
            $data = Redis::hgetall($key);

            if (stripos(json_encode($data), $searchTerm) !== false) {
                $orders[] = $data;
            }
        }

        return CollectionHelper::paginate($orders, $perPage, null, 'page',$orderBy, $orderByType);
    }

    public function consultOrder($id)
    {
        try {
            $order = $this->order::with(['items.dish:id,name,price'])
                ->find($id);

            return response()->json([$order], 200);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'error' => $ex->getMessage()], 400);
        }
    }

    public function cancelOrder($id)
    {
        try {
            $order = Order::find($id);
            Redis::del("orders_in_processing:{$id}");

            $order->update(['status' => $this->order::CANCELED]);

            return response()->json([
                'success' => true,
                'id' => $order->id,
                'message' => 'Order canceled successfully'
            ], 200);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'error' => $ex->getMessage()], 400);
        }
    }
}
