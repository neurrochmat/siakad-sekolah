<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $students = [
            [
                'nis' => '2024001',
                'nisn' => '1234567890',
                'nama_lengkap' => 'Siswa RPL 1',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '2008-01-01',
                'alamat' => 'Jl. Siswa No. 1',
                'nama_ayah' => 'Ayah Siswa 1',
                'nama_ibu' => 'Ibu Siswa 1',
                'telepon_ortu' => '081234567891',
                'class_id' => 1,
                'school_id' => 1,
                'is_active' => true
            ],
            [
                'nis' => '2024002',
                'nisn' => '1234567891',
                'nama_lengkap' => 'Siswa RPL 2',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '2008-02-02',
                'alamat' => 'Jl. Siswa No. 2',
                'nama_ayah' => 'Ayah Siswa 2',
                'nama_ibu' => 'Ibu Siswa 2',
                'telepon_ortu' => '081234567892',
                'class_id' => 2,
                'school_id' => 1,
                'is_active' => true
            ],
        ];

        foreach ($students as $student) {
            // Generate username dari NIS
            $username = 'siswa_' . $student['nis'];

            // Buat user untuk siswa
            $user = User::create([
                'name' => $student['nama_lengkap'],
                'email' => strtolower(str_replace(' ', '', $student['nama_lengkap'])) . '@siswa.com',
                'username' => $username,
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'school_id' => $student['school_id']
            ]);

            // Tambahkan user_id ke data siswa
            $student['user_id'] = $user->id;

            // Buat data siswa
            Student::create($student);
        }
    }
}
