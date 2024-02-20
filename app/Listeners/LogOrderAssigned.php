<?php

namespace App\Listeners;

use App\Support\EventMessages;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogOrderAssigned implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        Log::info(sprintf(
            EventMessages::MESSAGE_ORDER_ASSIGNED,
            $event->order->id,
            $event->order->driver->name ?? ''
        ));
    }
}
