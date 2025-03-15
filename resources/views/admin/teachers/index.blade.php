@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Guru</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('') }}">Home</a></li>
                    <li class="breadcrumb-item active">Guru</li>
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
                        <h3 class="card-title">Daftar Guru</h3>
                        <div class="card-tools">
                            @can('create', App\Models\Teacher::class)
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-create">
                                <i class="fas fa-plus"></i> Tambah Guru
                            </button>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="search" placeholder="Cari guru...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="school-filter">
                                    <option value="">Semua Sekolah</option>
                                    @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->nama_sekolah }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" id="status-filter">
                                    <option value="">Semua Status</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Foto</th>
                                        <th>NIP</th>
                                        <th>Nama Lengkap</th>
                                        <th>Sekolah</th>
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th>Status</th>
                                        <th style="width: 150px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="teachers-table">
                                    @foreach($teachers as $index => $teacher)
                                    <tr>
                                        <td>{{ $teachers->firstItem() + $index }}</td>
                                        <td>
                                            @if($teacher->foto)
                                                <img src="{{ $teacher->foto }}" alt="Foto" class="img-thumbnail" style="max-height: 50px">
                                            @else
                                                No Photo
                                            @endif
                                        </td>
                                        <td>{{ $teacher->nip }}</td>
                                        <td>{{ $teacher->nama_lengkap }}</td>
                                        <td>{{ $teacher->school->nama_sekolah }}</td>
                                        <td>{{ $teacher->user->email }}</td>
                                        <td>{{ $teacher->telepon }}</td>
                                        <td>
                                            <span class="badge badge-{{ $teacher->is_active ? 'success' : 'danger' }}">
                                                {{ $teacher->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </td>
                                        <td>
                                            @can('update', $teacher)
                                            <button type="button" class="btn btn-warning btn-sm btn-edit" data-id="{{ $teacher->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endcan
                                            @can('delete', $teacher)
                                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="{{ $teacher->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endcan
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="pagination-container" class="mt-3">
                            {{ $teachers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Create -->
<div class="modal fade" id="modal-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-create" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Guru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nip">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                            </div>
                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tempat_lahir">Tempat Lahir</label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="telepon">Telepon</label>
                                <input type="text" class="form-control" id="telepon" name="telepon" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="school_id">Sekolah</label>
                                <select class="form-control" id="school_id" name="school_id" required>
                                    <option value="">Pilih Sekolah</option>
                                    @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->nama_sekolah }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="foto">Foto</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto" name="foto" accept="image/*">
                                    <label class="custom-file-label" for="foto">Pilih file</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                                    <label class="custom-control-label" for="is_active">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modal-edit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-edit" enctype="multipart/form-data">
                @method('PUT')
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Guru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_nip">NIP</label>
                                <input type="text" class="form-control" id="edit_nip" name="nip" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_nama_lengkap">Nama Lengkap</label>
                                <input type="text" class="form-control" id="edit_nama_lengkap" name="nama_lengkap" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                                <select class="form-control" id="edit_jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_tempat_lahir">Tempat Lahir</label>
                                <input type="text" class="form-control" id="edit_tempat_lahir" name="tempat_lahir" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="edit_tanggal_lahir" name="tanggal_lahir" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_alamat">Alamat</label>
                                <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_telepon">Telepon</label>
                                <input type="text" class="form-control" id="edit_telepon" name="telepon" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_email">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_password">Password</label>
                                <input type="password" class="form-control" id="edit_password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                            </div>
                            <div class="form-group">
                                <label for="edit_school_id">Sekolah</label>
                                <select class="form-control" id="edit_school_id" name="school_id" required>
                                    <option value="">Pilih Sekolah</option>
                                    @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->nama_sekolah }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_foto">Foto</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="edit_foto" name="foto" accept="image/*">
                                    <label class="custom-file-label" for="edit_foto">Pilih file</label>
                                </div>
                                <div id="current_foto" class="mt-2"></div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="edit_is_active" name="is_active">
                                    <label class="custom-control-label" for="edit_is_active">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    let page = 1;
    let search = '';
    let school = '';
    let status = '';

    // Load data
    function loadTeachers() {
        $.ajax({
            url: '{{ route("teachers.index") }}',
            type: 'GET',
            data: {
                page: page,
                search: search,
                school_id: school,
                is_active: status,
                per_page: 10
            },
            success: function(response) {
                let html = '';
                response.data.forEach((teacher, index) => {
                    html += `
                        <tr>
                            <td>${response.from + index}</td>
                            <td>
                                ${teacher.foto ?
                                    `<img src="${teacher.foto}" alt="Foto" class="img-thumbnail" style="max-height: 50px">` :
                                    'No Photo'}
                            </td>
                            <td>${teacher.nip}</td>
                            <td>${teacher.nama_lengkap}</td>
                            <td>${teacher.school.nama_sekolah}</td>
                            <td>${teacher.user.email}</td>
                            <td>${teacher.telepon}</td>
                            <td>
                                <span class="badge badge-${teacher.is_active ? 'success' : 'danger'}">
                                    ${teacher.is_active ? 'Aktif' : 'Tidak Aktif'}
                                </span>
                            </td>
                            <td>
                                @can('update', 'teacher')
                                <button type="button" class="btn btn-warning btn-sm btn-edit" data-id="${teacher.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endcan
                                @can('delete', 'teacher')
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="${teacher.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </td>
                        </tr>
                    `;
                });

                $('#teachers-table').html(html);

                // Update pagination
                let pagination = '';
                for (let i = 1; i <= response.last_page; i++) {
                    pagination += `
                        <button class="btn btn-${page === i ? 'primary' : 'default'} btn-sm mx-1"
                                onclick="changePage(${i})">${i}</button>
                    `;
                }
                $('#pagination-container').html(pagination);
            }
        });
    }

    // Search handler
    $('#search').on('keyup', function() {
        search = $(this).val();
        page = 1;
        loadTeachers();
    });

    // School filter handler
    $('#school-filter').on('change', function() {
        school = $(this).val();
        page = 1;
        loadTeachers();
    });

    // Status filter handler
    $('#status-filter').on('change', function() {
        status = $(this).val();
        page = 1;
        loadTeachers();
    });

    // Create form handler
    $('#form-create').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: '{{ route("teachers.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#modal-create').modal('hide');
                $('#form-create')[0].reset();
                window.location.reload();
                toastr.success(response.message);
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    toastr.error(errors[key][0]);
                });
            }
        });
    });

    // Edit button handler
    $(document).on('click', '.btn-edit', function() {
        let id = $(this).data('id');

        $.get(`/teachers/${id}`, function(response) {
            $('#edit_id').val(response.id);
            $('#edit_nip').val(response.nip);
            $('#edit_nama_lengkap').val(response.nama_lengkap);
            $('#edit_jenis_kelamin').val(response.jenis_kelamin);
            $('#edit_tempat_lahir').val(response.tempat_lahir);
            $('#edit_tanggal_lahir').val(response.tanggal_lahir);
            $('#edit_alamat').val(response.alamat);
            $('#edit_telepon').val(response.telepon);
            $('#edit_email').val(response.user.email);
            $('#edit_school_id').val(response.school_id);
            $('#edit_is_active').prop('checked', response.is_active);

            if (response.foto) {
                $('#current_foto').html(`
                    <img src="${response.foto}" alt="Current Photo" class="img-thumbnail" style="max-height: 100px">
                `);
            }

            $('#modal-edit').modal('show');
        });
    });

    // Update form handler
    $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        let id = $('#edit_id').val();
        let formData = new FormData(this);

        $.ajax({
            url: `/teachers/${id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#modal-edit').modal('hide');
                window.location.reload();
                toastr.success(response.message);
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    toastr.error(errors[key][0]);
                });
            }
        });
    });

    // Delete button handler
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data guru akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/teachers/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        window.location.reload();
                        toastr.success(response.message);
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message);
                    }
                });
            }
        });
    });

    // File input handler
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});

// Pagination handler
function changePage(newPage) {
    page = newPage;
    loadTeachers();
}
</script>
@endpush
