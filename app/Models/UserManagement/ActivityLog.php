<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    //
    protected $table = 'activity_logs';
    protected $primaryKey = 'activity_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'activity_type',
        'description',
        'module_name',
        'activity_timestamp',
    ];

    protected $casts = [
        'activity_timestamp' => 'datetime',
    ];
}
