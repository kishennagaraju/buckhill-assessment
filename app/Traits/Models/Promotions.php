<?php

namespace App\Traits\Models;

use App\Models\Promotions as PromotionsModel;

trait Promotions
{
    /**
     * @var PromotionsModel
     */
    protected $_promotionModel;

    /**
     * @return PromotionsModel
     */
    public function getPromotionsModel(): PromotionsModel
    {
        if (!$this->_promotionModel) {
            $this->setPromotionsModel($this->_createDefaultPromotionsModel());
        }

        return $this->_promotionModel;
    }

    /**
     * @param PromotionsModel $promotionModel
     * @return void
     */
    public function setPromotionsModel(PromotionsModel $promotionModel): void
    {
        $this->_promotionModel = $promotionModel;
    }

    /**
     * @return PromotionsModel
     */
    protected function _createDefaultPromotionsModel(): PromotionsModel
    {
        return new PromotionsModel();
    }
}
