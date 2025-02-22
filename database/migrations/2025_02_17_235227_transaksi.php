<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('tanggal_transaksi')->useCurrent();
            $table->decimal('subtotal', 10, 2);      
            $table->decimal('diskon', 10, 2)->default(0);
            $table->decimal('pajak', 10, 2)->default(0);   
            $table->decimal('total', 10, 2);           
            $table->decimal('pembayaran', 10, 2);      
            $table->decimal('kembalian', 10, 2)->default(0);
            $table->integer('poin_didapat')->default(0)->nullable();
            $table->integer('poin_digunakan')->default(0)->nullable();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};

