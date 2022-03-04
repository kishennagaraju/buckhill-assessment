<?php

namespace App\Traits\Models;

use App\Models\Products as ProductsModel;

trait Products
{
    /**
     * @var ProductsModel
     */
    protected $_productModel;

    /**
     * @return ProductsModel
     */
    public function getProductsModel(): ProductsModel
    {
        if (!$this->_productModel) {
            $this->setProductsModel($this->_createDefaultProductsModel());
        }

        return $this->_productModel;
    }

    /**
     * @param ProductsModel $productModel
     * @return void
     */
    public function setProductsModel(ProductsModel $productModel): void
    {
        $this->_productModel = $productModel;
    }

    /**
     * @return ProductsModel
     */
    protected function _createDefaultProductsModel(): ProductsModel
    {
        return new ProductsModel();
    }
}
