<?php

namespace Database\Factories;

use App\Models\Market;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private $categoryNames = [
        'Electronics',
        'Clothing',
        'Books',
        'Home Decor',
        'Sports Equipment',
        'Beauty Products',
        'Toys',
    ];
    public function definition(): array
    {

        return [
            'name' => $this->faker->randomElement($this->categoryNames),
            'image' => $this->faker->imageUrl(),
            'market_id' => Market::pluck('id')->random(),
            ]
            ;
    }
}