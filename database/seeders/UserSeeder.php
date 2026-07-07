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

        User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin',
                'identitas' => 'ADM001',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'is_active' => 1,
            ]
        );

        User::firstOrCreate(
            ['email' => 'mo@mail.com'],
            [
                'name' => 'MO Staff',
                'identitas' => 'MO001',
                'password' => Hash::make('password'),
                'role_id' => $moRole->id,
                'is_active' => 1,
            ]
        );

        User::firstOrCreate(
            ['email' => 'dosen@mail.com'],
            [
                'name' => 'Dosen User',
                'identitas' => 'DSN001',
                'password' => Hash::make('password'),
                'role_id' => $dosenRole->id,
                'is_active' => 1,
            ]
        );

        User::firstOrCreate(
            ['email' => 'mahasiswa@mail.com'],
            [
                'name' => 'Mahasiswa User',
                'identitas' => 'MHS001',
                'password' => Hash::make('password'),
                'role_id' => $mahasiswaRole->id,
                'is_active' => 1,
            ]
        );

        User::firstOrCreate(
            ['email' => 'nonaktif@mail.com'],
            [
                'name' => 'Mahasiswa Nonaktif',
                'identitas' => 'MHS002',
                'password' => Hash::make('password'),
                'role_id' => $mahasiswaRole->id,
                'is_active' => 0,
            ]
        );
    }
}