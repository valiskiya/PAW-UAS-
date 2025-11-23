@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen User</h1>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Tambah User
        </a>
    </div>
    
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari user..." 
                   class="px-4 py-2 border rounded-lg">
            
            <select name="role_id" class="px-4 py-2 border rounded-lg">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->display_name }}
                    </option>
                @endforeach
            </select>
            
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>
    
    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Username</th>
                    <th class="px-4 py-3 text-left">Nama Lengkap</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold">{{ $user->username }}</td>
                    <td class="px-4 py-3">{{ $user->full_name }}</td>
                    <td class="px-4 py-3">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                            {{ $user->role->display_name }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($user->status) }}
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-green-600 hover:text-green-800 mx-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="openResetPasswordModal({{ $user->id }}, '{{ $user->username }}')" 
                                class="text-orange-600 hover:text-orange-800 mx-1">
                            <i class="fas fa-key"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada data user</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4">Reset Password User</h3>
        
        <form method="POST" id="resetPasswordForm">
            @csrf
            
            <div class="mb-4">
                <p class="text-gray-700 mb-2">Reset password untuk user: <strong id="resetUsername"></strong></p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Password Baru *</label>
                <input type="password" name="new_password" 
                       class="w-full px-4 py-2 border rounded-lg" 
                       placeholder="Minimal 6 karakter" required minlength="6">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Konfirmasi Password *</label>
                <input type="password" name="new_password_confirmation" 
                       class="w-full px-4 py-2 border rounded-lg" 
                       placeholder="Ketik ulang password" required minlength="6">
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeResetPasswordModal()"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                    Batal
                </button>
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-key mr-2"></i>Reset Password
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openResetPasswordModal(userId, username) {
    document.getElementById('resetUsername').textContent = username;
    document.getElementById('resetPasswordForm').action = `/admin/users/${userId}/reset-password`;
    document.getElementById('resetPasswordModal').classList.remove('hidden');
}

function closeResetPasswordModal() {
    document.getElementById('resetPasswordModal').classList.add('hidden');
}
</script>
@endpush
@endsection