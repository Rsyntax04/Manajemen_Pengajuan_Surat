<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Budi Santoso, S.T., M.Kom.',
            'email' => 'budi.santoso@maranatha.edu',
            'password' => Hash::make('password'),
            'role_id' => '3',
            'identitas' => '720307',
        ]);

        User::create([
            'name' => 'Caca Andika',
            'email' => 'caca@maranatha.edu',
            'password' => Hash::make('password'),
            'role_id' => '4',
            'identitas' => '2272001',
        ]);
        
        User::create([
            'name' => 'Doni Prasetyo',
            'email' => 'doni.prasetyo@maranatha.edu',
            'password' => Hash::make('password'),
            'role_id' => '4',
            'identitas' => '2272002',
        ]);

        User::create([
            'name' => 'Risa, S.Kom., M.T.',
            'email' => 'risa@maranatha.edu',
            'password' => Hash::make('password'),
            'role_id' => '3',
            'identitas' => '720319',
        ]);

        User::create([
            'name' => 'Admin Program Studi',
            'email' => 'admin@maranatha.edu',
            'password' => Hash::make('password'),
            'role_id' => '1',
        ]);

        User::create([
            'name' => 'Manajer Operasional',
            'email' => 'mo@maranatha.edu',
            'password' => Hash::make('password'),
            'role_id' => '2',
        ]);
    }
}