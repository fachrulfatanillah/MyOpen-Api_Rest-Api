<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectStatusLog extends Model
{
    use HasFactory;

    protected $table = 'project_status_log';
    public $timestamps = false;

    protected $fillable = [
        'status',
        'created',
        'project_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'unicode');
    }
}
