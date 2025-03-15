<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'nama_kelas',
        'kode_kelas',
        'kapasitas',
        'education_level_id',
        'school_id',
        'academic_year_id',
        'wali_kelas_id',
        'is_active'
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'is_active' => 'boolean'
    ];

    // Relasi dengan School
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Relasi dengan Education Level
    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    // Relasi dengan Academic Year
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // Relasi dengan Teacher (Wali Kelas)
    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    // Relasi dengan Student
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    // Relasi dengan Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }
}
