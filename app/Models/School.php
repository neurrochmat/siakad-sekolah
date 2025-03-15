<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_sekolah',
        'npsn',
        'alamat',
        'telepon',
        'email',
        'website',
        'logo',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relasi dengan Education Level
    public function educationLevels()
    {
        return $this->hasMany(EducationLevel::class);
    }

    // Relasi dengan Academic Year
    public function academicYears()
    {
        return $this->hasMany(AcademicYear::class);
    }

    // Relasi dengan Class
    public function classes()
    {
        return $this->hasMany(ClassRoom::class);
    }

    // Relasi dengan Teacher
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    // Relasi dengan Student
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    // Relasi dengan User
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
