<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'payroll';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'payroll_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'base_salary',
        'overtime_pay',
        'allowances',
        'deductions',
        'gross_salary',
        'net_salary',
        'payment_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
