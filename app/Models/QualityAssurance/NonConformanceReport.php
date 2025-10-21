<?php

namespace App\Models\QualityAssurance;

use Illuminate\Database\Eloquent\Model;

class NonConformanceReport extends Model
{
    //
    protected $table = 'non_conformance_reports';
    protected $primaryKey = 'ncr_id';

    protected $fillable = [
        'ncr_code',
        'report_date',
        'reported_by',
        'nc_category',
        'nc_type',
        'product_id',
        'batch_id',
        'description',
        'severity',
        'status',
        'immediate_action',
    ];

    protected $casts = [
        'report_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
