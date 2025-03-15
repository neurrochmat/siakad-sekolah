<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AcademicYear::query();

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
            $query->where('tahun_akademik', 'like', "%{$search}%");
        }

        $academicYears = $query->with('school')->paginate($request->per_page ?? 10);

        return response()->json($academicYears);
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
            'tahun_akademik' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'school_id' => 'required|exists:schools,id',
            'is_active' => 'boolean'
        ]);

        // Cek jika sudah ada tahun akademik aktif
        if ($request->is_active) {
            $activeYear = AcademicYear::where('school_id', $request->school_id)
                ->where('is_active', true)
                ->first();

            if ($activeYear) {
                throw ValidationException::withMessages([
                    'is_active' => ['Sudah ada tahun akademik yang aktif.']
                ]);
            }
        }

        $academicYear = AcademicYear::create($request->all());

        return response()->json([
            'message' => 'Tahun akademik berhasil ditambahkan',
            'data' => $academicYear->load('school')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicYear $academicYear)
    {
        $academicYear->load(['school', 'classes']);

        return response()->json($academicYear);
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
    public function update(Request $request, AcademicYear $academicYear)
    {
        $request->validate([
            'tahun_akademik' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'school_id' => 'required|exists:schools,id',
            'is_active' => 'boolean'
        ]);

        // Cek jika mengaktifkan tahun akademik
        if ($request->is_active && !$academicYear->is_active) {
            $activeYear = AcademicYear::where('school_id', $request->school_id)
                ->where('is_active', true)
                ->first();

            if ($activeYear) {
                throw ValidationException::withMessages([
                    'is_active' => ['Sudah ada tahun akademik yang aktif.']
                ]);
            }
        }

        $academicYear->update($request->all());

        return response()->json([
            'message' => 'Tahun akademik berhasil diperbarui',
            'data' => $academicYear->load('school')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        // Cek apakah tahun akademik masih memiliki kelas
        if ($academicYear->classes()->count() > 0) {
            throw ValidationException::withMessages([
                'academic_year' => ['Tidak dapat menghapus tahun akademik yang masih memiliki kelas.']
            ]);
        }

        $academicYear->delete();

        return response()->json([
            'message' => 'Tahun akademik berhasil dihapus'
        ]);
    }
}
