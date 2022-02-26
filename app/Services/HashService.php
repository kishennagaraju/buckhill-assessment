<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash as HashFacade;

class HashService {

    /**
     * Generate a Hash for the given string.
     *
     * @param $string
     *
     * @return false|string
     */
    public function generateHash($string = null)
    {
        if ($string) {
            return HashFacade::make($string);
        }

        return false;
    }

    /**
     * Check whether the string matches the hashed value.
     *
     * @param $string
     * @param $hash
     *
     * @return bool
     */
    public function verifyHashForString($string, $hash)
    {
        return HashFacade::check($string, $hash);
    }
}
