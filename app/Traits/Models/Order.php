<?php

namespace App\Traits\Models;

use App\Models\Order as OrderModel;

trait Order
{
    /**
     * @var OrderModel
     */
    protected $_orderModel;

    /**
     * @return OrderModel
     */
    public function getOrderModel(): OrderModel
    {
        if (!$this->_orderModel) {
            $this->setOrderModel($this->_createDefaultOrderModel());
        }

        return $this->_orderModel;
    }

    /**
     * @param OrderModel $orderModel
     * @return void
     */
    public function setOrderModel(OrderModel $orderModel): void
    {
        $this->_orderModel = $orderModel;
    }

    /**
     * @return OrderModel
     */
    protected function _createDefaultOrderModel(): OrderModel
    {
        return new OrderModel();
    }
}
