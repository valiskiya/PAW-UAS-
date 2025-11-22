<nav class="space-y-2">
    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : '' }}">
        <i class="fas fa-home w-6"></i>
        <span>Dashboard</span>
    </a>
    
    @if(auth()->user()->isDirektur())
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase">Laporan</div>
        <a href="{{ route('direktur.laporan.laba-rugi') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-chart-line w-6"></i>
            <span>Laba-Rugi Harian</span>
        </a>
        <a href="{{ route('direktur.laporan.bulanan') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-calendar-alt w-6"></i>
            <span>Laporan Bulanan</span>
        </a>
    @endif
    
    @if(auth()->user()->isManajerUnit())
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase">Inventori</div>
        <a href="{{ route('manajer.products.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-box w-6"></i>
            <span>Daftar Produk</span>
        </a>
        <a href="{{ route('manajer.stock-cards.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-clipboard-list w-6"></i>
            <span>Kartu Stok</span>
        </a>
        <a href="{{ route('manajer.purchase-orders.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-truck-loading w-6"></i>
            <span>Purchase Order</span>
        </a>
        
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase">Transaksi</div>
        <a href="{{ route('manajer.transactions.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-cash-register w-6"></i>
            <span>Daftar Transaksi</span>
        </a>
        
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase">Pelanggan</div>
        <a href="{{ route('manajer.customers.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-users w-6"></i>
            <span>Daftar Pelanggan</span>
        </a>
        
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase">Supplier</div>
        <a href="{{ route('manajer.suppliers.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-dolly w-6"></i>
            <span>Daftar Supplier</span>
        </a>
        
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase">Karyawan</div>
        <a href="{{ route('manajer.employees.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-user-tie w-6"></i>
            <span>Daftar Karyawan</span>
        </a>
        <a href="{{ route('manajer.attendances.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-calendar-check w-6"></i>
            <span>Absensi</span>
        </a>
        <a href="{{ route('manajer.salaries.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-money-bill-wave w-6"></i>
            <span>Gaji Karyawan</span>
        </a>
        
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase">Laporan</div>
        <a href="{{ route('manajer.reports.daily') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-file-alt w-6"></i>
            <span>Laporan Harian</span>
        </a>
        <a href="{{ route('manajer.reports.stock') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-warehouse w-6"></i>
            <span>Laporan Stok</span>
        </a>
    @endif
    
    @if(auth()->user()->isKasir())
        <a href="{{ route('kasir.pos') }}" class="flex items-center px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded font-semibold">
            <i class="fas fa-cash-register w-6"></i>
            <span>POS / Kasir</span>
        </a>
        <a href="{{ route('kasir.transactions.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-receipt w-6"></i>
            <span>Riwayat Transaksi</span>
        </a>
    @endif
    
    @if(auth()->user()->isLogistik())
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase">Inventori</div>
        <a href="{{ route('logistik.products.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-box w-6"></i>
            <span>Daftar Produk</span>
        </a>
        <a href="{{ route('logistik.stock-cards.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-clipboard-list w-6"></i>
            <span>Kartu Stok</span>
        </a>
        <a href="{{ route('logistik.conversion') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-exchange-alt w-6"></i>
            <span>Konversi Unit</span>
        </a>
        <a href="{{ route('logistik.stock-opname') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-clipboard-check w-6"></i>
            <span>Stok Opname</span>
        </a>
        <a href="{{ route('logistik.purchase-orders.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-truck-loading w-6"></i>
            <span>Penerimaan Barang</span>
        </a>
    @endif
    
    @if(auth()->user()->isAdminTI())
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase">User Management</div>
        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-users-cog w-6"></i>
            <span>Kelola User</span>
        </a>
        <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-cog w-6"></i>
            <span>Pengaturan Sistem</span>
        </a>
        <a href="{{ route('admin.backup') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-database w-6"></i>
            <span>Backup Data</span>
        </a>
        <a href="{{ route('admin.logs') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            <i class="fas fa-list w-6"></i>
            <span>Log Aktivitas</span>
        </a>
    @endif
</nav>