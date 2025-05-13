<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Users extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $timestamps = false;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->unicode = (string) Str::uuid();
        });
    }

    public function levelLogs()
    {
        return $this->hasMany(UserLevelLog::class, 'user_id');
    }

    public function statusLogs()
    {
        return $this->hasMany(UserStatusLog::class, 'user_id');
    }
}
