<?php

namespace Tests\Feature\App;

use App\Traits\Models\User;
use Tests\BuckhillBaseTesting;

class AdminBaseTesting extends BuckhillBaseTesting
{
    use User;

    public function getAdminUser()
    {
        return $this->getUserModel()->newQuery()->where('is_admin', '=', 1)->first();
    }

    public function getUser()
    {
        return $this->getUserModel()->newQuery()->where('is_admin', '=', 0)->first();
    }
}
