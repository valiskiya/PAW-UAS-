@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('manajer.customers.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Pelanggan Baru</h1>
        
        <form method="POST" action="{{ route('manajer.customers.store') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Kode Pelanggan *</label>
                    <input type="text" name="code" value="{{ old('code') }}" 
                           class="w-full px-4 py-2 border rounded-lg" placeholder="CUST001" required>
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Nama Pelanggan *</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full px-4 py-2 border rounded-lg" placeholder="Ahmad Fauzi" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tipe Pelanggan *</label>
                    <select name="type" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="non_member" {{ old('type') === 'non_member' ? 'selected' : '' }}>Non Member (Umum)</option>
                        <option value="member" {{ old('type') === 'member' ? 'selected' : '' }}>Member</option>
                        <option value="wholesale_low" {{ old('type') === 'wholesale_low' ? 'selected' : '' }}>Grosir Rendah (Diskon 5%)</option>
                        <option value="wholesale_high" {{ old('type') === 'wholesale_high' ? 'selected' : '' }}>Grosir Tinggi (Diskon 10% + Free Ongkir)</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" 
                           class="w-full px-4 py-2 border rounded-lg" placeholder="081234567890">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-4 py-2 border rounded-lg" placeholder="customer@example.com">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Alamat</label>
                    <textarea name="address" rows="3" 
                              class="w-full px-4 py-2 border rounded-lg" placeholder="Alamat lengkap...">{{ old('address') }}</textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('manajer.customers.index') }}" 
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