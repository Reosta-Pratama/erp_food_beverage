<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    //
    protected $table = 'company_profile';
    protected $primaryKey = 'company_id';

    protected $fillable = [
        'company_name',
        'legal_name',
        'tax_id',
        'address',
        'city',
        'country',
        'postal_code',
        'phone',
        'email',
        'website',
        'logo_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
