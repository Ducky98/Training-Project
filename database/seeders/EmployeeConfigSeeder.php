<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmployeeConfig;

class EmployeeConfigSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $configs = [
      [
        'type' => 'document',
        'value' => json_encode([
          'aadhaar front',
          'aadhaar back',
          '10th'
        ]),
      ]
    ];

    foreach ($configs as $config) {
      EmployeeConfig::updateOrCreate(['type' => $config['type']], $config);
    }
  }
}
