@extends('layouts.app')

@section('title', 'Edit Absensi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('manajer.attendances.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Edit Absensi</h1>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('manajer.attendances.update', $attendance->id) }}">
            @csrf
            @method('PUT')
            
            <!-- Info Karyawan (Read Only) -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Karyawan</p>
                <p class="font-semibold text-lg">{{ $attendance->employee->full_name }}</p>
                <p class="text-sm text-gray-600 mt-2">Shift: {{ $attendance->shift->name }}</p>
                <p class="text-sm text-gray-600">Tanggal: {{ $attendance->attendance_date->format('d/m/Y') }}</p>
            </div>
            
            <!-- Status -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="hadir" {{ old('status', $attendance->status) == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ old('status', $attendance->status) == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ old('status', $attendance->status) == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpha" {{ old('status', $attendance->status) == 'alpha' ? 'selected' : '' }}>Alpha</option>
                </select>
            </div>
            
            <!-- Check In -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Waktu Masuk
                </label>
                <input type="time" name="check_in" value="{{ old('check_in', $attendance->check_in ? $attendance->check_in->format('H:i') : '') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <!-- Check Out -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Waktu Keluar
                </label>
                <input type="time" name="check_out" value="{{ old('check_out', $attendance->check_out ? $attendance->check_out->format('H:i') : '') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <!-- Notes -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Catatan
                </label>
                <textarea name="notes" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Catatan tambahan (opsional)">{{ old('notes', $attendance->notes) }}</textarea>
            </div>
            
            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('manajer.attendances.index') }}" 
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