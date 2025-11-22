<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            [
                'name' => 'Pagi',
                'start_time' => '07:00:00',
                'end_time' => '15:00:00',
                'description' => 'Shift pagi (07:00 - 15:00)',
            ],
            [
                'name' => 'Sore',
                'start_time' => '15:00:00',
                'end_time' => '23:00:00',
                'description' => 'Shift sore (15:00 - 23:00)',
            ],
            [
                'name' => 'Malam',
                'start_time' => '23:00:00',
                'end_time' => '07:00:00',
                'description' => 'Shift malam (23:00 - 07:00)',
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}