<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'role_id' => 1, // Direktur
                'username' => 'direktur',
                'email' => 'direktur@ritelabc.com',
                'password' => Hash::make('password123'),
                'full_name' => 'Budi Santoso',
                'phone' => '081234567801',
                'address' => 'Jl. Merdeka No. 1, Jakarta',
                'status' => 'active',
            ],
            [
                'role_id' => 2, // Manajer Unit
                'username' => 'manajer',
                'email' => 'manajer@ritelabc.com',
                'password' => Hash::make('password123'),
                'full_name' => 'Siti Aminah',
                'phone' => '081234567802',
                'address' => 'Jl. Sudirman No. 10, Jakarta',
                'status' => 'active',
            ],
            [
                'role_id' => 3, // Kasir
                'username' => 'kasir1',
                'email' => 'kasir1@ritelabc.com',
                'password' => Hash::make('password123'),
                'full_name' => 'Andi Wijaya',
                'phone' => '081234567803',
                'address' => 'Jl. Kenari No. 5, Jakarta',
                'status' => 'active',
            ],
            [
                'role_id' => 3, // Kasir 2
                'username' => 'kasir2',
                'email' => 'kasir2@ritelabc.com',
                'password' => Hash::make('password123'),
                'full_name' => 'Dewi Lestari',
                'phone' => '081234567804',
                'address' => 'Jl. Melati No. 8, Jakarta',
                'status' => 'active',
            ],
            [
                'role_id' => 4, // Logistik
                'username' => 'logistik',
                'email' => 'logistik@ritelabc.com',
                'password' => Hash::make('password123'),
                'full_name' => 'Joko Prasetyo',
                'phone' => '081234567805',
                'address' => 'Jl. Mawar No. 12, Jakarta',
                'status' => 'active',
            ],
            [
                'role_id' => 5, // Admin TI
                'username' => 'admin',
                'email' => 'admin@ritelabc.com',
                'password' => Hash::make('password123'),
                'full_name' => 'Admin System',
                'phone' => '081234567806',
                'address' => 'Jl. IT Complex No. 1, Jakarta',
                'status' => 'active',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}