@extends('layouts.app')

@section('title', 'Manajemen Sekolah')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Sekolah</h3>
                    <div class="card-tools">
                        @can('create', App\Models\School::class)
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-create">
                            <i class="fas fa-plus"></i> Tambah Sekolah
                        </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" id="search" placeholder="Cari sekolah...">
                            </div>
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
                                    <th>Logo</th>
                                    <th>Nama Sekolah</th>
                                    <th>NPSN</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Status</th>
                                    <th style="width: 150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="schools-table">
                                @foreach($schools as $index => $school)
                                <tr>
                                    <td>{{ $schools->firstItem() + $index }}</td>
                                    <td>
                                        @if($school->logo)
                                            <img src="{{ $school->logo }}" alt="Logo" class="img-thumbnail" style="max-height: 50px">
                                        @else
                                            No Logo
                                        @endif
                                    </td>
                                    <td>{{ $school->nama_sekolah }}</td>
                                    <td>{{ $school->npsn }}</td>
                                    <td>{{ $school->email }}</td>
                                    <td>{{ $school->telepon }}</td>
                                    <td>
                                        <span class="badge badge-{{ $school->is_active ? 'success' : 'danger' }}">
                                            {{ $school->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        @can('update', $school)
                                        <button type="button" class="btn btn-warning btn-sm btn-edit" data-id="{{ $school->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endcan
                                        @can('delete', $school)
                                        <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="{{ $school->id }}">
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
                        {{ $schools->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="modal-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-create" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Sekolah</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_sekolah">Nama Sekolah</label>
                        <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" required>
                    </div>
                    <div class="form-group">
                        <label for="npsn">NPSN</label>
                        <input type="text" class="form-control" id="npsn" name="npsn" required>
                    </div>
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
                        <label for="website">Website</label>
                        <input type="url" class="form-control" id="website" name="website">
                    </div>
                    <div class="form-group">
                        <label for="logo">Logo</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="logo" name="logo" accept="image/*">
                            <label class="custom-file-label" for="logo">Pilih file</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                            <label class="custom-control-label" for="is_active">Aktif</label>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-edit" enctype="multipart/form-data">
                @method('PUT')
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Sekolah</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_nama_sekolah">Nama Sekolah</label>
                        <input type="text" class="form-control" id="edit_nama_sekolah" name="nama_sekolah" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_npsn">NPSN</label>
                        <input type="text" class="form-control" id="edit_npsn" name="npsn" required>
                    </div>
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
                        <label for="edit_website">Website</label>
                        <input type="url" class="form-control" id="edit_website" name="website">
                    </div>
                    <div class="form-group">
                        <label for="edit_logo">Logo</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="edit_logo" name="logo" accept="image/*">
                            <label class="custom-file-label" for="edit_logo">Pilih file</label>
                        </div>
                        <div id="current_logo" class="mt-2"></div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="edit_is_active" name="is_active">
                            <label class="custom-control-label" for="edit_is_active">Aktif</label>
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
    let status = '';

    // Load data
    function loadSchools() {
        $.ajax({
            url: '{{ route("schools.index") }}',
            type: 'GET',
            data: {
                page: page,
                search: search,
                is_active: status,
                per_page: 10
            },
            success: function(response) {
                let html = '';
                response.data.forEach((school, index) => {
                    html += `
                        <tr>
                            <td>${response.from + index}</td>
                            <td>
                                ${school.logo ?
                                    `<img src="${school.logo}" alt="Logo" class="img-thumbnail" style="max-height: 50px">` :
                                    'No Logo'}
                            </td>
                            <td>${school.nama_sekolah}</td>
                            <td>${school.npsn}</td>
                            <td>${school.email}</td>
                            <td>${school.telepon}</td>
                            <td>
                                <span class="badge badge-${school.is_active ? 'success' : 'danger'}">
                                    ${school.is_active ? 'Aktif' : 'Tidak Aktif'}
                                </span>
                            </td>
                            <td>
                                @can('update', 'school')
                                <button type="button" class="btn btn-warning btn-sm btn-edit" data-id="${school.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endcan
                                @can('delete', 'school')
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="${school.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </td>
                        </tr>
                    `;
                });

                $('#schools-table').html(html);

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
        loadSchools();
    });

    // Status filter handler
    $('#status-filter').on('change', function() {
        status = $(this).val();
        page = 1;
        loadSchools();
    });

    // Create form handler
    $('#form-create').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: '{{ route("schools.store") }}',
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

        $.get(`/schools/${id}`, function(response) {
            $('#edit_id').val(response.id);
            $('#edit_nama_sekolah').val(response.nama_sekolah);
            $('#edit_npsn').val(response.npsn);
            $('#edit_alamat').val(response.alamat);
            $('#edit_telepon').val(response.telepon);
            $('#edit_email').val(response.email);
            $('#edit_website').val(response.website);
            $('#edit_is_active').prop('checked', response.is_active);

            if (response.logo) {
                $('#current_logo').html(`
                    <img src="${response.logo}" alt="Current Logo" class="img-thumbnail" style="max-height: 100px">
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
            url: `/schools/${id}`,
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
            text: "Data sekolah akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/schools/${id}`,
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
    loadSchools();
}
</script>
@endpush
