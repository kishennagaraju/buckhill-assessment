<?php

namespace Database\Seeders;

use App\Models\Promotions;
use Illuminate\Database\Seeder;

class PromotionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Promotions::factory()->count(20)->create();
    }
}
