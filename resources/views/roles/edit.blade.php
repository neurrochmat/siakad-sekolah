@extends('layouts.app')
@push('css')
@endpush
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 text-uppercase">
                    <h4 class="m-0">Edit role</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0"></h5>
                            <div class="card-tools">
                                <a href="{{ route('roles.index') }}" class="btn btn-tool"><i
                                        class="fas fa-arrow-alt-circle-left"></i></a>
                            </div>
                        </div>
                        <form action="{{ route('roles.update', $role->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nama Role Akses</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name')is-invalid @enderror"
                                        placeholder="Nama Role Akses" value="{{ old('name', $role->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback" role="alert">
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Roles Menu Permission</label>
                                    <ul class="checktree">
                                        @foreach ($menus as $menu)
                                            @if (count($menu->submenus) == 0)
                                                <li><input type="checkbox" name="menu_id[]" @checked(in_array($menu->id, $getmenus->pluck('menu_id')->toarray()))
                                                        value="{{ $menu->id }}"> <b>
                                                        {{ $menu->nama_menu }}</b>
                                                    @if (count($menu->permissions) > 0)
                                                        <ul>
                                                            @foreach ($menu->permissions as $permission)
                                                                <li>
                                                                    <input type="checkbox" name="permission_id[]"
                                                                        @checked(in_array($permission->name, $role->permissions->pluck('name')->toArray()))
                                                                        value="{{ $permission->name }}">
                                                                    {!! $permission->detail . '<i>( ' . $permission->name . ' )</i>' !!}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @else
                                                <li>
                                                    <input type="checkbox" name="menu_id[]" @checked(in_array($menu->id, $getmenus->pluck('menu_id')->toarray()))
                                                        value="{{ $menu->id }}"> <b>
                                                        {{ $menu->nama_menu }}</b>
                                                    <ul>
                                                        @foreach ($menu->submenus as $submenu)
                                                            <li>
                                                                <input type="checkbox" name="menu_id[]"
                                                                    @checked(in_array($submenu->id, $getmenus->pluck('menu_id')->toarray()))
                                                                    value="{{ $submenu->id }}">
                                                                <b>
                                                                    {{ ucwords($submenu->nama_menu) }}</b>
                                                                @if (count($submenu->permissions) > 0)
                                                                    <ul>
                                                                        @foreach ($submenu->permissions as $permission)
                                                                            <li>
                                                                                <input type="checkbox"
                                                                                    @checked(in_array($permission->name, $role->permissions->pluck('name')->toArray()))
                                                                                    name="permission_id[]"
                                                                                    value="{{ $permission->name }}">
                                                                                {!! $permission->detail . '<i>( ' . $permission->name . ')</i>' !!}
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info btn-block btn-flat"><i class="fa fa-save"></i>
                                    Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
@endpush
