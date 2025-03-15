<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    public function run()
    {
        $levels = [
            ['nama_jenjang' => 'SD', 'kode' => 'SD'],
            ['nama_jenjang' => 'SMP', 'kode' => 'SMP'],
            ['nama_jenjang' => 'SMA', 'kode' => 'SMA'],
            ['nama_jenjang' => 'SMK', 'kode' => 'SMK'],
            ['nama_jenjang' => 'D3', 'kode' => 'D3'],
            ['nama_jenjang' => 'D4', 'kode' => 'D4'],
            ['nama_jenjang' => 'S1', 'kode' => 'S1'],
            ['nama_jenjang' => 'S2', 'kode' => 'S2'],
            ['nama_jenjang' => 'S3', 'kode' => 'S3'],
        ];

        foreach ($levels as $level) {
            EducationLevel::create($level);
        }
    }
}
