<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\productSubCategoryValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class productSubCategoryValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        productSubCategoryValue::factory(20)->create();
    }
}
