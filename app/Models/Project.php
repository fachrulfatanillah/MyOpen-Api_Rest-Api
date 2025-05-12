<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    public $timestamps = false;

    protected $fillable = [
        'unicode',
        'project_name',
        'is_active',
        'status',
        'created',
        'updated',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            $project->unicode = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'unicode');
    }
}
