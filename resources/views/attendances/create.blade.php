@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Tambah Presensi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Presensi</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Tambah Presensi</h3>
                    </div>
                    <form action="{{ route('attendances.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                        @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="class_id">Kelas</label>
                                        <select class="form-control @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required>
                                            <option value="">Pilih Kelas</option>
                                            @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->nama_kelas }} ({{ $class->educationLevel->nama_jenjang }} - {{ $class->academicYear->tahun_ajaran }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="subject_id">Mata Pelajaran</label>
                                        <select class="form-control @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id" required>
                                            <option value="">Pilih Mata Pelajaran</option>
                                            @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->nama_mapel }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('subject_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <h4>Daftar Siswa</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIS</th>
                                                    <th>Nama</th>
                                                    <th>Status</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody id="student-list">
                                                <!-- Data siswa akan dimuat melalui AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('attendances.index') }}" class="btn btn-default">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Load siswa saat kelas dipilih
    $('#class_id').on('change', function() {
        var classId = $(this).val();
        if (classId) {
            $.get('/admin/attendances/get-students/' + classId, function(data) {
                var html = '';
                data.forEach(function(student, index) {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${student.nis}</td>
                            <td>${student.nama}</td>
                            <td>
                                <input type="hidden" name="student_ids[]" value="${student.id}">
                                <select class="form-control" name="status[]" required>
                                    <option value="hadir">Hadir</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="izin">Izin</option>
                                    <option value="alpha">Alpha</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="keterangan[]">
                            </td>
                        </tr>
                    `;
                });
                $('#student-list').html(html);
            });
        } else {
            $('#student-list').html('');
        }
    });
});
</script>
@endpush
