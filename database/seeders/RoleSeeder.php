<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_name' => 'admin'],
            ['role_name' => 'mo'],
            ['role_name' => 'dosen'],
            ['role_name' => 'mahasiswa'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}