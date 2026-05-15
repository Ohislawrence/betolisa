<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $bettorRole = Role::create(['name' => 'bettor']);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@tipster.com',
            'password' => bcrypt('password'),
            'telegram_number' => '@admin_telegram',
            'username' => 'admin',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Create a test bettor
        $bettor = User::create([
            'name' => 'Test Bettor',
            'email' => 'bettor@tipster.com',
            'password' => bcrypt('password'),
            'telegram_number' => '@bettor_telegram',
            'username' => 'testbettor',
            'is_active' => true,
        ]);
        $bettor->assignRole('bettor');
    }
}
