<?php

namespace App\Traits\Services;

use App\Services\HashService;

trait Hash
{
    /**
     * @var HashService
     */
    protected $_hashService;

    /**
     * @return HashService
     */
    public function getHashService()
    {
        if (!$this->_hashService) {
            $this->setHashService($this->_createDefaultHashService());
        }

        return $this->_hashService;
    }

    /**
     * @param HashService $hashService
     * @return $this
     */
    public function setHashService(HashService $hashService)
    {
        $this->_hashService = $hashService;

        return $this;
    }

    /**
     * @return HashService
     */
    protected function _createDefaultHashService()
    {
        return new HashService();
    }
}
