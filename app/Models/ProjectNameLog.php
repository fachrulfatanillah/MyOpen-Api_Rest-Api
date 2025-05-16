<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectNameLog extends Model
{
    use HasFactory;

    protected $table = 'project_name_log';
    public $timestamps = false;

    protected $fillable = [
        'project_name',
        'created',
        'project_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'unicode');
    }
}
