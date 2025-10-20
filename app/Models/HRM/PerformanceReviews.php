<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class PerformanceReviews extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'performance_reviews';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'review_id';

    /**
     * The attributes that are mass assignable.
     */
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

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
