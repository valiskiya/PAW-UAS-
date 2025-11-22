<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'direktur',
                'display_name' => 'Direktur',
                'description' => 'Monitoring, laporan, KPI, performa toko'
            ],
            [
                'name' => 'manajer_unit',
                'display_name' => 'Manajer Unit',
                'description' => 'Operasional penuh (stok, transaksi, SDM, gaji, laporan)'
            ],
            [
                'name' => 'kasir',
                'display_name' => 'Kasir / Shop Keeper',
                'description' => 'Transaksi, layanan pelanggan, pengecekan anggota'
            ],
            [
                'name' => 'logistik',
                'display_name' => 'Logistik',
                'description' => 'Barang masuk, kartu stok, konversi unit, stok opname'
            ],
            [
                'name' => 'admin_ti',
                'display_name' => 'Admin TI Toko',
                'description' => 'User management, konfigurasi sistem, backup data'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}