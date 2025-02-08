<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */


     public function run()
     {
         User::create([
             'username' => 'admin',
             'email' => 'admin@example.com',
             'password' => Hash::make('password'),
             'role' => 'admin',
         ]);
 
         User::create([
             'username' => 'petugas',
             'email' => 'petugas@example.com',
             'password' => Hash::make('password'),
             'role' => 'petugas',
         ]);
     }

}
