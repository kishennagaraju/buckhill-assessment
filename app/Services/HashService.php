<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash as HashFacade;

class HashService {

    /**
     * Generate a Hash for the given string.
     *
     * @param  string|null  $string
     *
     * @return false|string
     */
    public function generateHash(string $string = null)
    {
        if ($string) {
            return HashFacade::make($string);
        }

        return false;
    }

    /**
     * Check whether the string matches the hashed value.
     *
     * @param  string  $string
     * @param  string  $hash
     *
     * @return bool
     */
    public function verifyHashForString(string $string, string $hash): bool
    {
        return HashFacade::check($string, $hash);
    }
}
