<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // <-- WAJIB ADA

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Membersihkan cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Membuat data Role
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'logistik']);
        Role::create(['name' => 'staf_lapangan']);
    }
}