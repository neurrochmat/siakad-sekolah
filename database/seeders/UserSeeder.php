<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin Super
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin_super'
        ]);
        $admin->assignRole('admin');

        // Guru
        $guru = User::create([
            'name' => 'Guru',
            'email' => 'guru@guru.com',
            'username' => 'guru',
            'password' => Hash::make('password'),
            'role' => 'guru'
        ]);
        $guru->assignRole('guru');

        // Siswa
        $siswa = User::create([
            'name' => 'Siswa',
            'email' => 'siswa@siswa.com',
            'username' => 'siswa',
            'password' => Hash::make('password'),
            'role' => 'siswa'
        ]);
        $siswa->assignRole('siswa');
    }
}
