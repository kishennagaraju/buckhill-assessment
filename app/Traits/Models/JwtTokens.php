<?php

namespace App\Traits\Models;

use App\Models\JwtTokens as JwtTokensModel;

trait JwtTokens
{
    /**
     * @var JwtTokensModel
     */
    protected $_jwtTokensModel;

    /**
     * @return JwtTokensModel
     */
    public function getJwtTokensModel(): JwtTokensModel
    {
        if (!$this->_jwtTokensModel) {
            $this->setJwtTokensModel($this->_createDefaultJwtTokensModel());
        }

        return $this->_jwtTokensModel;
    }

    /**
     * @param JwtTokensModel $jwtTokensModel
     * @return void
     */
    public function setJwtTokensModel(JwtTokensModel $jwtTokensModel): void
    {
        $this->_jwtTokensModel = $jwtTokensModel;
    }

    /**
     * @return JwtTokensModel
     */
    protected function _createDefaultJwtTokensModel(): JwtTokensModel
    {
        return new JwtTokensModel();
    }
}
