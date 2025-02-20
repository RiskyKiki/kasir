<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import trait LogsActivity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DetailTransaksi extends Model{   
    use HasFactory, LogsActivity;

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'jumlah',
        'harga',
        'subtotal',
        'created_by',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['transaksi_id', 'produk_id', 'jumlah', 'harga', 'subtotal'])
            ->useLogName('Detail Transaksi')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Detail Transaksi telah di{$eventName}";
            });
    }
    
    public function getDescriptionForEvent(string $eventName): string {
        return "Detail Transaksi telah di{$eventName}";
    }

    public function transaksi(){
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
    public function produk(){
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
