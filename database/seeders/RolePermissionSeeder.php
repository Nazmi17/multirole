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
        // 1. Buat Permission (Hak Akses Spesifik)
        // Nanti ini bisa ditambah via GUI Admin
        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'edit profile']);

        // 2. Buat Role & Assign Permission
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all()); // Admin bisa semuanya

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo(['edit profile']); // User cuma bisa edit profil

        // 3. Migrasi User Lama (Sync kolom 'role' ke Spatie)
        $users = User::all();
        foreach ($users as $user) {
            // Assign role spatie berdasarkan kolom role lama
            // Pastikan stringnya sama ('admin' atau 'user')
            $user->assignRole($user->role); 
        }
    }
}