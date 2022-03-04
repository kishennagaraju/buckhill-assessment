<?php

namespace App\Traits\Models;

use App\Models\Categories as CategoriesModel;

trait Categories
{
    /**
     * @var CategoriesModel
     */
    protected $_categoryModel;

    /**
     * @return CategoriesModel
     */
    public function getCategoriesModel(): CategoriesModel
    {
        if (!$this->_categoryModel) {
            $this->setCategoriesModel($this->_createDefaultCategoriesModel());
        }

        return $this->_categoryModel;
    }

    /**
     * @param CategoriesModel $categoryModel
     * @return void
     */
    public function setCategoriesModel(CategoriesModel $categoryModel): void
    {
        $this->_categoryModel = $categoryModel;
    }

    /**
     * @return CategoriesModel
     */
    protected function _createDefaultCategoriesModel(): CategoriesModel
    {
        return new CategoriesModel();
    }
}
