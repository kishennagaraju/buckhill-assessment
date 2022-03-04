<?php

namespace Database\Factories;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class CategoriesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Categories::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(3, true);
        return [
            'uuid' => $this->faker->uuid(),
            'title' => $title,
            'slug' => Str::slug($title),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
