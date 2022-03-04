<?php

namespace App\Traits\Models;

use App\Models\Payments as PaymentsModel;

trait Payments
{
    /**
     * @var PaymentsModel
     */
    protected $_paymentModel;

    /**
     * @return PaymentsModel
     */
    public function getPaymentsModel(): PaymentsModel
    {
        if (!$this->_paymentModel) {
            $this->setPaymentsModel($this->_createDefaultPaymentsModel());
        }

        return $this->_paymentModel;
    }

    /**
     * @param PaymentsModel $paymentModel
     * @return void
     */
    public function setPaymentsModel(PaymentsModel $paymentModel): void
    {
        $this->_paymentModel = $paymentModel;
    }

    /**
     * @return PaymentsModel
     */
    protected function _createDefaultPaymentsModel(): PaymentsModel
    {
        return new PaymentsModel();
    }
}
