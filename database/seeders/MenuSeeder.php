<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Menu Utama
        $dashboard = Menu::create([
            'nama_menu' => 'Dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'route' => 'home',
            'order' => 1,
            'parent_id' => null
        ]);

        $manajemenUser = Menu::create([
            'nama_menu' => 'Manajemen User',
            'icon' => 'fas fa-users',
            'route' => '#',
            'order' => 2,
            'parent_id' => null
        ]);

        $dataMaster = Menu::create([
            'nama_menu' => 'Data Master',
            'icon' => 'fas fa-database',
            'route' => '#',
            'order' => 3,
            'parent_id' => null
        ]);

        // Submenu Manajemen User
        Menu::create([
            'nama_menu' => 'Users',
            'icon' => 'fas fa-user',
            'route' => 'users.index',
            'order' => 1,
            'parent_id' => $manajemenUser->id
        ]);

        Menu::create([
            'nama_menu' => 'Roles',
            'icon' => 'fas fa-user-tag',
            'route' => 'roles.index',
            'order' => 2,
            'parent_id' => $manajemenUser->id
        ]);

        // Submenu Data Master
        Menu::create([
            'nama_menu' => 'Data Sekolah',
            'icon' => 'fas fa-school',
            'route' => 'schools.index',
            'order' => 1,
            'parent_id' => $dataMaster->id
        ]);

        Menu::create([
            'nama_menu' => 'Data Guru',
            'icon' => 'fas fa-chalkboard-teacher',
            'route' => 'teachers.index',
            'order' => 2,
            'parent_id' => $dataMaster->id
        ]);

        Menu::create([
            'nama_menu' => 'Data Siswa',
            'icon' => 'fas fa-user-graduate',
            'route' => 'students.index',
            'order' => 3,
            'parent_id' => $dataMaster->id
        ]);

        Menu::create([
            'nama_menu' => 'Data Kelas',
            'icon' => 'fas fa-door-open',
            'route' => 'classes.index',
            'order' => 4,
            'parent_id' => $dataMaster->id
        ]);

        Menu::create([
            'nama_menu' => 'Data Mata Pelajaran',
            'icon' => 'fas fa-book',
            'route' => 'subjects.index',
            'order' => 5,
            'parent_id' => $dataMaster->id
        ]);
    }
}
