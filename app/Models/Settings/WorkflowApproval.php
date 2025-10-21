<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class WorkflowApproval extends Model
{
    //
    protected $table = 'workflow_approvals';
    protected $primaryKey = 'approval_id';
    public $timestamps = false;

    protected $fillable = [
        'workflow_id',
        'reference_type',
        'reference_id',
        'approver_id',
        'approval_level',
        'status',
        'comments',
        'action_date',
    ];

    protected $casts = [
        'action_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
