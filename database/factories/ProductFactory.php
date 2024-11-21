<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Market;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'image' => $this->faker->imageUrl(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'desc' => $this->faker->sentence,
            'rate' => $this->faker->randomFloat(1, 1, 5),
            'market_id' => Market::pluck('id')->random(),
            'category_id' => Category::pluck('id')->random(),

        ];
    }
}
