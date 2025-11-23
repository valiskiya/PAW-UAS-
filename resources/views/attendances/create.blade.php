@extends('layouts.app')

@section('title', 'Tambah Absensi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('manajer.attendances.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Absensi</h1>
        
        <form method="POST" action="{{ route('manajer.attendances.store') }}" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Karyawan *</label>
                <select name="employee_id" class="w-full px-4 py-2 border rounded-lg" required>
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->full_name }} ({{ $employee->employee_code }})</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Shift *</label>
                <select name="shift_id" class="w-full px-4 py-2 border rounded-lg" required>
                    <option value="">-- Pilih Shift --</option>
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Tanggal *</label>
                <input type="date" name="attendance_date" value="{{ old('attendance_date', date('Y-m-d')) }}" 
                       class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Status *</label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg" required>
                    <option value="hadir">Hadir</option>
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                    <option value="alpha">Alpha</option>
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Check In</label>
                    <input type="time" name="check_in" value="{{ old('check_in') }}" 
                           class="w-full px-4 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Check Out</label>
                    <input type="time" name="check_out" value="{{ old('check_out') }}" 
                           class="w-full px-4 py-2 border rounded-lg">
                </div>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Catatan</label>
                <textarea name="notes" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('manajer.attendances.index') }}" 
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