<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_code',
        'full_name',
        'phone',
        'address',
        'hire_date',
        'position',
        'salary_grade',
        'base_salary_per_shift',
        'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary_grade' => 'integer',
        'base_salary_per_shift' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function salaryPayments()
    {
        return $this->hasMany(SalaryPayment::class);
    }

    // Helper: Hitung gaji per shift berdasarkan strata
    public function calculateSalaryPerShift()
    {
        // Strata 3 = 100.000
        // Setiap naik 1 strata = +20%
        $baseSalary = 100000;
        $increment = 0.20; // 20%
        
        $gradeIncrease = $this->salary_grade - 3;
        return $baseSalary * (1 + ($gradeIncrease * $increment));
    }

    // Helper: Hitung total izin dalam bulan tertentu
    public function getTotalLeaveInMonth($month, $year)
    {
        return $this->attendances()
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->where('status', 'izin')
            ->count();
    }
}