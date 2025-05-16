<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TablesOfProjectsNameLog extends Model
{
    use HasFactory;

    protected $table = 'tables_of_projects_name_log';
    public $timestamps = false;

    protected $fillable = [
        'table_name',
        'created',
        'table_id',
    ];

    public function table()
    {
        return $this->belongsTo(TablesOfProject::class, 'table_id', 'unicode');
    }
}
