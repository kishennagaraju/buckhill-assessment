<?php

namespace App\Services;

use App\Traits\Models\Order;
use App\Traits\Models\PasswordResets;
use App\Traits\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService {

    use Order;
    use User;
    use PasswordResets;

    /**
     * @param  int    $userId
     * @param  array  $relationships
     *
     * @return mixed
     */
    public function getAllOrdersForUser(int $userId, array $relationships = [])
    {
        return $this->getOrderModel()->getAllOrders($relationships, $userId);
    }

    /**
     * @param  string  $email
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function forgotPassword(string $email)
    {
        if ($this->getUserModel()->newQuery()->where('email', '=', $email)->exists())
        {
            return $this->getPasswordResetsModel()->forgotPassword($email);
        }

        throw new ModelNotFoundException();
    }

    public function resetPassword($data = [])
    {
        $userDetails = $this->getUserModel()->newQuery()->where('email', '=', $data['email'])->firstOrFail();
        if ($this->getUserModel()->updateUser($userDetails['uuid'], [
            'email' => $data['email'],
            'password' => $data['password']
        ]))
        {
            return $this->getPasswordResetsModel()->newQuery()->where('email', '=', $data['email'])->delete();
        }

        throw new ModelNotFoundException();
    }
}
