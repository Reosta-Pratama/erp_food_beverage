<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    //
    protected $table = 'workflow';
    protected $primaryKey = 'workflow_id';

    protected $fillable = [
        'workflow_name',
        'module_name',
        'process_name',
        'approval_hierarchy',
        'require_approval',
        'approval_levels',
    ];

    protected $casts = [
        'approval_hierarchy' => 'array',
        'require_approval' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
