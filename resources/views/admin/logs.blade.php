@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Log Aktivitas Sistem</h1>
            <p class="text-sm text-gray-500">
                Pantau login, percobaan login gagal, perubahan data master, dan aktivitas penting lainnya.
            </p>
        </div>
        <p class="text-gray-600 text-sm">
            {{ now()->isoFormat('dddd, D MMMM Y') }}
        </p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
        <!-- Filter Bar -->
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="col-span-1 md:col-span-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari log (mis. username, aksi, keterangan)..."
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <div class="flex items-center gap-2">
                <select name="level"
                        class="flex-1 px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Level</option>
                    <option value="info" {{ request('level') === 'info' ? 'selected' : '' }}>Info</option>
                    <option value="warning" {{ request('level') === 'warning' ? 'selected' : '' }}>Warning</option>
                    <option value="error" {{ request('level') === 'error' ? 'selected' : '' }}>Error</option>
                </select>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg">
                    Filter
                </button>
            </div>
        </form>

        <!-- Log Console -->
        <div class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-xs md:text-sm h-96 overflow-y-auto">
            <div class="space-y-1">
                @forelse($logs as $log)
                    @php
                        $color = match($log->level) {
                            'warning' => 'text-yellow-300',
                            'error'   => 'text-red-400',
                            default   => 'text-green-400',
                        };
                    @endphp
                    <div class="{{ $color }}">
                        [{{ $log->created_at->format('Y-m-d H:i:s') }}]
                        {{ strtoupper(str_pad($log->level, 7, ' ', STR_PAD_RIGHT)) }} :
                        {{ $log->action }}
                        @if($log->user)
                            (user: {{ $log->user->username }})
                        @endif
                        @if($log->description)
                            - {{ $log->description }}
                        @endif
                        @if($log->ip_address)
                            [IP: {{ $log->ip_address }}]
                        @endif
                    </div>
                @empty
                    <div class="text-gray-400">
                        --- Belum ada log aktivitas yang tercatat ---
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-2">
            {{ $logs->withQueryString()->links() }}
        </div>

        <div class="text-sm text-gray-600">
            <p class="font-semibold mb-1">Tips untuk Admin TI:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Periksa log secara berkala untuk mendeteksi aktivitas mencurigakan atau error sistem.</li>
                <li>Gunakan filter <em>level</em> untuk fokus pada peringatan (warning) dan error.</li>
                <li>Jika ada percobaan login gagal berulang, pertimbangkan untuk mengunci akun terkait.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
