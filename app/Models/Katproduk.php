<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// Import trait LogsActivity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Katproduk extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'katproduks';
    protected $fillable = [
        'kode',
        'nama',
        'created_by',
        'updated_by',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kode', 'nama'])
            ->useLogName('Kategori')
            ->setDescriptionForEvent(function (string $eventName) {
                return "Kategori telah di{$eventName}";
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
}
