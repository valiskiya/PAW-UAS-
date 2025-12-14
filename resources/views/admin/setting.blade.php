@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Pengaturan Sistem</h1>
            <p class="text-sm text-gray-500">
                Atur informasi toko, kontak, dan parameter umum yang digunakan di seluruh aplikasi.
            </p>
        </div>
        <p class="text-gray-600 text-sm">
            {{ now()->isoFormat('dddd, D MMMM Y') }}
        </p>
    </div>

    <div class="space-y-6">
        <!-- General Settings -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Pengaturan Umum Toko</h2>
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nama Toko</label>
                        <input type="text" name="store_name" value="Ritel ABC"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Telepon Toko</label>
                        <input type="text" name="store_phone" value="021-12345678"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Alamat Toko</label>
                    <textarea name="store_address" rows="2"
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">Jl. Raya No. 123, Jakarta</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email Toko</label>
                        <input type="email" name="store_email" value="info@ritelabc.com"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Zona Waktu</label>
                        <select name="timezone"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Asia/Jakarta">Asia/Jakarta (WIB)</option>
                            <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
                            <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow">
                        <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        <!-- Shift Settings -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Pengaturan Shift Kerja</h2>
            <p class="text-sm text-gray-500 mb-3">
                Pengaturan ini digunakan sebagai referensi jadwal kerja & perhitungan absensi karyawan.
            </p>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-semibold text-gray-800">Shift Pagi</p>
                        <p class="text-sm text-gray-600">05:00 - 12:00</p>
                    </div>
                    <button type="button" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-semibold text-gray-800">Shift Sore</p>
                        <p class="text-sm text-gray-600">13:00 - 21:00</p>
                    </div>
                    <button type="button" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-semibold text-gray-800">Shift Malam</p>
                        <p class="text-sm text-gray-600">21:00 - 04:00</p>
                    </div>
                    <button type="button" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Informasi Sistem</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-600">Versi Aplikasi</p>
                    <p class="font-semibold text-gray-900">1.0.0</p>
                </div>
                <div>
                    <p class="text-gray-600">Laravel Version</p>
                    <p class="font-semibold text-gray-900">{{ app()->version() }}</p>
                </div>
                <div>
                    <p class="text-gray-600">PHP Version</p>
                    <p class="font-semibold text-gray-900">{{ PHP_VERSION }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Database</p>
                    <p class="font-semibold text-gray-900">PostgreSQL</p>
                </div>
            </div>
            <p class="mt-3 text-xs text-gray-500">
                Informasi ini membantu Admin TI saat melakukan troubleshooting atau berkoordinasi dengan tim IT pusat.
            </p>
        </div>
    </div>
</div>
@endsection
