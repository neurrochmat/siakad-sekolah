<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Delete existing model_has_permissions and permissions
        DB::table('model_has_permissions')->truncate();
        Permission::truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Get menu IDs
        $userMenu = Menu::where('nama_menu', 'Users')->first();
        $roleMenu = Menu::where('nama_menu', 'Roles')->first();
        $schoolMenu = Menu::where('nama_menu', 'Data Sekolah')->first();
        $guruMenu = Menu::where('nama_menu', 'Data Guru')->first();
        $siswaMenu = Menu::where('nama_menu', 'Data Siswa')->first();
        $kelasMenu = Menu::where('nama_menu', 'Data Kelas')->first();
        $mapelMenu = Menu::where('nama_menu', 'Data Mata Pelajaran')->first();

        // Create permissions for Users
        if ($userMenu) {
            Permission::create([
                'name' => 'read_user',
                'menu_id' => $userMenu->id,
                'detail' => 'Melihat daftar pengguna'
            ]);
            Permission::create([
                'name' => 'create_user',
                'menu_id' => $userMenu->id,
                'detail' => 'Menambah pengguna baru'
            ]);
            Permission::create([
                'name' => 'update_user',
                'menu_id' => $userMenu->id,
                'detail' => 'Mengubah data pengguna'
            ]);
            Permission::create([
                'name' => 'delete_user',
                'menu_id' => $userMenu->id,
                'detail' => 'Menghapus pengguna'
            ]);
        }

        // Create permissions for Roles
        if ($roleMenu) {
            Permission::create([
                'name' => 'read_role',
                'menu_id' => $roleMenu->id,
                'detail' => 'Melihat daftar role'
            ]);
            Permission::create([
                'name' => 'create_role',
                'menu_id' => $roleMenu->id,
                'detail' => 'Menambah role baru'
            ]);
            Permission::create([
                'name' => 'update_role',
                'menu_id' => $roleMenu->id,
                'detail' => 'Mengubah role'
            ]);
            Permission::create([
                'name' => 'delete_role',
                'menu_id' => $roleMenu->id,
                'detail' => 'Menghapus role'
            ]);
        }

        // Create permissions for Schools
        if ($schoolMenu) {
            Permission::create([
                'name' => 'read_school',
                'menu_id' => $schoolMenu->id,
                'detail' => 'Melihat data sekolah'
            ]);
            Permission::create([
                'name' => 'create_school',
                'menu_id' => $schoolMenu->id,
                'detail' => 'Menambah data sekolah'
            ]);
            Permission::create([
                'name' => 'update_school',
                'menu_id' => $schoolMenu->id,
                'detail' => 'Mengubah data sekolah'
            ]);
            Permission::create([
                'name' => 'delete_school',
                'menu_id' => $schoolMenu->id,
                'detail' => 'Menghapus data sekolah'
            ]);
        }

        // Create permissions for Teachers
        if ($guruMenu) {
            Permission::create([
                'name' => 'read_teacher',
                'menu_id' => $guruMenu->id,
                'detail' => 'Melihat data guru'
            ]);
            Permission::create([
                'name' => 'create_teacher',
                'menu_id' => $guruMenu->id,
                'detail' => 'Menambah data guru'
            ]);
            Permission::create([
                'name' => 'update_teacher',
                'menu_id' => $guruMenu->id,
                'detail' => 'Mengubah data guru'
            ]);
            Permission::create([
                'name' => 'delete_teacher',
                'menu_id' => $guruMenu->id,
                'detail' => 'Menghapus data guru'
            ]);
        }

        // Create permissions for Students
        if ($siswaMenu) {
            Permission::create([
                'name' => 'read_student',
                'menu_id' => $siswaMenu->id,
                'detail' => 'Melihat data siswa'
            ]);
            Permission::create([
                'name' => 'create_student',
                'menu_id' => $siswaMenu->id,
                'detail' => 'Menambah data siswa'
            ]);
            Permission::create([
                'name' => 'update_student',
                'menu_id' => $siswaMenu->id,
                'detail' => 'Mengubah data siswa'
            ]);
            Permission::create([
                'name' => 'delete_student',
                'menu_id' => $siswaMenu->id,
                'detail' => 'Menghapus data siswa'
            ]);
        }

        // Create permissions for Classes
        if ($kelasMenu) {
            Permission::create([
                'name' => 'read_class',
                'menu_id' => $kelasMenu->id,
                'detail' => 'Melihat data kelas'
            ]);
            Permission::create([
                'name' => 'create_class',
                'menu_id' => $kelasMenu->id,
                'detail' => 'Menambah data kelas'
            ]);
            Permission::create([
                'name' => 'update_class',
                'menu_id' => $kelasMenu->id,
                'detail' => 'Mengubah data kelas'
            ]);
            Permission::create([
                'name' => 'delete_class',
                'menu_id' => $kelasMenu->id,
                'detail' => 'Menghapus data kelas'
            ]);
        }

        // Create permissions for Subjects
        if ($mapelMenu) {
            Permission::create([
                'name' => 'read_subject',
                'menu_id' => $mapelMenu->id,
                'detail' => 'Melihat data mata pelajaran'
            ]);
            Permission::create([
                'name' => 'create_subject',
                'menu_id' => $mapelMenu->id,
                'detail' => 'Menambah data mata pelajaran'
            ]);
            Permission::create([
                'name' => 'update_subject',
                'menu_id' => $mapelMenu->id,
                'detail' => 'Mengubah data mata pelajaran'
            ]);
            Permission::create([
                'name' => 'delete_subject',
                'menu_id' => $mapelMenu->id,
                'detail' => 'Menghapus data mata pelajaran'
            ]);
        }

        // Assign all permissions to admin role
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo(Permission::all());

        // Assign specific permissions to guru role
        $guruRole = Role::findByName('guru');
        $guruRole->givePermissionTo([
            'read_teacher',
            'read_student',
            'read_class',
            'read_subject',
            'read_school'
        ]);

        // Assign specific permissions to siswa role
        $siswaRole = Role::findByName('siswa');
        $siswaRole->givePermissionTo([
            'read_student',
            'read_class',
            'read_subject',
            'read_school'
        ]);
    }
}
