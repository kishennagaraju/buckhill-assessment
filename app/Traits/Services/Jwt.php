<?php

namespace App\Traits\Services;

use App\Services\JwtService;

trait Jwt
{
    /**
     * @var JwtService
     */
    protected $_jwtService;

    /**
     * @return JwtService
     */
    public function getJwtService(): JwtService
    {
        if (!$this->_jwtService) {
            $this->setJwtService($this->_createDefaultJwtService());
        }

        return $this->_jwtService;
    }

    /**
     * @param JwtService $jwtService
     * @return void
     */
    public function setJwtService(JwtService $jwtService): void
    {
        $this->_jwtService = $jwtService;
    }

    /**
     * @return JwtService
     */
    protected function _createDefaultJwtService(): JwtService
    {
        return new JwtService();
    }
}
