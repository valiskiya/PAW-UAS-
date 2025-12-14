<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }
        
        $users = $query->orderBy('full_name')->paginate(20);
        $roles = Role::all();
        
        return view('users.index', compact('users', 'roles'));
    }
    
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'role_id'   => 'required|exists:roles,id',
            'username'  => 'required|unique:users,username',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6|confirmed',
            'full_name' => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string',
        ]);
        
        User::create([
            'role_id'   => $request->role_id,
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'full_name' => $request->full_name,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'status'    => 'active',
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }
    
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }
    
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role_id'   => 'required|exists:roles,id',
            'username'  => 'required|unique:users,username,' . $user->id,
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'full_name' => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string',
            'status'    => 'required|in:active,inactive',
        ]);
        
        $user->update($request->except('password'));
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate');
    }
    
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
        ]);
        
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        
        return back()->with('success', 'Password berhasil direset');
    }
    
    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);
        
        return back()->with('success', 'Status user berhasil diubah');
    }
    
    public function settings()
    {
        return view('admin.setting');
    }

    /**
     * Halaman daftar backup.
     * Route: GET /admin/backup  (admin.backup)
     */
    public function backup()
    {
        $backups = [];

        if (Storage::disk('local')->exists('backups')) {
            $files = Storage::disk('local')->files('backups');

            foreach ($files as $file) {
                $backups[] = [
                    'name' => basename($file),
                    'size' => Storage::disk('local')->size($file),
                    'date' => Storage::disk('local')->lastModified($file),
                ];
            }

            // urutkan terbaru di atas
            usort($backups, function ($a, $b) {
                return $b['date'] <=> $a['date'];
            });
        }
        
        return view('admin.backup', compact('backups'));
    }

    /**
     * Membuat backup baru (sederhana: summary data).
     * Route: POST /admin/backup/create  (admin.backup.create)
     */
    public function createBackup()
    {
        // Pastikan folder backups ada
        if (!Storage::disk('local')->exists('backups')) {
            Storage::disk('local')->makeDirectory('backups');
        }

        // Nama file: backup_YYYYMMDD_HHMMSS.json
        $fileName = 'backup_' . now()->format('Ymd_His') . '.json';
        $path     = 'backups/' . $fileName;

        // Isi backup: ringkasan data (cukup untuk tugas)
        $data = [
            'generated_at' => now()->toDateTimeString(),
            'summary'      => [
                'users'        => User::count(),
                'products'     => Product::count(),
                'transactions' => Transaction::count(),
                'customers'    => Customer::count(),
            ],
        ];

        Storage::disk('local')->put($path, json_encode($data, JSON_PRETTY_PRINT));

        return redirect()
            ->route('admin.backup')
            ->with('success', 'Backup berhasil dibuat.');
    }

    /**
     * Download file backup.
     * Route: GET /admin/backup/download/{file}  (admin.backup.download)
     */
    public function downloadBackup(string $file)
    {
        $path = 'backups/' . $file;

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        $fullPath = storage_path('app/' . $path);

        return response()->download($fullPath);
    }
    
    public function logs()
    {
        // Saat ini route logs sudah diarahkan ke ActivityLogController@index,
        // method ini dibiarkan saja untuk jaga-jaga jika masih ada yang memanggil.
        return view('admin.logs');
    }
    
    public function monitoring()
    {
        $stats = [
            'users'              => User::count(),
            'products'           => \App\Models\Product::count(),
            'transactions_today' => \App\Models\Transaction::whereDate('transaction_date', today())->count(),
            'revenue_today'      => \App\Models\Transaction::whereDate('transaction_date', today())
                                        ->where('status', 'completed')
                                        ->sum('total'),
        ];
        
        return view('admin.monitoring', compact('stats'));
    }
}
