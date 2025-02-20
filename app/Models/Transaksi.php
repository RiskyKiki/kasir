<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// Import trait LogsActivity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'pelanggan_id',
        'user_id',
        'tanggal_transaksi',
        'subtotal',
        'diskon',
        'pajak',
        'total',
        'pembayaran',
        'kembalian',
        'poin_didapat',
        'poin_digunakan',
        'created_by',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'pelanggan_id',
                'user_id',
                'tanggal_transaksi',
                'subtotal',
                'diskon',
                'pajak',
                'total',
                'pembayaran',
                'kembalian',
                'poin_didapat',
                'poin_digunakan'
            ])
            ->useLogName('Transaksi')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Transaksi telah di{$eventName}";
            });
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
