<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // Filter berdasarkan sekolah
        if ($request->has('school_id') && !empty($request->school_id)) {
            $query->where('school_id', $request->school_id);
        }

        // Filter berdasarkan kelas
        if ($request->has('class_id') && !empty($request->class_id)) {
            $query->where('class_id', $request->class_id);
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
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $students = $query->with(['school', 'class', 'user'])->latest()->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => $students->items(),
                'current_page' => $students->currentPage(),
                'per_page' => $students->perPage(),
                'last_page' => $students->lastPage(),
                'total' => $students->total(),
                'from' => $students->firstItem(),
                'to' => $students->lastItem(),
            ]);
        }

        $schools = \App\Models\School::where('is_active', true)->get();
        $classes = \App\Models\ClassRoom::where('is_active', true)
            ->when($request->has('school_id'), function($q) use ($request) {
                return $q->where('school_id', $request->school_id);
            })
            ->get();

        return view('admin.students.index', compact('students', 'schools', 'classes'));
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
            'nis' => 'required|string|unique:students',
            'nisn' => 'required|string|unique:students',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'telepon_ortu' => 'nullable|string',
            'foto' => 'nullable|image|max:2048', // Max 2MB
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'school_id' => 'required|exists:schools,id',
            'class_id' => 'required|exists:classes,id',
            'is_active' => 'boolean'
        ]);

        // Validasi kapasitas kelas
        $class = \App\Models\ClassRoom::find($request->class_id);
        if ($class->students()->count() >= $class->kapasitas) {
            throw ValidationException::withMessages([
                'class_id' => ['Kelas sudah penuh.']
            ]);
        }

        // Buat user baru
        $user = User::create([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'school_id' => $request->school_id
        ]);

        $data = $request->except(['email', 'password', 'foto']);
        $data['user_id'] = $user->id;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/students');
            $data['foto'] = Storage::url($path);
        }

        $student = Student::create($data);

        return response()->json([
            'message' => 'Siswa berhasil ditambahkan',
            'data' => $student->load(['school', 'class', 'user'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load(['school', 'class', 'user', 'attendances']);

        return response()->json($student);
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
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nis' => 'required|string|unique:students,nis,' . $student->id,
            'nisn' => 'required|string|unique:students,nisn,' . $student->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'telepon_ortu' => 'nullable|string',
            'foto' => 'nullable|image|max:2048', // Max 2MB
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'password' => 'nullable|string|min:8',
            'school_id' => 'required|exists:schools,id',
            'class_id' => 'required|exists:classes,id',
            'is_active' => 'boolean'
        ]);

        // Validasi kapasitas kelas jika pindah kelas
        if ($request->class_id != $student->class_id) {
            $class = \App\Models\ClassRoom::find($request->class_id);
            if ($class->students()->count() >= $class->kapasitas) {
                throw ValidationException::withMessages([
                    'class_id' => ['Kelas tujuan sudah penuh.']
                ]);
            }
        }

        // Update user
        $userData = [
            'name' => $request->nama_lengkap,
            'email' => $request->email
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $student->user->update($userData);

        $data = $request->except(['email', 'password', 'foto']);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($student->foto) {
                Storage::delete(str_replace('/storage', 'public', $student->foto));
            }

            $path = $request->file('foto')->store('public/students');
            $data['foto'] = Storage::url($path);
        }

        $student->update($data);

        return response()->json([
            'message' => 'Data siswa berhasil diperbarui',
            'data' => $student->load(['school', 'class', 'user'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Cek apakah siswa masih memiliki data presensi
        if ($student->attendances()->count() > 0) {
            throw ValidationException::withMessages([
                'student' => ['Tidak dapat menghapus siswa yang masih memiliki data presensi.']
            ]);
        }

        // Hapus foto jika ada
        if ($student->foto) {
            Storage::delete(str_replace('/storage', 'public', $student->foto));
        }

        // Hapus user terkait
        $student->user->delete();

        $student->delete();

        return response()->json([
            'message' => 'Data siswa berhasil dihapus'
        ]);
    }
}
