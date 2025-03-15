<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleHasMenusSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // Hapus data yang ada
            DB::statement('DELETE FROM role_has_menus');

            // Insert data untuk admin (role_id = 1)
            DB::statement('
                INSERT INTO role_has_menus (role_id, menu_id)
                SELECT 1, id FROM menus
            ');

            // Insert data untuk guru (role_id = 2)
            DB::statement("
                INSERT INTO role_has_menus (role_id, menu_id)
                SELECT 2, id FROM menus WHERE route IN ('home', 'attendances.index')
            ");

            // Insert data untuk siswa (role_id = 3)
            DB::statement("
                INSERT INTO role_has_menus (role_id, menu_id)
                SELECT 3, id FROM menus WHERE route = 'home'
            ");
        } finally {
            // Enable kembali foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
