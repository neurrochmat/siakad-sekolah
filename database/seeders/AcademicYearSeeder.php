<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;

        AcademicYear::create([
            'tahun_ajaran' => $currentYear . '/' . $nextYear,
            'status' => 'active'
        ]);
    }
}
