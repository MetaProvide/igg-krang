<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'ron',
            'email' => 'ron@example.com',
            'password' => Hash::make('password'), 
        ]);
        
        DB::table('users')->insert([
            'name' => 'henry',
            'email' => 'henry@example.com',
            'password' => Hash::make('password'), 
        ]);

        DB::table('users')->insert([
            'name' => 'igor',
            'email' => 'igor@example.com',
            'password' => Hash::make('password'), 
        ]);
    }
}
