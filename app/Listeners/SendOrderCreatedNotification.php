<?php

namespace App\Listeners;

use App\Events\v1\Frontend\F_OrderCreatedEvent;
use App\Notifications\OrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendOrderCreatedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(F_OrderCreatedEvent $event): void
    {
        \Log::info('SendOrderCreatedNotification' . json_encode($event->order->store->users()->get()));
        Notification::send($event->order->store->users()->whereHas('userFcmTokens')->get(), new OrderNotification(
            order: $event->order,
            type: 'order_created'
        ));
    }
}
