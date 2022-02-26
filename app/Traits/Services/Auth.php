<?php

namespace App\Traits\Services;

use App\Services\Admin\AuthService;

trait Auth
{
    /**
     * @var AuthService
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
     * @param AuthService $authService
     * @return void
     */
    public function setAuthService(AuthService $authService): void
    {
        $this->_authService = $authService;
    }

    /**
     * @return AuthService
     */
    protected function _createDefaultAuthService(): AuthService
    {
        return new AuthService();
    }
}
