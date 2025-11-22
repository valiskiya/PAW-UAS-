<?php

namespace App\Http\Controllers;

use App\Models\SalaryPayment;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalaryPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = SalaryPayment::with('employee');
        
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }
        
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $salaries = $query->latest('year')
            ->latest('month')
            ->paginate(20);
        
        return view('salaries.index', compact('salaries'));
    }
    
    public function generate()
    {
        $employees = Employee::where('status', 'active')->get();
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        return view('salaries.generate', compact('employees', 'currentMonth', 'currentYear'));
    }
    
    public function calculate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);
        
        $results = [];
        
        foreach ($request->employee_ids as $employeeId) {
            $employee = Employee::find($employeeId);
            
            // Check if already exists
            $exists = SalaryPayment::where('employee_id', $employeeId)
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->exists();
                
            if ($exists) {
                continue;
            }
            
            // Get attendance data
            $attendances = Attendance::where('employee_id', $employeeId)
                ->whereMonth('attendance_date', $request->month)
                ->whereYear('attendance_date', $request->year)
                ->get();
            
            $totalShifts = $attendances->count();
            $totalPresent = $attendances->where('status', 'hadir')->count();
            $totalLeave = $attendances->where('status', 'izin')->count();
            $totalSick = $attendances->where('status', 'sakit')->count();
            $totalAlpha = $attendances->where('status', 'alpha')->count();
            
            // Calculate salary
            $baseSalary = $employee->calculateSalaryPerShift();
            $totalSalary = $baseSalary * $totalPresent;
            
            // Create salary record
            $salary = SalaryPayment::create([
                'employee_id' => $employeeId,
                'month' => $request->month,
                'year' => $request->year,
                'total_shifts' => $totalShifts,
                'total_present' => $totalPresent,
                'total_leave' => $totalLeave,
                'total_sick' => $totalSick,
                'total_alpha' => $totalAlpha,
                'salary_grade' => $employee->salary_grade,
                'base_salary_per_shift' => $baseSalary,
                'total_salary' => $totalSalary,
                'status' => 'pending',
            ]);
            
            $results[] = $salary;
        }
        
        return redirect()->route('manajer.salaries.index')
            ->with('success', count($results) . ' gaji karyawan berhasil dihitung');
    }
    
    public function pay(Request $request, SalaryPayment $salary)
    {
        if ($salary->status === 'paid') {
            return back()->with('error', 'Gaji ini sudah dibayarkan');
        }
        
        $salary->update([
            'status' => 'paid',
            'payment_date' => now(),
            'paid_by' => auth()->id(),
        ]);
        
        return back()->with('success', 'Pembayaran gaji berhasil dicatat');
    }
}