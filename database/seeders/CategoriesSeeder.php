<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Categories::factory()->count(5)->create()->each(function ($category) {
            $products = \App\Models\Products::factory()->count(5)->make();
            $category->products()->saveMany($products);
        });
    }
}
