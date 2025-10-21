<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    //
    protected $table = 'payroll';
    protected $primaryKey = 'payroll_id';
    public $timestamps = false;

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

    protected $casts = [
        'base_salary' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'payment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
