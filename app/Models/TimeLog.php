<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    protected $fillable = [
        'project_id',
        'start_time',
        'end_time',
        'description',
        'hours',
        'billable'
    ];

    protected $dates = ['start_time', 'end_time'];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
