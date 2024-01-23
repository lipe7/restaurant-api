<?php

namespace App\Jobs;

use App\Events\OrderProcessed;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->order->update(['status' => $this->order::IN_PROGRESS]);

        $data = $this->order->toArray();

        Redis::hmset("orders_in_processing:{$this->order->id}", $data);

        broadcast(new OrderProcessed($this->order->toArray()))->toOthers();
    }
}
