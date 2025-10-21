<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $table = 'employees';
    protected $primaryKey = 'employee_id';

    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'id_number',
        'department_id',
        'position_id',
        'join_date',
        'resign_date',
        'employment_status',
        'base_salary',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'join_date' => 'date',
        'resign_date' => 'date',
        'base_salary' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
