<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectIsActiveLog extends Model
{
    use HasFactory;

    protected $table = 'project_is_active_log';
    public $timestamps = false;

    protected $fillable = [
        'is_active',
        'created',
        'project_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'unicode');
    }
}
