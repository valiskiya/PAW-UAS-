<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'total_shifts',
        'total_present',
        'total_leave',
        'total_sick',
        'total_alpha',
        'salary_grade',
        'base_salary_per_shift',
        'total_salary',
        'payment_date',
        'status',
        'notes',
        'paid_by',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'total_shifts' => 'integer',
        'total_present' => 'integer',
        'total_leave' => 'integer',
        'total_sick' => 'integer',
        'total_alpha' => 'integer',
        'salary_grade' => 'integer',
        'base_salary_per_shift' => 'decimal:2',
        'total_salary' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}