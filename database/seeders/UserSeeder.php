<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Serd',
            'surname' => 'Chanthapheng',
            'phone' => '76878346',
            'dob' => '2023-2-2',
            'password' => '56489649',
            'otp' => '1234'
        ]);
    }
}
