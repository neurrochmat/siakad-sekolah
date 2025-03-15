<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ClassRoom::query();

        // Filter berdasarkan sekolah
        if ($request->has('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        // Filter berdasarkan tingkat pendidikan
        if ($request->has('education_level_id')) {
            $query->where('education_level_id', $request->education_level_id);
        }

        // Filter berdasarkan tahun akademik
        if ($request->has('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        // Filter berdasarkan wali kelas
        if ($request->has('wali_kelas_id')) {
            $query->where('wali_kelas_id', $request->wali_kelas_id);
        }

        // Filter berdasarkan status aktif
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_kelas', 'like', "%{$search}%")
                  ->orWhere('kode_kelas', 'like', "%{$search}%");
            });
        }

        $classes = $query->with([
            'school',
            'educationLevel',
            'academicYear',
            'waliKelas',
            'students'
        ])->paginate($request->per_page ?? 10);

        return response()->json($classes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'kode_kelas' => 'required|string|unique:classes',
            'kapasitas' => 'required|integer|min:1',
            'education_level_id' => 'required|exists:education_levels,id',
            'school_id' => 'required|exists:schools,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean'
        ]);

        // Validasi wali kelas harus guru
        if ($request->wali_kelas_id) {
            $user = \App\Models\User::find($request->wali_kelas_id);
            if (!$user || !$user->isGuru()) {
                throw ValidationException::withMessages([
                    'wali_kelas_id' => ['Wali kelas harus seorang guru.']
                ]);
            }
        }

        $class = ClassRoom::create($request->all());

        return response()->json([
            'message' => 'Kelas berhasil ditambahkan',
            'data' => $class->load(['school', 'educationLevel', 'academicYear', 'waliKelas'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassRoom $class)
    {
        $class->load([
            'school',
            'educationLevel',
            'academicYear',
            'waliKelas',
            'students',
            'attendances'
        ]);

        return response()->json($class);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassRoom $class)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'kode_kelas' => 'required|string|unique:classes,kode_kelas,' . $class->id,
            'kapasitas' => 'required|integer|min:1',
            'education_level_id' => 'required|exists:education_levels,id',
            'school_id' => 'required|exists:schools,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean'
        ]);

        // Validasi wali kelas harus guru
        if ($request->wali_kelas_id) {
            $user = \App\Models\User::find($request->wali_kelas_id);
            if (!$user || !$user->isGuru()) {
                throw ValidationException::withMessages([
                    'wali_kelas_id' => ['Wali kelas harus seorang guru.']
                ]);
            }
        }

        // Validasi kapasitas tidak boleh kurang dari jumlah siswa yang ada
        if ($request->kapasitas < $class->students()->count()) {
            throw ValidationException::withMessages([
                'kapasitas' => ['Kapasitas tidak boleh kurang dari jumlah siswa yang ada.']
            ]);
        }

        $class->update($request->all());

        return response()->json([
            'message' => 'Kelas berhasil diperbarui',
            'data' => $class->load(['school', 'educationLevel', 'academicYear', 'waliKelas'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassRoom $class)
    {
        // Cek apakah kelas masih memiliki siswa
        if ($class->students()->count() > 0) {
            throw ValidationException::withMessages([
                'class' => ['Tidak dapat menghapus kelas yang masih memiliki siswa.']
            ]);
        }

        // Cek apakah kelas masih memiliki data presensi
        if ($class->attendances()->count() > 0) {
            throw ValidationException::withMessages([
                'class' => ['Tidak dapat menghapus kelas yang masih memiliki data presensi.']
            ]);
        }

        $class->delete();

        return response()->json([
            'message' => 'Kelas berhasil dihapus'
        ]);
    }

    /**
     * Get classes by school
     */
    public function getBySchool($schoolId)
    {
        $classes = \App\Models\ClassRoom::where('school_id', $schoolId)
            ->where('is_active', true)
            ->get();

        return response()->json($classes);
    }
}
