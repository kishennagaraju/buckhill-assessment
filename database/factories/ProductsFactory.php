<?php

namespace Database\Factories;

use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Products::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'title' => $this->faker->sentence(6, true),
            'description' => $this->faker->paragraph(10, true),
            'price' => $this->faker->randomFloat(2, 10, 10000),
            'metadata' => [
                'brand' => $this->faker->uuid(),
                'image' => $this->faker->uuid(),
            ],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
