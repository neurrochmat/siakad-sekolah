<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'guru@guru.com')->first();
        $school = School::first();

        $teachers = [
            [
                'nip' => '198001012010011001',
                'nama_lengkap' => 'Guru RPL',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '1980-01-01',
                'alamat' => 'Jl. Guru No. 1',
                'telepon' => '081234567890',
                'user_id' => $user->id,
                'school_id' => $school->id,
                'is_active' => true
            ],
        ];

        foreach ($teachers as $teacher) {
            Teacher::create($teacher);
        }
    }
}
