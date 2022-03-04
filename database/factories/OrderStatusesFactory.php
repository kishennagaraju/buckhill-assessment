<?php

namespace Database\Factories;

use App\Models\OrderStatuses;
use Illuminate\Database\Eloquent\Factories\Factory;


class OrderStatusesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderStatuses::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'title' => $this->faker->sentence(1, true),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
