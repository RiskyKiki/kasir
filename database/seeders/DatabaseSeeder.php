<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Produk;
use App\Models\Katproduk;
use App\Models\Pelanggan;
use Illuminate\Support\Carbon;
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
            'username'   => 'admin',
            'email'      => 'admin@example.com',
            'password'   =>  Hash::make('password'),
            'role'       => 'admin',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        User::create([
            'username'   => 'petugas',
            'email'      => 'petugas@example.com',
            'password'   =>  Hash::make('password'),
            'role'       => 'petugas',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Pelanggan::create([
            'nama'       => 'pelanggan1',
            'telepon'    => '123456789',
            'alamat'     => 'disuatu tempat',
            'tipe'       => 'Umum',
            'poin'       => '0',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Pelanggan::create([
            'nama'       => '1naggnalap',
            'telepon'    => '987654321',
            'alamat'     => 'tapmet utausid',
            'tipe'       => 'Loyal',
            'poin'       => '0',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Katproduk::create([
            'kode'       => 'CAT-001',
            'nama'       => 'Mie instan',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Produk::create([
            'kode'              => 'PRD-001',
            'nama'              => 'Mie Goreng',
            'kategori_id'       => 1,
            'tanggal_kadaluarsa' => '2026-12-31',
            'tanggal_pembelian' => Carbon::now(),
            'hpp'               => 2500.00,
            'harga1'            => 2750.00,
            'harga2'            => 3000.00,
            'harga3'            => 3250.00,
            'stok'              => 100,
            'min_stok'          => 10,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);
    }
}
