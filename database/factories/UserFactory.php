<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => Str::uuid(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('userpassword'),
            'is_marketing' => 1,
            'is_admin' => 0,
            'avatar' => Str::uuid(),
            'address' => $this->faker->address(),
            'phone_number' => $this->faker->phoneNumber(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Create admin user for seeding.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function adminUsers()
    {
        return $this->state(function (array $attributes) {
            return [
                'email' => 'admin@buckhill.co.uk',
                'password' => Hash::make('admin'),
                'is_admin' => 1,
                'is_marketing' => 0
            ];
        });
    }
}
