<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EducationLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EducationLevel::query();

        // Filter berdasarkan sekolah
        if ($request->has('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        // Filter berdasarkan status aktif
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_tingkat', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        $educationLevels = $query->with('school')->paginate($request->per_page ?? 10);

        return response()->json($educationLevels);
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
            'nama_tingkat' => 'required|string|max:255',
            'kode' => 'required|string|unique:education_levels',
            'deskripsi' => 'nullable|string',
            'school_id' => 'required|exists:schools,id',
            'is_active' => 'boolean'
        ]);

        $educationLevel = EducationLevel::create($request->all());

        return response()->json([
            'message' => 'Tingkat pendidikan berhasil ditambahkan',
            'data' => $educationLevel->load('school')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(EducationLevel $educationLevel)
    {
        $educationLevel->load(['school', 'classes', 'subjects']);

        return response()->json($educationLevel);
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
    public function update(Request $request, EducationLevel $educationLevel)
    {
        $request->validate([
            'nama_tingkat' => 'required|string|max:255',
            'kode' => 'required|string|unique:education_levels,kode,' . $educationLevel->id,
            'deskripsi' => 'nullable|string',
            'school_id' => 'required|exists:schools,id',
            'is_active' => 'boolean'
        ]);

        $educationLevel->update($request->all());

        return response()->json([
            'message' => 'Tingkat pendidikan berhasil diperbarui',
            'data' => $educationLevel->load('school')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EducationLevel $educationLevel)
    {
        // Cek apakah tingkat pendidikan masih memiliki kelas
        if ($educationLevel->classes()->count() > 0) {
            throw ValidationException::withMessages([
                'education_level' => ['Tidak dapat menghapus tingkat pendidikan yang masih memiliki kelas.']
            ]);
        }

        // Cek apakah tingkat pendidikan masih memiliki mata pelajaran
        if ($educationLevel->subjects()->count() > 0) {
            throw ValidationException::withMessages([
                'education_level' => ['Tidak dapat menghapus tingkat pendidikan yang masih memiliki mata pelajaran.']
            ]);
        }

        $educationLevel->delete();

        return response()->json([
            'message' => 'Tingkat pendidikan berhasil dihapus'
        ]);
    }
}
