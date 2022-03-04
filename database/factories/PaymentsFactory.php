<?php

namespace Database\Factories;

use App\Models\Payments;
use Illuminate\Database\Eloquent\Factories\Factory;


class PaymentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'type' => '',
            'details' => [],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function creditCard()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'credit_card',
                'details' => [
                    'holder_name' => $this->faker->name(),
                    'number' => $this->faker->creditCardNumber(),
                    'cvv' => $this->faker->randomNumber(3),
                    'expire_date' => $this->faker->creditCardExpirationDateString()
                ]
            ];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cashOnDelivery()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'cash_on_delivery',
                'details' => [
                    'first_name' => $this->faker->firstName(),
                    'last_name' => $this->faker->lastName(),
                    'address' => $this->faker->address(),
                ]
            ];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function bankTransfer()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'bank_transfer',
                'details' => [
                    'swift' => $this->faker->swiftBicNumber(),
                    'iban' => $this->faker->iban(),
                    'name' => $this->faker->name(),
                ]
            ];
        });
    }
}
