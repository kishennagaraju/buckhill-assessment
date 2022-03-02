<?php

namespace App\Traits\Services;

use App\Services\UserService;

trait User
{
    /**
     * @var \App\Services\UserService
     */
    protected $_userService;

    /**
     * @return UserService
     */
    public function getUserService(): UserService
    {
        if (!$this->_userService) {
            $this->setUserService($this->_createDefaultUserService());
        }

        return $this->_userService;
    }

    /**
     * @param  \App\Services\UserService  $userService
     *
     * @return void
     */
    public function setUserService(UserService $userService): void
    {
        $this->_userService = $userService;
    }

    /**
     * @return \App\Services\UserService
     */
    protected function _createDefaultUserService(): UserService
    {
        return new UserService();
    }
}
