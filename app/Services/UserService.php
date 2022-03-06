<?php

namespace App\Services;

use App\Traits\Models\Order;
use App\Traits\Models\User;

class UserService {

    use Order;
    use User;

    public function getAllOrdersForUser($userId, $relationships = [])
    {
        return $this->getOrderModel()->getAllOrders($relationships, $userId);
    }
}
