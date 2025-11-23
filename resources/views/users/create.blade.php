@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah User Baru</h1>
        
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Username *</label>
                    <input type="text" name="username" value="{{ old('username') }}" 
                           class="w-full px-4 py-2 border rounded-lg" placeholder="username" required>
                    @error('username')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-4 py-2 border rounded-lg" placeholder="user@example.com" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap *</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" 
                           class="w-full px-4 py-2 border rounded-lg" placeholder="Nama Lengkap" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Role *</label>
                    <select name="role_id" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" 
                           class="w-full px-4 py-2 border rounded-lg" placeholder="081234567890">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Alamat</label>
                    <textarea name="address" rows="2" 
                              class="w-full px-4 py-2 border rounded-lg" placeholder="Alamat lengkap...">{{ old('address') }}</textarea>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Password *</label>
                    <input type="password" name="password" 
                           class="w-full px-4 py-2 border rounded-lg" placeholder="Minimal 6 karakter" required minlength="6">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" 
                           class="w-full px-4 py-2 border rounded-lg" placeholder="Ketik ulang password" required minlength="6">
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection