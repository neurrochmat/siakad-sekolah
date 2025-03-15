<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        School::create([
            'nama_sekolah' => 'Politeknik Negeri Semarang',
            'npsn' => '20363827',
            'alamat' => 'Jl. Prof. H. Soedarto, S.H., Tembalang, Semarang',
            'telepon' => '(024) 7473417',
            'email' => 'sekretariat@polines.ac.id',
            'website' => 'https://www.polines.ac.id',
            'is_active' => true
        ]);
    }
}
