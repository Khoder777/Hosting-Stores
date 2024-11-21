<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // استدعاء كل Seeder هنا
        $this->call([
            MarketSeeder::class,
            CategorySeeder::class,
            ShipSeeder::class,
            SubCategorySeeder::class,
            SubCategoeyPropertySeeder::class,
            ProductSeeder::class,
            productSubCategoryValueSeeder::class,
        ]);
    }
}
