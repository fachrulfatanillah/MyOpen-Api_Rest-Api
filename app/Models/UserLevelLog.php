<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserLevelLog extends Model
{
    use HasFactory;

    protected $table = 'users_level_log';

    public $timestamps = false;

    protected $fillable = [
        'level',
        'created',
        'user_id',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}
