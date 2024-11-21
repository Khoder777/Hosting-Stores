<?php

namespace Database\Factories;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubCategoeyProperty>
 */
class SubCategoeyPropertyFactory extends Factory
{
    private $subCategoryPropertyNames = [
        'name',
        'color',
        'size',
        'material',
        'weight',
        'brand',
        'model',
        'ram',
        'storage',
        'type',
        'pattern',
        'style',
        'season',
        'heel_height',
        'weight_capacity',
        'capacity',
    ];
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement($this->subCategoryPropertyNames),
            'sub_category_id' => SubCategory::pluck('id')->random(),
        ];
    }
}