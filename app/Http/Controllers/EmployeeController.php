<?php
// ===== EmployeeController.php =====
namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('user');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employee_code', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%");
            });
        }
        
        $employees = $query->orderBy('full_name')->paginate(20);
        
        return view('employees.index', compact('employees'));
    }
    
    public function create()
    {
        return view('employees.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|unique:employees,employee_code',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'hire_date' => 'required|date',
            'position' => 'required|in:kasir,logistik,manajer,lainnya',
            'salary_grade' => 'required|integer|min:3|max:10',
        ]);
        
        $employee = Employee::create($request->all());
        
        return redirect()->route('manajer.employees.index')
            ->with('success', 'Karyawan berhasil ditambahkan');
    }
    
    public function show(Employee $employee)
    {
        $employee->load('user', 'attendances.shift', 'salaryPayments');
        
        return view('employees.show', compact('employee'));
    }
    
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }
    
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_code' => 'required|unique:employees,employee_code,' . $employee->id,
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'hire_date' => 'required|date',
            'position' => 'required|in:kasir,logistik,manajer,lainnya',
            'salary_grade' => 'required|integer|min:3|max:10',
            'status' => 'required|in:active,inactive',
        ]);
        
        $employee->update($request->all());
        
        return redirect()->route('manajer.employees.index')
            ->with('success', 'Karyawan berhasil diupdate');
    }
    
    public function destroy(Employee $employee)
    {
        if ($employee->attendances()->count() > 0) {
            return back()->with('error', 'Karyawan tidak dapat dihapus karena memiliki riwayat absensi');
        }
        
        $employee->delete();
        
        return redirect()->route('manajer.employees.index')
            ->with('success', 'Karyawan berhasil dihapus');
    }
}
