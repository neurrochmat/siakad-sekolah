<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nis',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'nama_ayah',
        'nama_ibu',
        'telepon_ortu',
        'foto',
        'class_id',
        'school_id',
        'user_id',
        'is_active'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_active' => 'boolean'
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan School
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Relasi dengan Class
    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    // Relasi dengan Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
