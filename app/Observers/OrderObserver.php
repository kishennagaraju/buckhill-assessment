<?php

namespace App\Observers;

use App\Models\Order;
use App\Traits\Models\OrderStatuses;
use App\Traits\Models\Payments;
use App\Traits\Models\Products;
use App\Traits\Models\User;
use Illuminate\Support\Str;

class OrderObserver
{
    use OrderStatuses;
    use Payments;
    use User;
    use Products;

    /**
     * Handle the Post "created" event.
     *
     * @param  Order  $order
     * @return void
     */
    public function creating(Order $order)
    {
        $this->saveOrUpdate($order);
    }

    /**
     * Handle the Put "updated" event.
     *
     * @param  Order  $order
     * @return void
     */
    public function updating(Order $order)
    {
        $this->saveOrUpdate($order);
    }

    /**
     * @param  \App\Models\Order  $order
     *
     * @return void
     */
    public function saveOrUpdate(Order $order): void
    {
        $requestDetails = request()->all();
        $userDetails = $this->getUserModel()->newQuery()->where('is_admin', '=', 0)->firstOrFail();
        $orderStatus = $this->getOrderStatusesModel()->getOrderStatusByUuid($requestDetails['order_status_uuid']);
        $paymentDetails = $this->getPaymentsModel()->getPaymentByUuid($requestDetails['payment_uuid']);

        $amount = 0;
        $products = [];
        foreach ($requestDetails['products'] as $product) {
            $productDetails = $this->getProductsModel()->getProductByUuid($product['product']);
            $amount += $productDetails->price * $product['quantity'];
        }

        $order->uuid = Str::uuid();
        $order->products = $requestDetails['products'];
        $order->address = $requestDetails['address'];
        $order->amount = $amount;
        $order->user_id = $userDetails->id;
        $order->order_status_id = $orderStatus->id;
        $order->payment_id = $paymentDetails->id;
    }
}
