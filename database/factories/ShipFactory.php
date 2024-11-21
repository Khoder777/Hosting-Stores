<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class ShipFactory extends Factory
{
 
    public function definition(): array
    {
        return [
            'city' => $this->faker->city,
            'shipping' => $this->faker->randomFloat(2, 10, 500),
            'status' => $this->faker->boolean,
        ];
    }
}