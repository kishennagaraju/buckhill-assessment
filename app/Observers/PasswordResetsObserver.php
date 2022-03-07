<?php

namespace App\Observers;

use App\Models\PasswordResets;
use App\Models\User;
use App\Traits\Services\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PasswordResetsObserver
{
    use Hash;

    /**
     * Handle the PasswordResets "created" event.
     *
     * @param  PasswordResets  $passwordReset
     * @return void
     */
    public function creating(PasswordResets $passwordReset)
    {
        $passwordReset->token = $this->_uniqueRandom('password_resets', 'token', 50);
        $passwordReset->updated_at = now();

        if (!count($passwordReset->getDirty())) {
            $passwordReset->created_at = now();
        }
    }


    /**
     *
     * Generate a unique random string of characters
     * uses str_random() helper for generating the random string
     *
     * @param  string  $table  - name of the table
     * @param  string  $col    - name of the column that needs to be tested
     * @param  int     $chars  - length of the random string
     *
     * @return string
     */
    function _uniqueRandom(string $table, string $col, int $chars = 16)
    {

        $unique = false;

        // Store tested results in array to not test them again
        $tested = [];

        do{

            // Generate random string of characters
            $random = Str::random($chars);

            // Check if it's already testing
            // If so, don't query the database again
            if( in_array($random, $tested) ){
                continue;
            }

            // Check if it is unique in the database
            $count = DB::table($table)->where($col, '=', $random)->count();

            // Store the random character in the tested array
            // To keep track which ones are already tested
            $tested[] = $random;

            // String appears to be unique
            if( $count == 0){
                // Set unique to true to break the loop
                $unique = true;
            }

            // If unique is still false at this point
            // it will just repeat all the steps until
            // it has generated a random string of characters

        }
        while(!$unique);


        return $random;
    }
}
