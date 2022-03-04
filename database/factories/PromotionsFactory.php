<?php

namespace Database\Factories;

use App\Models\Promotions;
use Illuminate\Database\Eloquent\Factories\Factory;


class PromotionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Promotions::class;

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
            'content' => $this->faker->paragraph(10, true),
            'metadata' => [
                'image' => $this->faker->uuid(),
                'valid_to' => $this->faker->dateTimeBetween('+0 days', '+1 year')->format('Y-m-d h:i:s'),
                'valid_from' => $this->faker->date('Y-m-d h:i:s'),
            ],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
