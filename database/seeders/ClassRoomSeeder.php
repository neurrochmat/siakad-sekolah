<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\School;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    public function run()
    {
        $school = School::first();

        $classes = [
            [
                'nama_kelas' => 'X RPL 1',
                'school_id' => $school->id,
                'education_level_id' => 4, // SMK
                'academic_year_id' => 1,
                'status' => 'active'
            ],
            [
                'nama_kelas' => 'X RPL 2',
                'school_id' => $school->id,
                'education_level_id' => 4,
                'academic_year_id' => 1,
                'status' => 'active'
            ],
            [
                'nama_kelas' => 'XI RPL 1',
                'school_id' => $school->id,
                'education_level_id' => 4,
                'academic_year_id' => 1,
                'status' => 'active'
            ],
            [
                'nama_kelas' => 'XI RPL 2',
                'school_id' => $school->id,
                'education_level_id' => 4,
                'academic_year_id' => 1,
                'status' => 'active'
            ],
            [
                'nama_kelas' => 'XII RPL 1',
                'school_id' => $school->id,
                'education_level_id' => 4,
                'academic_year_id' => 1,
                'status' => 'active'
            ],
            [
                'nama_kelas' => 'XII RPL 2',
                'school_id' => $school->id,
                'education_level_id' => 4,
                'academic_year_id' => 1,
                'status' => 'active'
            ],
        ];

        foreach ($classes as $class) {
            ClassRoom::create($class);
        }
    }
}
