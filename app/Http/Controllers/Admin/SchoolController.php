<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = School::query();

        // Filter berdasarkan status aktif
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }

        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_sekolah', 'like', "%{$search}%")
                  ->orWhere('npsn', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $schools = $query->latest()->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => $schools->items(),
                'current_page' => $schools->currentPage(),
                'per_page' => $schools->perPage(),
                'last_page' => $schools->lastPage(),
                'total' => $schools->total(),
                'from' => $schools->firstItem(),
                'to' => $schools->lastItem(),
            ]);
        }

        return view('admin.schools.index', compact('schools'));
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
            'nama_sekolah' => 'required|string|max:255',
            'npsn' => 'required|string|unique:schools',
            'alamat' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'required|email|unique:schools',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|max:2048', // Max 2MB
            'is_active' => 'boolean'
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('public/logos');
            $data['logo'] = Storage::url($path);
        }

        $school = School::create($data);

        return response()->json([
            'message' => 'Sekolah berhasil ditambahkan',
            'data' => $school
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        $school->load(['educationLevels', 'academicYears', 'classes', 'teachers', 'students']);

        return response()->json($school);
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
    public function update(Request $request, School $school)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'npsn' => 'required|string|unique:schools,npsn,' . $school->id,
            'alamat' => 'required|string',
            'telepon' => 'required|string',
            'email' => 'required|email|unique:schools,email,' . $school->id,
            'website' => 'nullable|url',
            'logo' => 'nullable|image|max:2048', // Max 2MB
            'is_active' => 'boolean'
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($school->logo) {
                Storage::delete(str_replace('/storage', 'public', $school->logo));
            }

            $path = $request->file('logo')->store('public/logos');
            $data['logo'] = Storage::url($path);
        }

        $school->update($data);

        return response()->json([
            'message' => 'Sekolah berhasil diperbarui',
            'data' => $school
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        // Cek apakah sekolah masih memiliki data terkait
        if ($school->users()->count() > 0) {
            throw ValidationException::withMessages([
                'school' => ['Tidak dapat menghapus sekolah yang masih memiliki pengguna.']
            ]);
        }

        // Hapus logo jika ada
        if ($school->logo) {
            Storage::delete(str_replace('/storage', 'public', $school->logo));
        }

        $school->delete();

        return response()->json([
            'message' => 'Sekolah berhasil dihapus'
        ]);
    }
}
