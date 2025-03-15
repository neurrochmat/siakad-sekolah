<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_mapel',
        'kode',
        'education_level_id',
        'deskripsi',
        'status'
    ];

    protected $casts = [
        'kkm' => 'integer',
        'jam_pelajaran' => 'integer',
        'is_active' => 'boolean'
    ];

    // Relasi dengan Education Level
    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    // Relasi dengan School
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Relasi dengan Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
