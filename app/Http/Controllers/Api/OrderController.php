<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\ListRequest;
use App\Services\OrdersServices;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    private OrdersServices $ordersServices;

    public function __construct(OrdersServices $ordersServices)
    {
        $this->ordersServices = $ordersServices;
    }

    public function createOrder(CreateOrderRequest $request)
    {
        return $this->ordersServices->createOrder($request);
    }

    public function listOrdersInProcessing(ListRequest $request)
    {
        return $this->ordersServices->listOrdersInProcessing($request);
    }

    public function consultOrder($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:orders',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Error when searching',
                'error' => 'The record does not exist or has been removed.'
            ],400);
        }

        return $this->ordersServices->consultOrder($id);
    }

    public function cancelOrder($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:orders',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Error when searching',
                'error' => 'The record does not exist or has been removed.'
            ],400);
        }

        return $this->ordersServices->cancelOrder($id);
    }
}
