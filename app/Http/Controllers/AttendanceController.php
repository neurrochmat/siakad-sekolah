<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $attendances = Attendance::with(['student', 'teacher', 'subject'])->latest()->get();
        $classes = ClassRoom::with(['educationLevel', 'academicYear'])->get();
        $subjects = Subject::all();

        return view('attendances.index', compact('attendances', 'classes', 'subjects'));
    }

    public function create()
    {
        $classes = ClassRoom::with(['educationLevel', 'academicYear'])->get();
        $subjects = Subject::all();

        return view('attendances.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'class_id' => 'required|exists:class_rooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'status' => 'required|array',
            'status.*' => 'required|in:hadir,sakit,izin,alpha',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:255'
        ]);

        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        foreach ($request->student_ids as $key => $studentId) {
            Attendance::create([
                'tanggal' => $request->tanggal,
                'student_id' => $studentId,
                'teacher_id' => $teacher->id,
                'subject_id' => $request->subject_id,
                'status' => $request->status[$key],
                'keterangan' => $request->keterangan[$key] ?? null
            ]);
        }

        return redirect()->route('attendances.index')
            ->with('success', 'Presensi berhasil ditambahkan');
    }

    public function show(Attendance $attendance)
    {
        $studentAttendances = Attendance::where('student_id', $attendance->student_id)
            ->with(['subject'])
            ->latest()
            ->get();

        return view('attendances.show', compact('attendance', 'studentAttendances'));
    }

    public function edit(Attendance $attendance)
    {
        $classes = ClassRoom::with(['educationLevel', 'academicYear'])->get();
        $subjects = Subject::all();

        return view('attendances.edit', compact('attendance', 'classes', 'subjects'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'class_id' => 'required|exists:class_rooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'status' => 'required|array',
            'status.*' => 'required|in:hadir,sakit,izin,alpha',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:255'
        ]);

        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        foreach ($request->student_ids as $key => $studentId) {
            if ($studentId == $attendance->student_id) {
                $attendance->update([
                    'tanggal' => $request->tanggal,
                    'teacher_id' => $teacher->id,
                    'subject_id' => $request->subject_id,
                    'status' => $request->status[$key],
                    'keterangan' => $request->keterangan[$key] ?? null
                ]);
            }
        }

        return redirect()->route('attendances.index')
            ->with('success', 'Presensi berhasil diperbarui');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendances.index')
            ->with('success', 'Presensi berhasil dihapus');
    }

    public function getStudents(ClassRoom $class)
    {
        return response()->json($class->students);
    }
}
