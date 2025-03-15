<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Teacher::query();

        // Filter berdasarkan sekolah
        if ($request->has('school_id') && !empty($request->school_id)) {
            $query->where('school_id', $request->school_id);
        }

        // Filter berdasarkan status aktif
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }

        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
            });
        }

        $teachers = $query->with(['school', 'user', 'classesAsWaliKelas'])->latest()->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => $teachers->items(),
                'current_page' => $teachers->currentPage(),
                'per_page' => $teachers->perPage(),
                'last_page' => $teachers->lastPage(),
                'total' => $teachers->total(),
                'from' => $teachers->firstItem(),
                'to' => $teachers->lastItem(),
            ]);
        }

        $schools = \App\Models\School::where('is_active', true)->get();
        return view('admin.teachers.index', compact('teachers', 'schools'));
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
            'nip' => 'required|string|unique:teachers',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'telepon' => 'required|string',
            'foto' => 'nullable|image|max:2048', // Max 2MB
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'school_id' => 'required|exists:schools,id',
            'is_active' => 'boolean'
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru',
            'school_id' => $request->school_id
        ]);

        $data = $request->except(['email', 'password', 'foto']);
        $data['user_id'] = $user->id;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/teachers');
            $data['foto'] = Storage::url($path);
        }

        $teacher = Teacher::create($data);

        return response()->json([
            'message' => 'Guru berhasil ditambahkan',
            'data' => $teacher->load(['school', 'user'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['school', 'user', 'classesAsWaliKelas']);

        return response()->json($teacher);
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
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'nip' => 'required|string|unique:teachers,nip,' . $teacher->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'telepon' => 'required|string',
            'foto' => 'nullable|image|max:2048', // Max 2MB
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'password' => 'nullable|string|min:8',
            'school_id' => 'required|exists:schools,id',
            'is_active' => 'boolean'
        ]);

        // Update user
        $userData = [
            'name' => $request->nama_lengkap,
            'email' => $request->email
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $teacher->user->update($userData);

        $data = $request->except(['email', 'password', 'foto']);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($teacher->foto) {
                Storage::delete(str_replace('/storage', 'public', $teacher->foto));
            }

            $path = $request->file('foto')->store('public/teachers');
            $data['foto'] = Storage::url($path);
        }

        $teacher->update($data);

        return response()->json([
            'message' => 'Data guru berhasil diperbarui',
            'data' => $teacher->load(['school', 'user'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        // Cek apakah guru masih menjadi wali kelas
        if ($teacher->classesAsWaliKelas()->count() > 0) {
            throw ValidationException::withMessages([
                'teacher' => ['Tidak dapat menghapus guru yang masih menjadi wali kelas.']
            ]);
        }

        // Hapus foto jika ada
        if ($teacher->foto) {
            Storage::delete(str_replace('/storage', 'public', $teacher->foto));
        }

        // Hapus user terkait
        $teacher->user->delete();

        $teacher->delete();

        return response()->json([
            'message' => 'Data guru berhasil dihapus'
        ]);
    }
}
