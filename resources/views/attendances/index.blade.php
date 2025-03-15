@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Presensi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('') }}">Home</a></li>
                    <li class="breadcrumb-item active">Presensi</li>
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
                        <h3 class="card-title">Daftar Presensi</h3>
                        <div class="card-tools">
                            <a href="{{ route('attendances.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Presensi
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter_class">Filter Kelas</label>
                                    <select class="form-control" id="filter_class">
                                        <option value="">Semua Kelas</option>
                                        @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter_subject">Filter Mata Pelajaran</label>
                                    <select class="form-control" id="filter_subject">
                                        <option value="">Semua Mata Pelajaran</option>
                                        @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->nama_mapel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter_date">Filter Tanggal</label>
                                    <input type="date" class="form-control" id="filter_date">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter_status">Filter Status</label>
                                    <select class="form-control" id="filter_status">
                                        <option value="">Semua Status</option>
                                        <option value="hadir">Hadir</option>
                                        <option value="sakit">Sakit</option>
                                        <option value="izin">Izin</option>
                                        <option value="alpha">Alpha</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <table id="datatable-main" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $attendance->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $attendance->student->class->nama_kelas }}</td>
                                    <td>{{ $attendance->subject->nama_mapel }}</td>
                                    <td>{{ $attendance->teacher->nama }}</td>
                                    <td>{{ $attendance->student->nis }}</td>
                                    <td>{{ $attendance->student->nama }}</td>
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
                                    <td>{{ $attendance->keterangan }}</td>
                                    <td>
                                        <a href="{{ route('attendances.show', $attendance->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm confirm-button">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inisialisasi DataTable
    var table = $('#datatable-main').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#datatable-main_wrapper .col-md-6:eq(0)');

    // Filter Kelas
    $('#filter_class').on('change', function() {
        table.column(2).search($(this).val()).draw();
    });

    // Filter Mata Pelajaran
    $('#filter_subject').on('change', function() {
        table.column(3).search($(this).val()).draw();
    });

    // Filter Tanggal
    $('#filter_date').on('change', function() {
        table.column(1).search($(this).val()).draw();
    });

    // Filter Status
    $('#filter_status').on('change', function() {
        table.column(7).search($(this).val()).draw();
    });
});
</script>
@endpush
