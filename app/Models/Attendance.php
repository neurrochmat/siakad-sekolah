<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tanggal',
        'student_id',
        'teacher_id',
        'subject_id',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    // Relasi dengan Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relasi dengan Teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Relasi dengan Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
