<?php

namespace App\Traits\Services;

use App\Services\AuthService;

trait Auth
{
    /**
     * @var \App\Services\AuthService
     */
    protected $_authService;

    /**
     * @return AuthService
     */
    public function getAuthService(): AuthService
    {
        if (!$this->_authService) {
            $this->setAuthService($this->_createDefaultAuthService());
        }

        return $this->_authService;
    }

    /**
     * @param  \App\Services\AuthService  $authService
     *
     * @return void
     */
    public function setAuthService(AuthService $authService): void
    {
        $this->_authService = $authService;
    }

    /**
     * @return \App\Services\AuthService
     */
    protected function _createDefaultAuthService(): AuthService
    {
        return new AuthService();
    }
}
