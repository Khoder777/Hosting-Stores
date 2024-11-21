<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;


class SubCategoryFactory extends Factory
{
 
    private $subCategoryNames = [
        'Phones',
        'Laptops',
        'Shoes',
        'Furniture',
        'Garden Tools',
        'Cosmetics',
        'Toys',
    ];

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement($this->subCategoryNames),
            'image' => $this->faker->imageUrl(),
            'category_id' => Category::pluck('id')->random(),
        ];
    }
}
