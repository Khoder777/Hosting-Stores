<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MarketOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('market_owners')->insert([

            'email' => 'marketOwner@gmail.com',
            'password' => Hash::make('123123123'),
            'market_id' => '1',
        ]);
    }
}