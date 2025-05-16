<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class TablesOfProject extends Model
{
    use HasFactory;

    protected $table = 'tables_of_projects';
    public $timestamps = false;

    protected $fillable = [
        'unicode',
        'table_name',
        'status',
        'created',
        'updated',
        'project_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->unicode = (string) Str::uuid();
        });
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'unicode');
    }
}
