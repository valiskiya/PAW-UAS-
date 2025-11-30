@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('manajer.employees.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Edit Karyawan</h1>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('manajer.employees.update', $employee->id) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Karyawan -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Kode Karyawan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="employee_code" value="{{ old('employee_code', $employee->employee_code) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('employee_code') border-red-500 @enderror" 
                           required>
                    @error('employee_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="full_name" value="{{ old('full_name', $employee->full_name) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('full_name') border-red-500 @enderror" 
                           required>
                    @error('full_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Telepon -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror" 
                           required>
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Tanggal Masuk -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Tanggal Masuk <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="hire_date" value="{{ old('hire_date', $employee->hire_date->format('Y-m-d')) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('hire_date') border-red-500 @enderror" 
                           required>
                    @error('hire_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Posisi -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Posisi <span class="text-red-500">*</span>
                    </label>
                    <select name="position" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="kasir" {{ old('position', $employee->position) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="logistik" {{ old('position', $employee->position) == 'logistik' ? 'selected' : '' }}>Logistik</option>
                        <option value="manajer" {{ old('position', $employee->position) == 'manajer' ? 'selected' : '' }}>Manajer</option>
                        <option value="lainnya" {{ old('position', $employee->position) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                
                <!-- Strata Gaji -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Strata Gaji <span class="text-red-500">*</span>
                    </label>
                    <select name="salary_grade" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @for($i = 3; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('salary_grade', $employee->salary_grade) == $i ? 'selected' : '' }}>
                                Strata {{ $i }} (Rp {{ number_format(100000 * (1 + (($i - 3) * 0.20)), 0, ',', '.') }}/shift)
                            </option>
                        @endfor
                    </select>
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            
            <!-- Alamat -->
            <div class="mt-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <textarea name="address" rows="4" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror" 
                          required>{{ old('address', $employee->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('manajer.employees.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection