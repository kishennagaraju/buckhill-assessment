<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(6, true);
        return [
            'uuid' => $this->faker->uuid(),
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->paragraph(10, true),
            'metadata' => [
                'author' => $this->faker->name(),
                'image' => $this->faker->uuid(),
            ],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
