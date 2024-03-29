<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Support\EventMessages;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogOrderCreated implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        Log::info(sprintf(EventMessages::MESSAGE_ORDER_CREATED, $event->order->id));
    }
}
