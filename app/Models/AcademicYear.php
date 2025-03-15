<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tahun_ajaran',
        'status'
    ];

    public function classRooms()
    {
        return $this->hasMany(ClassRoom::class);
    }
}
