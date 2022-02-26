<?php

namespace App\Traits\Models;

use App\Models\User as UserModel;

trait User
{
    /**
     * @var UserModel
     */
    protected $_userModel;

    /**
     * @return UserModel
     */
    public function getUserModel(): UserModel
    {
        if (!$this->_userModel) {
            $this->setUserModel($this->_createDefaultUserModel());
        }

        return $this->_userModel;
    }

    /**
     * @param UserModel $userModel
     * @return void
     */
    public function setUserModel(UserModel $userModel): void
    {
        $this->_userModel = $userModel;
    }

    /**
     * @return UserModel
     */
    protected function _createDefaultUserModel(): UserModel
    {
        return new UserModel();
    }
}
