@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('manajer.employees.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Karyawan Baru</h1>
        
        <form method="POST" action="{{ route('manajer.employees.store') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Kode Karyawan *</label>
                    <input type="text" name="employee_code" value="{{ old('employee_code') }}" 
                           class="w-full px-4 py-2 border rounded-lg" placeholder="EMP001" required>
                    @error('employee_code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap *</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" 
                           class="w-full px-4 py-2 border rounded-lg" placeholder="Budi Santoso" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Telepon *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" 
                           class="w-full px-4 py-2 border rounded-lg" placeholder="081234567890" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tanggal Bergabung *</label>
                    <input type="date" name="hire_date" value="{{ old('hire_date') }}" 
                           class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Posisi *</label>
                    <select name="position" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="kasir" {{ old('position') === 'kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="logistik" {{ old('position') === 'logistik' ? 'selected' : '' }}>Logistik</option>
                        <option value="manajer" {{ old('position') === 'manajer' ? 'selected' : '' }}>Manajer</option>
                        <option value="lainnya" {{ old('position') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Strata Gaji *</label>
                    <select name="salary_grade" class="w-full px-4 py-2 border rounded-lg" required>
                        @for($i = 3; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('salary_grade', 3) == $i ? 'selected' : '' }}>
                                Strata {{ $i }} (Rp {{ number_format(100000 * pow(1.2, $i - 3), 0, ',', '.') }}/shift)
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Alamat *</label>
                    <textarea name="address" rows="3" 
                              class="w-full px-4 py-2 border rounded-lg" placeholder="Alamat lengkap..." required>{{ old('address') }}</textarea>
                </div>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-lg mt-4">
                <p class="text-sm text-blue-800">
                    <strong>Informasi Gaji:</strong><br>
                    - Strata 3 = Rp 100.000/shift<br>
                    - Setiap naik 1 strata = +20%<br>
                    - Gaji dihitung per shift hadir
                </p>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('manajer.employees.index') }}" 
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