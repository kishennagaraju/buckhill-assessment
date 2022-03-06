<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            BrandsSeeder::class,
            PromotionsSeeder::class,
            PostSeeder::class,
            CategoriesSeeder::class,
            OrderStatusesSeeder::class,
            PaymentsSeeder::class,
            OrdersSeeder::class
        ]);
    }
}
