<nav class="space-y-1">
    @php
        $user = auth()->user();
    @endphp

    {{-- DASHBOARD UMUM --}}
    <a href="{{ route('dashboard') }}" 
       class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
        <i class="fas fa-home w-6"></i>
        <span>Dashboard</span>
    </a>
    
    {{-- DIREKTUR --}}
    @if($user && $user->isDirektur())
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase px-4">Laporan</div>
        
        <a href="{{ route('direktur.laporan.laba-rugi') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-chart-line w-6"></i>
            <span>Laba-Rugi Harian</span>
        </a>
        
        <a href="{{ route('direktur.laporan.bulanan') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-calendar-alt w-6"></i>
            <span>Laporan Bulanan</span>
        </a>
    @endif
    
    {{-- MANAJER UNIT --}}
    @if($user && $user->isManajerUnit())
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase px-4">Inventori</div>
        
        <a href="{{ route('manajer.products.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-box w-6"></i>
            <span>Daftar Produk</span>
        </a>
        
        <a href="{{ route('manajer.stock-cards.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-clipboard-list w-6"></i>
            <span>Kartu Stok</span>
        </a>
        
        <a href="{{ route('manajer.purchase-orders.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-truck-loading w-6"></i>
            <span>Purchase Order</span>
        </a>
        
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase px-4">Transaksi</div>
        
        <a href="{{ route('manajer.transactions.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-cash-register w-6"></i>
            <span>Daftar Transaksi</span>
        </a>
        
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase px-4">Pelanggan</div>
        
        <a href="{{ route('manajer.customers.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-users w-6"></i>
            <span>Daftar Pelanggan</span>
        </a>
        
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase px-4">Supplier</div>
        
        <a href="{{ route('manajer.suppliers.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-dolly w-6"></i>
            <span>Daftar Supplier</span>
        </a>
        
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase px-4">Karyawan</div>
        
        <a href="{{ route('manajer.employees.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-user-tie w-6"></i>
            <span>Daftar Karyawan</span>
        </a>
        
        <a href="{{ route('manajer.attendances.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-calendar-check w-6"></i>
            <span>Absensi</span>
        </a>
        
        <a href="{{ route('manajer.salaries.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-money-bill-wave w-6"></i>
            <span>Gaji Karyawan</span>
        </a>
        
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase px-4">Laporan</div>
        
        <a href="{{ route('manajer.reports.daily') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-file-alt w-6"></i>
            <span>Laporan Harian</span>
        </a>
        
        <a href="{{ route('manajer.reports.stock') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-warehouse w-6"></i>
            <span>Laporan Stok</span>
        </a>
    @endif
    
    {{-- KASIR --}}
    @if($user && $user->isKasir())
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase px-4">Kasir</div>

        {{-- Tombol POS utama --}}
        <a href="{{ route('kasir.pos') }}" 
           class="flex items-center px-4 py-3 text-white bg-green-600 hover:bg-green-700 rounded-lg font-semibold clickable btn-hover shadow-lg {{ request()->routeIs('kasir.pos') ? 'ring-2 ring-offset-2 ring-green-400' : '' }}">
            <i class="fas fa-cash-register w-6"></i>
            <span>POS / Kasir</span>
        </a>
        
        {{-- Riwayat transaksi kasir --}}
        <a href="{{ route('kasir.transactions.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover {{ request()->routeIs('kasir.transactions.*') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
            <i class="fas fa-receipt w-6"></i>
            <span>Riwayat Transaksi</span>
        </a>
    @endif
    
    {{-- LOGISTIK --}}
    @if($user && $user->isLogistik())
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase px-4">Inventori</div>
        
        <a href="{{ route('logistik.products.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-box w-6"></i>
            <span>Daftar Produk</span>
        </a>
        
        <a href="{{ route('logistik.stock-cards.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-clipboard-list w-6"></i>
            <span>Kartu Stok</span>
        </a>
        
        <a href="{{ route('logistik.conversion') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-exchange-alt w-6"></i>
            <span>Konversi Unit</span>
        </a>
        
        <a href="{{ route('logistik.stock-opname') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-clipboard-check w-6"></i>
            <span>Stok Opname</span>
        </a>
        
        <a href="{{ route('logistik.purchase-orders.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-truck-loading w-6"></i>
            <span>Penerimaan Barang</span>
        </a>
    @endif
    
    {{-- ADMIN TI --}}
    @if($user && $user->isAdminTI())
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase px-4">User Management</div>
        
        <a href="{{ route('admin.users.index') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-users-cog w-6"></i>
            <span>Kelola User</span>
        </a>
        
        <a href="{{ route('admin.settings') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-cog w-6"></i>
            <span>Pengaturan Sistem</span>
        </a>
        
        <a href="{{ route('admin.backup') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-database w-6"></i>
            <span>Backup Data</span>
        </a>
        
        <a href="{{ route('admin.logs') }}" 
           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg clickable btn-hover">
            <i class="fas fa-list w-6"></i>
            <span>Log Aktivitas</span>
        </a>
    @endif
</nav>
