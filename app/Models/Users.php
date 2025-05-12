<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Users extends Model
{
    use HasFactory;

    // Nama tabel (opsional kalau sudah sesuai konvensi)
    protected $table = 'users';

    // Primary key
    protected $primaryKey = 'id';

    // Tipe primary key
    protected $keyType = 'int';

    // Tidak menggunakan created_at dan updated_at default Laravel
    public $timestamps = false;

    // Field yang boleh diisi secara mass-assignment
    protected $fillable = [
        'unicode',
        'google_id',
        'name',
        'email',
        'email_verified',
        'image_url',
        'status',
        'level',
        'created',
        'updated',
    ];

    // Auto-generate UUID ketika membuat user baru
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->unicode = (string) Str::uuid();
        });
    }

    // Relasi ke level log
    public function levelLogs()
    {
        return $this->hasMany(UserLevelLog::class, 'user_id');
    }

    // Relasi ke status log
    public function statusLogs()
    {
        return $this->hasMany(UserStatusLog::class, 'user_id');
    }
}
