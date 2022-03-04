<?php

namespace App\Observers;

use App\Models\Payments;
use Illuminate\Support\Str;

class PaymentsObserver
{
    /**
     * Handle the Post "created" event.
     *
     * @param  Payments  $payment
     * @return void
     */
    public function creating(Payments $payment)
    {
        $payment->uuid = Str::uuid();
    }
}
