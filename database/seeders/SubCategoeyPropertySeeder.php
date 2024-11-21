<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubCategoeyProperty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubCategoeyPropertySeeder extends Seeder
{


    public function run(): void
    {
        SubCategoeyProperty::factory(20)->create();
    }
}