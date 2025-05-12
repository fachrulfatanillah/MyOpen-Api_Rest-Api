<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserStatusLog extends Model
{
    use HasFactory;

    protected $table = 'users_status_log';

    public $timestamps = false;

    protected $fillable = [
        'status',
        'created',
        'user_id',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}
