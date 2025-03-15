@php
    use App\Helpers\MenuHelper;
@endphp

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    @foreach (json_decode(MenuHelper::Menu()) as $menu)
        @if ($menu->route && !MenuHelper::hasAccess($menu->route))
            @continue
        @endif

        @if (count($menu->submenus) == 0)
            <li class="nav-item">
                <a href="{{ $menu->route == '#' ? '#' : route($menu->route) }}"
                    class="nav-link {{ request()->routeIs($menu->route) ? 'active' : '' }}">
                    <i class="{{ $menu->icon }}"></i>
                    <p>{{ $menu->nama_menu }}</p>
                </a>
            </li>
        @else
            @php
                $hasAccessibleSubmenu = false;
                foreach ($menu->submenus as $submenu) {
                    if (!$submenu->route || MenuHelper::hasAccess($submenu->route)) {
                        $hasAccessibleSubmenu = true;
                        break;
                    }
                }
            @endphp

            @if (!$hasAccessibleSubmenu)
                @continue
            @endif

            <li class="nav-item {{ collect($menu->submenus)->contains(function($submenu) {
                return request()->routeIs($submenu->route);
            }) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ collect($menu->submenus)->contains(function($submenu) {
                    return request()->routeIs($submenu->route);
                }) ? 'active' : '' }}">
                    <i class="{{ $menu->icon }}"></i>
                    <p>
                        {{ $menu->nama_menu }}
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @foreach ($menu->submenus as $submenu)
                        @if ($submenu->route && !MenuHelper::hasAccess($submenu->route))
                            @continue
                        @endif

                        @if (count($submenu->submenus) == 0)
                            <li class="nav-item">
                                <a href="{{ $submenu->route == '#' ? '#' : route($submenu->route) }}"
                                    class="nav-link {{ request()->routeIs($submenu->route) ? 'active' : '' }}">
                                    <i class="{{ $submenu->icon }}"></i>
                                    <p>{{ $submenu->nama_menu }}</p>
                                </a>
                            </li>
                        @else
                            @php
                                $hasAccessibleSubSubmenu = false;
                                foreach ($submenu->submenus as $subsubmenu) {
                                    if (!$subsubmenu->route || MenuHelper::hasAccess($subsubmenu->route)) {
                                        $hasAccessibleSubSubmenu = true;
                                        break;
                                    }
                                }
                            @endphp

                            @if (!$hasAccessibleSubSubmenu)
                                @continue
                            @endif

                            <li class="nav-item {{ collect($submenu->submenus)->contains(function($subsubmenu) {
                                return request()->routeIs($subsubmenu->route);
                            }) ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ collect($submenu->submenus)->contains(function($subsubmenu) {
                                    return request()->routeIs($subsubmenu->route);
                                }) ? 'active' : '' }}">
                                    <i class="{{ $submenu->icon }}"></i>
                                    <p>
                                        {{ $submenu->nama_menu }}
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @foreach ($submenu->submenus as $subsubmenu)
                                        @if ($subsubmenu->route && !MenuHelper::hasAccess($subsubmenu->route))
                                            @continue
                                        @endif

                                        <li class="nav-item">
                                            <a href="{{ $subsubmenu->route == '#' ? '#' : route($subsubmenu->route) }}"
                                                class="nav-link {{ request()->routeIs($subsubmenu->route) ? 'active' : '' }}">
                                                <i class="{{ $subsubmenu->icon }}"></i>
                                                <p>{{ $subsubmenu->nama_menu }}</p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        @endif
    @endforeach
</ul>
