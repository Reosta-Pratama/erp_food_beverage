<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    //
    protected $table = 'performance_reviews';
    protected $primaryKey = 'review_id';
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'reviewer_id',
        'review_period_start',
        'review_period_end',
        'performance_score',
        'strengths',
        'weaknesses',
        'comments',
    ];

    protected $casts = [
        'review_period_start' => 'date',
        'review_period_end' => 'date',
        'performance_score' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
