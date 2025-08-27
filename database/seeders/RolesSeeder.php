<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run()
  {
    // Create roles
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'user']);

    // Optional: Assign admin role to a user
    $admin = User::create([
      'name' => 'Admin User',
      'email' => 'admin@admin.com',
      'password' => bcrypt('secret')
    ]);

    $admin->assignRole('admin');
  }
}
