<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'code' => 'SUP001',
                'name' => 'PT. Sumber Makmur',
                'contact_person' => 'Agus Salim',
                'phone' => '021-5551234',
                'email' => 'supplier1@example.com',
                'address' => 'Jl. Industri No. 20, Jakarta',
                'status' => 'active',
            ],
            [
                'code' => 'SUP002',
                'name' => 'CV. Berkah Jaya',
                'contact_person' => 'Rina Susanti',
                'phone' => '021-5555678',
                'email' => 'supplier2@example.com',
                'address' => 'Jl. Perdagangan No. 15, Tangerang',
                'status' => 'active',
            ],
            [
                'code' => 'SUP003',
                'name' => 'UD. Maju Bersama',
                'contact_person' => 'Herman Wijaya',
                'phone' => '021-5559876',
                'email' => 'supplier3@example.com',
                'address' => 'Jl. Niaga No. 8, Bekasi',
                'status' => 'active',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}