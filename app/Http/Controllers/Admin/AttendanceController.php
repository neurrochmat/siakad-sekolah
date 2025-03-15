<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Attendance::query();

        // Filter berdasarkan siswa
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter berdasarkan kelas
        if ($request->has('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Filter berdasarkan mata pelajaran
        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // Filter berdasarkan status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->with(['student', 'class', 'subject'])->paginate($request->per_page ?? 10);

        return response()->json($attendances);
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
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'tanggal' => 'required|date',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i|after:waktu_masuk',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string'
        ]);

        // Cek apakah siswa terdaftar di kelas tersebut
        $student = Student::find($request->student_id);
        if ($student->class_id != $request->class_id) {
            throw ValidationException::withMessages([
                'student_id' => ['Siswa tidak terdaftar di kelas ini.']
            ]);
        }

        // Cek apakah sudah ada presensi untuk siswa pada tanggal dan mata pelajaran yang sama
        $exists = Attendance::where('student_id', $request->student_id)
            ->where('subject_id', $request->subject_id)
            ->whereDate('tanggal', $request->tanggal)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'student_id' => ['Presensi untuk siswa ini sudah ada pada tanggal dan mata pelajaran yang sama.']
            ]);
        }

        $attendance = Attendance::create($request->all());

        return response()->json([
            'message' => 'Presensi berhasil ditambahkan',
            'data' => $attendance->load(['student', 'class', 'subject'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        $attendance->load(['student', 'class', 'subject']);

        return response()->json($attendance);
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
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'tanggal' => 'required|date',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i|after:waktu_masuk',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string'
        ]);

        // Cek apakah siswa terdaftar di kelas tersebut
        $student = Student::find($request->student_id);
        if ($student->class_id != $request->class_id) {
            throw ValidationException::withMessages([
                'student_id' => ['Siswa tidak terdaftar di kelas ini.']
            ]);
        }

        // Cek apakah sudah ada presensi untuk siswa pada tanggal dan mata pelajaran yang sama (kecuali presensi ini)
        $exists = Attendance::where('student_id', $request->student_id)
            ->where('subject_id', $request->subject_id)
            ->whereDate('tanggal', $request->tanggal)
            ->where('id', '!=', $attendance->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'student_id' => ['Presensi untuk siswa ini sudah ada pada tanggal dan mata pelajaran yang sama.']
            ]);
        }

        $attendance->update($request->all());

        return response()->json([
            'message' => 'Presensi berhasil diperbarui',
            'data' => $attendance->load(['student', 'class', 'subject'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return response()->json([
            'message' => 'Presensi berhasil dihapus'
        ]);
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'tanggal' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:hadir,izin,sakit,alpha',
            'attendances.*.waktu_masuk' => 'nullable|date_format:H:i',
            'attendances.*.waktu_keluar' => 'nullable|date_format:H:i|after:attendances.*.waktu_masuk',
            'attendances.*.keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->attendances as $data) {
                // Cek apakah siswa terdaftar di kelas tersebut
                $student = Student::find($data['student_id']);
                if ($student->class_id != $request->class_id) {
                    throw ValidationException::withMessages([
                        'attendances' => ["Siswa dengan ID {$data['student_id']} tidak terdaftar di kelas ini."]
                    ]);
                }

                // Cek apakah sudah ada presensi untuk siswa pada tanggal dan mata pelajaran yang sama
                $exists = Attendance::where('student_id', $data['student_id'])
                    ->where('subject_id', $request->subject_id)
                    ->whereDate('tanggal', $request->tanggal)
                    ->exists();

                if ($exists) {
                    throw ValidationException::withMessages([
                        'attendances' => ["Presensi untuk siswa dengan ID {$data['student_id']} sudah ada."]
                    ]);
                }

                Attendance::create(array_merge($data, [
                    'class_id' => $request->class_id,
                    'subject_id' => $request->subject_id,
                    'tanggal' => $request->tanggal
                ]));
            }

            DB::commit();

            return response()->json([
                'message' => 'Presensi berhasil ditambahkan'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function report(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $report = DB::table('attendances')
            ->join('students', 'attendances.student_id', '=', 'students.id')
            ->where('attendances.class_id', $request->class_id)
            ->where('attendances.subject_id', $request->subject_id)
            ->whereBetween('attendances.tanggal', [$request->start_date, $request->end_date])
            ->select(
                'students.nama_lengkap',
                DB::raw('COUNT(CASE WHEN attendances.status = "hadir" THEN 1 END) as hadir'),
                DB::raw('COUNT(CASE WHEN attendances.status = "izin" THEN 1 END) as izin'),
                DB::raw('COUNT(CASE WHEN attendances.status = "sakit" THEN 1 END) as sakit'),
                DB::raw('COUNT(CASE WHEN attendances.status = "alpha" THEN 1 END) as alpha')
            )
            ->groupBy('students.id', 'students.nama_lengkap')
            ->get();

        return response()->json($report);
    }
}
