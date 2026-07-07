<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('role_name', 'admin')->first();
        $moRole = Role::where('role_name', 'mo')->first();
        $dosenRole = Role::where('role_name', 'dosen')->first();
        $mahasiswaRole = Role::where('role_name', 'mahasiswa')->first();

        User::create([
            'name' => 'Admin',
            'identitas' => 'ADM001',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'is_active' => 1
        ]);

        User::create([
            'name' => 'MO Staff',
            'identitas' => 'MO001',
            'email' => 'mo@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $moRole->id,
            'is_active' => 1
        ]);

        User::create([
            'name' => 'Dosen User',
            'identitas' => 'DSN001',
            'email' => 'dosen@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $dosenRole->id,
            'is_active' => 1
        ]);

        User::create([
            'name' => 'Mahasiswa User',
            'identitas' => 'MHS001',
            'email' => 'mahasiswa@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $mahasiswaRole->id,
            'is_active' => 1
        ]);

        User::create([
            'name' => 'Mahasiswa Nonaktif',
            'identitas' => 'MHS002',
            'email' => 'nonaktif@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $mahasiswaRole->id,
            'is_active' => 0
        ]);
    }
}