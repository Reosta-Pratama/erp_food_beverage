<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;

class ActivityLogs extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'activity_logs';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'activity_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'activity_type',
        'description',
        'module_name',
        'activity_timestamp',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'activity_timestamp' => 'datetime',
    ];
}
