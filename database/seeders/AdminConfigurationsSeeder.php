<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminConfigurationsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $companies = [
      [
        'name' => 'RRR Health At Home',
        'address' => '123, MG Road, Sector 44, Near Metro Station',
        'location' => 'New York, USA',
        'bank_name' => 'Bank of America',
        'account_number' => '1234567890',
        'ifsc_code' => 'BOFA0123456',
        'gst_number' => '22AAAAA0000A1Z5',
        'email' => 'info@acme.com',
        'phone' => '+1 234 567 8901',
      ],
      [
        'name' => 'ABC Home Care',
        'address' => '456, Park Avenue',
        'location' => 'Los Angeles, USA',
        'bank_name' => 'Chase Bank',
        'account_number' => '9876543210',
        'ifsc_code' => 'CHAS0987654',
        'gst_number' => '33BBBBB1111B2Z6',
        'email' => 'contact@abc.com',
        'phone' => '+1 987 654 3210',
      ],
    ];

    DB::table('admin_configurations')->insert([
      'key' => 'companies',
      'value' => json_encode($companies),
    ]);
  }
}
