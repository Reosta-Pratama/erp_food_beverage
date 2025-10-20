<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Certifications extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'certifications';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'certification_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'certification_name',
        'issuing_authority',
        'issue_date',
        'expiry_date',
        'certificate_number',
        'document_path',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
