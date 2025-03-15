<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            [
                'nama_mapel' => 'Pemrograman Web',
                'kode' => 'PW',
                'education_level_id' => 4, // SMK
                'deskripsi' => 'Mata pelajaran pemrograman web dasar',
                'status' => 'active'
            ],
            [
                'nama_mapel' => 'Pemrograman Mobile',
                'kode' => 'PM',
                'education_level_id' => 4,
                'deskripsi' => 'Mata pelajaran pemrograman mobile',
                'status' => 'active'
            ],
            [
                'nama_mapel' => 'Pemrograman Desktop',
                'kode' => 'PD',
                'education_level_id' => 4,
                'deskripsi' => 'Mata pelajaran pemrograman desktop',
                'status' => 'active'
            ],
            [
                'nama_mapel' => 'Basis Data',
                'kode' => 'BD',
                'education_level_id' => 4,
                'deskripsi' => 'Mata pelajaran basis data',
                'status' => 'active'
            ],
            [
                'nama_mapel' => 'Jaringan Komputer',
                'kode' => 'JK',
                'education_level_id' => 4,
                'deskripsi' => 'Mata pelajaran jaringan komputer',
                'status' => 'active'
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
