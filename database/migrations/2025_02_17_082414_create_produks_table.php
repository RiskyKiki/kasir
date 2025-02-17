<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama', 255);
            $table->foreignId('kategori_id')->constrained('katproduks')->onDelete('cascade');
            $table->date('tanggal_pembelian');
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->decimal('hpp', 10, 2);
            $table->decimal('harga1', 10, 2);
            $table->decimal('harga2', 10, 2);
            $table->decimal('harga3', 10, 2);
            $table->integer('stok');
            $table->integer('min_stok');
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
