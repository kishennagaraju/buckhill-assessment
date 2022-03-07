<?php

namespace App\Traits\Models;

use App\Models\PasswordResets as PasswordResetsModel;

trait PasswordResets
{
    /**
     * @var PasswordResetsModel
     */
    protected $_passwordResetsModel;

    /**
     * @return PasswordResetsModel
     */
    public function getPasswordResetsModel(): PasswordResetsModel
    {
        if (!$this->_passwordResetsModel) {
            $this->setPasswordResetsModel($this->_createDefaultPasswordResetsModel());
        }

        return $this->_passwordResetsModel;
    }

    /**
     * @param PasswordResetsModel $passwordResetsModel
     * @return void
     */
    public function setPasswordResetsModel(PasswordResetsModel $passwordResetsModel): void
    {
        $this->_passwordResetsModel = $passwordResetsModel;
    }

    /**
     * @return PasswordResetsModel
     */
    protected function _createDefaultPasswordResetsModel(): PasswordResetsModel
    {
        return new PasswordResetsModel();
    }
}
