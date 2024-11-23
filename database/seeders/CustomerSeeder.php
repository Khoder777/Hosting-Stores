<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([

            'email' => 'Customer@gmail.com',
            'password' => Hash::make('123123123'),
            'full_name'=>'alizene',
            'image'=>'images/alizene',
            'phone_number'=>'0938449863',
            'status'=>'1',
            'city_id'=>'1',
            'otp'=>'2131',
            'verified_email'=>'1',

        ]);
    }
}
