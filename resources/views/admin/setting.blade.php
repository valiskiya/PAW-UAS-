@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Pengaturan Sistem</h1>
    
    <div class="space-y-6">
        <!-- General Settings -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Pengaturan Umum</h2>
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Nama Toko</label>
                    <input type="text" name="store_name" value="Ritel ABC" 
                           class="w-full px-4 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Alamat Toko</label>
                    <textarea name="store_address" rows="2" 
                              class="w-full px-4 py-2 border rounded-lg">Jl. Raya No. 123, Jakarta</textarea>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Telepon Toko</label>
                    <input type="text" name="store_phone" value="021-12345678" 
                           class="w-full px-4 py-2 border rounded-lg">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Email Toko</label>
                    <input type="email" name="store_email" value="info@ritelabc.com" 
                           class="w-full px-4 py-2 border rounded-lg">
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                        <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Shift Settings -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Pengaturan Shift</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-semibold">Shift Pagi</p>
                        <p class="text-sm text-gray-600">07:00 - 15:00</p>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-semibold">Shift Sore</p>
                        <p class="text-sm text-gray-600">15:00 - 23:00</p>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-semibold">Shift Malam</p>
                        <p class="text-sm text-gray-600">23:00 - 07:00</p>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- System Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Informasi Sistem</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">Versi Aplikasi</p>
                    <p class="font-semibold">1.0.0</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Laravel Version</p>
                    <p class="font-semibold">{{ app()->version() }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">PHP Version</p>
                    <p class="font-semibold">{{ PHP_VERSION }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Database</p>
                    <p class="font-semibold">MySQL</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection