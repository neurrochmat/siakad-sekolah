<?php

namespace App\Helpers;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    public static function Menu()
    {
        $user = Auth::user();

        // Get all menu IDs that user has access to through roles
        $menuIds = $user->roles()
            ->with('menus')
            ->get()
            ->pluck('menus')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->values()
            ->toArray();

        // Get menus with proper eager loading
        $menus = Menu::with(['submenus' => function ($query) use ($menuIds) {
                $query->whereIn('id', $menuIds)
                    ->orderBy('order')
                    ->with(['submenus' => function ($query) use ($menuIds) {
                        $query->whereIn('id', $menuIds)
                            ->orderBy('order');
                    }]);
            }])
            ->whereNull('parent_id')
            ->whereIn('id', $menuIds)
            ->orderBy('order')
            ->get();

        return json_encode($menus);
    }

    public static function hasAccess($route)
    {
        $user = Auth::user();
        $menu = Menu::where('route', $route)->first();

        if (!$menu) {
            return false;
        }

        return $user->roles()
            ->whereHas('menus', function($query) use ($menu) {
                $query->where('menus.id', $menu->id);
            })
            ->exists();
    }
}
