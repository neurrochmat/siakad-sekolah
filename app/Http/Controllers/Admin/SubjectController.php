<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subject::query();

        // Filter berdasarkan sekolah
        if ($request->has('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        // Filter berdasarkan tingkat pendidikan
        if ($request->has('education_level_id')) {
            $query->where('education_level_id', $request->education_level_id);
        }

        // Filter berdasarkan status aktif
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_mapel', 'like', "%{$search}%")
                  ->orWhere('kode_mapel', 'like', "%{$search}%");
            });
        }

        $subjects = $query->with(['school', 'educationLevel'])->paginate($request->per_page ?? 10);

        return response()->json($subjects);
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
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|unique:subjects',
            'deskripsi' => 'nullable|string',
            'kkm' => 'required|integer|min:0|max:100',
            'jam_pelajaran' => 'required|integer|min:1',
            'education_level_id' => 'required|exists:education_levels,id',
            'school_id' => 'required|exists:schools,id',
            'is_active' => 'boolean'
        ]);

        $subject = Subject::create($request->all());

        return response()->json([
            'message' => 'Mata pelajaran berhasil ditambahkan',
            'data' => $subject->load(['school', 'educationLevel'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        $subject->load(['school', 'educationLevel', 'attendances']);

        return response()->json($subject);
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
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|unique:subjects,kode_mapel,' . $subject->id,
            'deskripsi' => 'nullable|string',
            'kkm' => 'required|integer|min:0|max:100',
            'jam_pelajaran' => 'required|integer|min:1',
            'education_level_id' => 'required|exists:education_levels,id',
            'school_id' => 'required|exists:schools,id',
            'is_active' => 'boolean'
        ]);

        $subject->update($request->all());

        return response()->json([
            'message' => 'Mata pelajaran berhasil diperbarui',
            'data' => $subject->load(['school', 'educationLevel'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        // Cek apakah mata pelajaran masih memiliki data presensi
        if ($subject->attendances()->count() > 0) {
            throw ValidationException::withMessages([
                'subject' => ['Tidak dapat menghapus mata pelajaran yang masih memiliki data presensi.']
            ]);
        }

        $subject->delete();

        return response()->json([
            'message' => 'Mata pelajaran berhasil dihapus'
        ]);
    }
}
