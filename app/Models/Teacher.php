<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nip',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'telepon',
        'foto',
        'user_id',
        'school_id',
        'is_active'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classesAsWaliKelas()
    {
        return $this->hasMany(ClassRoom::class, 'wali_kelas_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
