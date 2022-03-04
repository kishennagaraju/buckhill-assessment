<?php

namespace Database\Seeders;

use App\Models\OrderStatuses;
use Illuminate\Database\Seeder;

class OrderStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderStatuses::factory()->count(10)->create();
    }
}
