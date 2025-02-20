<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// Import trait LogsActivity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Produk extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'kode',
        'nama',
        'kategori_id',
        'tanggal_kadaluarsa',
        'tanggal_pembelian',
        'hpp',
        'harga1',
        'harga2',
        'harga3',
        'stok',
        'min_stok',
        'created_by',
        'updated_by',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'kode',
                'nama',
                'kategori_id',
                'tanggal_kadaluarsa',
                'tanggal_pembelian',
                'hpp',
                'harga1',
                'harga2',
                'harga3',
                'stok',
                'min_stok'
            ])
            ->useLogName('Produk')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Produk telah di{$eventName}";
            });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function kategori()
    {
        return $this->belongsTo(Katproduk::class, 'kategori_id');
    }
}
