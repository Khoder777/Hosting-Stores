<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\SubCategoeyProperty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\productSubCategoryValue>
 */
class productSubCategoryValueFactory extends Factory
{
    private $values = [
        'red',
        'green',
        'blue',
        'yellow',
        'large',
        'small',
        'medium',
        'xxlarge',
        'leather',
        'cotton',
        'polyester',
        'silk',
        '1kg',
        '500g',
        '2kg',
        '750g',
        'BrandA',
        'BrandB',
        'BrandC',
        'BrandD',
        'ModelX',
        'ModelY',
        'ModelZ',
        'ModelW',
        '8GB',
        '16GB',
        '32GB',
        '64GB',
        '256GB',
        '512GB',
        '1TB',
        '2TB',
        'Laptop',
        'Smartphone',
        'Tablet',
        'Smartwatch',
        'Striped',
        'Floral',
        'Geometric',
        'Polka Dot',
        'Casual',
        'Formal',
        'Sporty',
        'Bohemian',
        'Spring/Summer',
        'Fall/Winter',
        'All Seasons',
        'Summer',
        '3 inches',
        '2 inches',
        '4 inches',
        '1 inch',
        '150 lbs',
        '100 lbs',
        '200 lbs',
        '120 lbs',
        '32 oz',
        '16 oz',
        '64 oz',
        '48 oz',
    ];
    public function definition(): array
    {
        return [
            'value' =>  $this->faker->randomElement($this->values),
            'product_id' => Product::pluck('id')->random(),
            'image' => $this->faker->imageUrl(),
            'sub_category_property_id' => SubCategoeyProperty::pluck('id')->random(),
        ];
    }
}
