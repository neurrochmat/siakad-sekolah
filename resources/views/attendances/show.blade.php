@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Presensi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Presensi</a></li>
                    <li class="breadcrumb-item active">Detail</li>
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
                        <h3 class="card-title">Detail Presensi</h3>
                        <div class="card-tools">
                            <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 200px">Tanggal</th>
                                        <td>{{ $attendance->tanggal->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kelas</th>
                                        <td>{{ $attendance->student->class->nama_kelas }} ({{ $attendance->student->class->educationLevel->nama_jenjang }} - {{ $attendance->student->class->academicYear->tahun_ajaran }})</td>
                                    </tr>
                                    <tr>
                                        <th>Mata Pelajaran</th>
                                        <td>{{ $attendance->subject->nama_mapel }}</td>
                                    </tr>
                                    <tr>
                                        <th>Guru</th>
                                        <td>{{ $attendance->teacher->nama }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 200px">NIS</th>
                                        <td>{{ $attendance->student->nis }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <td>{{ $attendance->student->nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @switch($attendance->status)
                                                @case('hadir')
                                                    <span class="badge badge-success">Hadir</span>
                                                    @break
                                                @case('sakit')
                                                    <span class="badge badge-warning">Sakit</span>
                                                    @break
                                                @case('izin')
                                                    <span class="badge badge-info">Izin</span>
                                                    @break
                                                @case('alpha')
                                                    <span class="badge badge-danger">Alpha</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Keterangan</th>
                                        <td>{{ $attendance->keterangan ?: '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h4>Riwayat Presensi Siswa</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Mata Pelajaran</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($studentAttendances as $studentAttendance)
                                            <tr>
                                                <td>{{ $studentAttendance->tanggal->format('d/m/Y') }}</td>
                                                <td>{{ $studentAttendance->subject->nama_mapel }}</td>
                                                <td>
                                                    @switch($studentAttendance->status)
                                                        @case('hadir')
                                                            <span class="badge badge-success">Hadir</span>
                                                            @break
                                                        @case('sakit')
                                                            <span class="badge badge-warning">Sakit</span>
                                                            @break
                                                        @case('izin')
                                                            <span class="badge badge-info">Izin</span>
                                                            @break
                                                        @case('alpha')
                                                            <span class="badge badge-danger">Alpha</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td>{{ $studentAttendance->keterangan ?: '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('attendances.index') }}" class="btn btn-default">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
