<?php

namespace App\Observers;

use App\Models\User;
use App\Traits\Services\Hash;
use Illuminate\Support\Str;

class UserObserver
{
    use Hash;

    /**
     * Handle the User "created" event.
     *
     * @param  User  $user
     * @return void
     */
    public function creating(User $user)
    {
        $user->uuid = Str::uuid();
        $user->password = $this->getHashService()->generateHash($user->password);
        $user->updated_at = now();

        if (!count($user->getDirty())) {
            $user->created_at = now();
        }
    }
}
