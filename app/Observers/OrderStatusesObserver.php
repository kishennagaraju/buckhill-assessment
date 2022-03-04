<?php

namespace App\Observers;

use App\Models\OrderStatuses;
use Illuminate\Support\Str;

class OrderStatusesObserver
{
    /**
     * Handle the Post "created" event.
     *
     * @param  OrderStatuses  $orderStatus
     * @return void
     */
    public function creating(OrderStatuses $orderStatus)
    {
        $orderStatus->uuid = Str::uuid();
    }
}
