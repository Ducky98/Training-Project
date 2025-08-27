<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      Shift::create([
        'name' => 'Half Time',
        'hours' => 6,
        'color' => '#4CAF50', // Green
      ]);

      Shift::create([
        'name' => 'Part Time',
        'hours' => 12,
        'color' => '#2196F3', // Blue
      ]);

      Shift::create([
        'name' => 'Full Time',
        'hours' => 24,
        'color' => '#9C27B0', // Purple
      ]);
    }
}
