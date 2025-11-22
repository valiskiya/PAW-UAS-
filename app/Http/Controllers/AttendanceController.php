<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['employee', 'shift']);
        
        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        } else {
            $query->whereDate('attendance_date', today());
        }
        
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $attendances = $query->orderBy('attendance_date', 'desc')->paginate(20);
        $employees = Employee::where('status', 'active')->get();
        
        return view('attendances.index', compact('attendances', 'employees'));
    }
    
    public function create()
    {
        $employees = Employee::where('status', 'active')->get();
        $shifts = Shift::all();
        
        return view('attendances.create', compact('employees', 'shifts'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string',
        ]);
        
        // Check duplicate
        $exists = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('attendance_date', $request->attendance_date)
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Absensi untuk karyawan ini pada tanggal tersebut sudah ada');
        }
        
        // Check izin limit (6 kali = warning, 7 = max)
        $employee = Employee::find($request->employee_id);
        $month = Carbon::parse($request->attendance_date)->month;
        $year = Carbon::parse($request->attendance_date)->year;
        
        if ($request->status === 'izin') {
            $leaveCount = $employee->getTotalLeaveInMonth($month, $year);
            
            if ($leaveCount >= 7) {
                return back()->with('error', 'Karyawan sudah mencapai batas maksimal izin (7 kali) bulan ini');
            }
            
            if ($leaveCount == 6) {
                session()->flash('warning', 'PERINGATAN: Ini adalah izin ke-6 untuk karyawan ini bulan ini!');
            }
        }
        
        Attendance::create($request->all());
        
        return redirect()->route('manajer.attendances.index')
            ->with('success', 'Absensi berhasil ditambahkan');
    }
    
    public function edit(Attendance $attendance)
    {
        $employees = Employee::where('status', 'active')->get();
        $shifts = Shift::all();
        
        return view('attendances.edit', compact('attendance', 'employees', 'shifts'));
    }
    
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string',
        ]);
        
        $attendance->update($request->all());
        
        return redirect()->route('manajer.attendances.index')
            ->with('success', 'Absensi berhasil diupdate');
    }
}