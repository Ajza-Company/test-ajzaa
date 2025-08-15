<?php

namespace App\Observers;

use App\Events\v1\Frontend\F_OrderCreatedEvent;
use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
    }
}
