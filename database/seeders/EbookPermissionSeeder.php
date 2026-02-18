<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EbookPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view ebooks',
            'create ebooks',
            'update ebooks',
            'delete ebooks',
        ];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $rolePengelola = Role::firstOrCreate( ['name' => 'Pengelola']);
        $rolePengelola->givePermissionTo($permissions);

        $roleAdmin = Role::firstOrCreate( ['name' => 'admin']);
        $roleAdmin->givePermissionTo($permissions);
    }
}
