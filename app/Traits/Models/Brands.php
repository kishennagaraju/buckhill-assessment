<?php

namespace App\Traits\Models;

use App\Models\Brands as BrandsModel;

trait Brands
{
    /**
     * @var BrandsModel
     */
    protected $_brandModel;

    /**
     * @return BrandsModel
     */
    public function getBrandsModel(): BrandsModel
    {
        if (!$this->_brandModel) {
            $this->setBrandsModel($this->_createDefaultBrandsModel());
        }

        return $this->_brandModel;
    }

    /**
     * @param BrandsModel $brandModel
     * @return void
     */
    public function setBrandsModel(BrandsModel $brandModel): void
    {
        $this->_brandModel = $brandModel;
    }

    /**
     * @return BrandsModel
     */
    protected function _createDefaultBrandsModel(): BrandsModel
    {
        return new BrandsModel();
    }
}
