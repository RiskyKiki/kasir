<?php

namespace Database\Seeders;

use App\Models\Katproduk;
use App\Models\Pelanggan;
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
            'email'    => 'admin@example.com',
            'password' =>  Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'username' => 'petugas',
            'email'    => 'petugas@example.com',
            'password' =>  Hash::make('password'),
            'role'     => 'petugas',
        ]);

        Pelanggan::create([
            'nama'    => 'pelanggan1',
            'telepon' => '123456789',
            'alamat'  => 'disuatu tempat',
            'tipe'    => 'Umum',
            'poin'    => '0',
        ]);

        Pelanggan::create([
            'nama'    => '1naggnalap',
            'telepon' => '987654321',
            'alamat'  => 'tapmet utausid',
            'tipe'    => 'Loyal',
            'poin'    => '0',
        ]);

        Katproduk::create([
            'kode' => 'PDR-000',
            'nama' => 'Tidak ada',
        ]);
    }
}
