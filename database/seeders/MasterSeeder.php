<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menu Utama - Manajemen Pengguna
        $menuManajemen = Menu::create([
            'nama_menu' => 'Manajemen Pengguna',
            'route' => '#',
            'icon' => 'fas fa-users-cog',
            'parent_id' => null,
            'order' => 1
        ]);

        // Submenu Users
        $userMenu = Menu::create([
            'nama_menu' => 'Users',
            'route' => 'users.index',
            'parent_id' => $menuManajemen->id,
            'order' => 1
        ]);

        // Submenu Roles
        $roleMenu = Menu::create([
            'nama_menu' => 'Roles',
            'route' => 'roles.index',
            'parent_id' => $menuManajemen->id,
            'order' => 2
        ]);

        // Menu Utama - Data Master
        $menuMaster = Menu::create([
            'nama_menu' => 'Data Master',
            'route' => '#',
            'icon' => 'fas fa-database',
            'parent_id' => null,
            'order' => 2
        ]);

        // Submenu Data Guru
        $guruMenu = Menu::create([
            'nama_menu' => 'Data Guru',
            'route' => 'teachers.index',
            'parent_id' => $menuMaster->id,
            'order' => 1
        ]);

        // Submenu Data Siswa
        $siswaMenu = Menu::create([
            'nama_menu' => 'Data Siswa',
            'route' => 'students.index',
            'parent_id' => $menuMaster->id,
            'order' => 2
        ]);

        // Submenu Data Kelas
        $kelasMenu = Menu::create([
            'nama_menu' => 'Data Kelas',
            'route' => 'classes.index',
            'parent_id' => $menuMaster->id,
            'order' => 3
        ]);

        // Submenu Data Mata Pelajaran
        $mapelMenu = Menu::create([
            'nama_menu' => 'Data Mata Pelajaran',
            'route' => 'subjects.index',
            'parent_id' => $menuMaster->id,
            'order' => 4
        ]);

        // Buat role admin, guru, dan siswa
        $adminRole = Role::create(['name' => 'admin']);
        $guruRole = Role::create(['name' => 'guru']);
        $siswaRole = Role::create(['name' => 'siswa']);

        // Buat user admin default
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password')
        ]);
        $admin->assignRole('admin');
    }
}
