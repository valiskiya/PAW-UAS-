<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'code' => 'CUST001',
                'name' => 'Umum',
                'type' => 'non_member',
                'phone' => null,
                'email' => null,
                'address' => null,
                'member_since' => null,
                'discount_percentage' => 0,
                'free_shipping' => false,
                'status' => 'active',
            ],
            [
                'code' => 'CUST002',
                'name' => 'Ahmad Fauzi',
                'type' => 'member',
                'phone' => '081234567890',
                'email' => 'ahmad@example.com',
                'address' => 'Jl. Kebon Jeruk No. 10',
                'member_since' => now()->subMonths(6),
                'discount_percentage' => 0,
                'free_shipping' => false,
                'status' => 'active',
            ],
            [
                'code' => 'CUST003',
                'name' => 'Toko Sari Rasa',
                'type' => 'wholesale_low',
                'phone' => '081234567891',
                'email' => 'saririsa@example.com',
                'address' => 'Jl. Pasar Baru No. 25',
                'member_since' => now()->subYears(1),
                'discount_percentage' => 5,
                'free_shipping' => false,
                'status' => 'active',
            ],
            [
                'code' => 'CUST004',
                'name' => 'CV. Berkah Makmur',
                'type' => 'wholesale_high',
                'phone' => '081234567892',
                'email' => 'berkahmakmur@example.com',
                'address' => 'Jl. Industri Raya No. 100',
                'member_since' => now()->subYears(2),
                'discount_percentage' => 10,
                'free_shipping' => true,
                'status' => 'active',
            ],
            [
                'code' => 'CUST005',
                'name' => 'Ibu Ratna',
                'type' => 'member',
                'phone' => '081234567893',
                'email' => 'ratna@example.com',
                'address' => 'Jl. Anggrek No. 7',
                'member_since' => now()->subMonths(3),
                'discount_percentage' => 0,
                'free_shipping' => false,
                'status' => 'active',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}