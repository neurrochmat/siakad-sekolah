<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read_role')->only('index', 'show');
        $this->middleware('permission:create_role')->only('create', 'store');
        $this->middleware('permission:update_role')->only('edit', 'update');
        $this->middleware('permission:delete_role')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menus = Menu::with(['submenus.permissions', 'permissions'])->where('parent_id', null)->get();
        return view('roles.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:roles,name',
                'menu_id' => 'array',
                'permission_id' => 'array'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $role = Role::create(['name' => strtolower($request->name)]);

            if ($request->has('menu_id')) {
                $menuData = array_map(function($menu_id) use ($role) {
                    return [
                        'menu_id' => $menu_id,
                        'role_id' => $role->id
                    ];
                }, $request->menu_id);

                DB::table('role_has_menus')->insert($menuData);
            }

            if ($request->has('permission_id')) {
                $role->syncPermissions($request->permission_id);
            }

            toastr()->success('Role berhasil disimpan');
            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            toastr()->warning('Terdapat masalah diserver: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menus = Menu::with(['submenus.permissions', 'permissions'])->where('parent_id', null)->get();
        $role = Role::with('permissions')->findOrFail($id);
        $getmenus = DB::table('role_has_menus')->where('role_id', $id)->get();

        return view('roles.edit', compact('role', 'menus', 'getmenus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:roles,name,' . $id,
                'menu_id' => 'array',
                'permission_id' => 'array'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $role = Role::findOrFail($id);

            DB::table('role_has_menus')->where('role_id', $id)->delete();

            if ($request->has('menu_id')) {
                $menuData = array_map(function($menu_id) use ($role) {
                    return [
                        'menu_id' => $menu_id,
                        'role_id' => $role->id
                    ];
                }, $request->menu_id);

                DB::table('role_has_menus')->insert($menuData);
            }

            $role->update(['name' => strtolower($request->name)]);

            if ($request->has('permission_id')) {
                $role->syncPermissions($request->permission_id);
            } else {
                $role->syncPermissions([]);
            }

            toastr()->success('Role berhasil diperbarui');
            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            toastr()->warning('Terdapat masalah diserver: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            if($role->name === 'admin') {
                toastr()->warning('Role admin tidak dapat dihapus');
                return redirect()->back();
            }

            DB::table('role_has_menus')->where('role_id', $id)->delete();
            $role->delete();

            toastr()->success('Role berhasil dihapus');
            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            toastr()->warning('Terdapat masalah diserver: ' . $th->getMessage());
            return redirect()->back();
        }
    }
}
