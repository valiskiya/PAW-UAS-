<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeForRole(); // opsional, lihat method di bawah

        $query = ActivityLog::with('user')->latest();

        // filter level (info / warning / error)
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // filter pencarian teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('username', 'like', "%{$search}%")
                         ->orWhere('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->paginate(50);

        return view('admin.logs', compact('logs'));
    }

    /**
     * Boleh dihapus kalau kamu sudah pakai Gate/Policy sendiri.
     * Ini hanya contoh sederhana: hanya Admin TI yang boleh buka log.
     */
    protected function authorizeForRole(): void
    {
        if (!auth()->check() || !auth()->user()->isAdminTI()) {
            abort(403, 'Tidak memiliki hak akses untuk melihat log aktivitas.');
        }
    }
}
