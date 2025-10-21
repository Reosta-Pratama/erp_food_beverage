<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    //
    protected $table = 'certifications';
    protected $primaryKey = 'certification_id';
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'certification_name',
        'issuing_authority',
        'issue_date',
        'expiry_date',
        'certificate_number',
        'document_path',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
