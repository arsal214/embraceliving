<div class="sidebar">
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Dashboard -->
            <li class="nav-item ">
                <a href="#" class="nav-link ">
                    <i class="nav-icon fas fa-eye"></i>
                    <p>
                        Dashboard
                        <i class="fas fa-chevron-right right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/admin/Dashboard/Report" class="nav-link ">
                            <i class="nav-icon fas fa-eye"></i>
                            <p>
                                Report
                            </p>
                        </a>
                    </li>

                </ul>
            </li>

            @can('roles-list')
                <!-- Roles -->
                <li class="nav-item">
                    <a href="{{route('roles.index')}}" class="nav-link ">
                        <i class="nav-icon fas fa-seedling"></i>
                        <p>Role</p>
                    </a>
                </li>
            @endcan
{{--            @can('pages-list')--}}
                <!-- Roles -->
                <li class="nav-item">
                    <a href="{{route('pages.index')}}" class="nav-link ">
                        <i class="nav-icon fas fa-seedling"></i>
                        <p>Pages</p>
                    </a>
                </li>
{{--            @endcan--}}
            @can('regions-list')
                <!-- Roles -->
                <li class="nav-item">
                    <a href="{{route('regions.index')}}" class="nav-link ">
                        <i class="nav-icon fas fa-seedling"></i>
                        <p>Regions</p>
                    </a>
                </li>
            @endcan
            <!-- Group -->
            @can('groups-list')
                <li class="nav-item">
                    <a href="{{route('groups.index')}}" class="nav-link ">
                        <i class="nav-icon fas fa-seedling"></i>
                        <p>Groups</p>
                    </a>
                </li>
            @endcan

            <!-- Homes -->
            @can('homes-list')
                <li class="nav-item">
                    <a href="{{route('homes.index')}}" class="nav-link ">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Homes</p>
                    </a>
                </li>
            @endcan

            <!-- Themes -->
            @can('themes-list')
                <li class="nav-item">
                    <a href="{{route('themes.index')}}" class="nav-link ">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Themes</p>
                    </a>
                </li>
            @endcan


            <!-- Users -->
            @can('users-list')
                <li class="nav-item">
                    <a href="{{route('users.index')}}" class="nav-link ">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Users</p>
                    </a>
                </li>
            @endcan
            <!-- Permission -->
            @can('permissions-list')
                <li class="nav-item">
                    <a href="{{route('permissions.index')}}" class="nav-link ">
                        <i class="nav-icon fas fa-seedling"></i>
                        <p>Permission</p>
                    </a>
                </li>
            @endcan

            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link">
                        <i class="nav-icon fas fa-sign-out"></i> {{ __('Log Out') }}
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</div>



