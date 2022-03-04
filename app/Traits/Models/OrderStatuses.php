<?php

namespace App\Traits\Models;

use App\Models\OrderStatuses as OrderStatusesModel;

trait OrderStatuses
{
    /**
     * @var OrderStatusesModel
     */
    protected $_orderStatusModel;

    /**
     * @return OrderStatusesModel
     */
    public function getOrderStatusesModel(): OrderStatusesModel
    {
        if (!$this->_orderStatusModel) {
            $this->setOrderStatusesModel($this->_createDefaultOrderStatusesModel());
        }

        return $this->_orderStatusModel;
    }

    /**
     * @param OrderStatusesModel $orderStatusModel
     * @return void
     */
    public function setOrderStatusesModel(OrderStatusesModel $orderStatusModel): void
    {
        $this->_orderStatusModel = $orderStatusModel;
    }

    /**
     * @return OrderStatusesModel
     */
    protected function _createDefaultOrderStatusesModel(): OrderStatusesModel
    {
        return new OrderStatusesModel();
    }
}
