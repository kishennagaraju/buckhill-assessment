<?php

namespace App\Observers;

use App\Models\JwtTokens;

class JwtTokensObserver
{
    /**
     * Handle the JwtTokens "created" event.
     *
     * @param  JwtTokens  $jwtTokens
     * @return void
     */
    public function creating(JwtTokens $jwtTokens)
    {
        $jwtTokens->updated_at = now();

        if (!count($jwtTokens->getDirty())) {
            $jwtTokens->created_at = now();
        }
    }
}
