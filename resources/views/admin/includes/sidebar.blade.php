<div class="main-sidebar sidebar-style-2" tabindex="1">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <span class="logo-name">
                    <img src="{{ asset('admin_assets/img/logo.png') }}" alt="Logo" style="max-height: 50px;" />
                </span>
            </a>
            <a href="{{ route('admin.dashboard') }}">
                <span class="logo-fm">
                    <img class="mb-2" src="{{ asset('admin_assets/img/logo_fm.png') }}" alt="Logo FM" />
                </span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header text-uppercase text-muted small font-weight-bold ml-3 mt-2 mb-1">Main</li>
            <li class="dropdown {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="ph ph-house" style="font-size: 1.2rem;"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0);"
                    class="menu-toggle nav-link has-dropdown {{ Request::is('admin/profile*') || Request::is('admin/password*') || Request::is('admin/detail*') ? 'active' : '' }}">
                    <i class="ph ph-user-gear" style="font-size: 1.2rem;"></i>
                    <span>Manage Account</span>
                </a>
                <ul class="dropdown-menu"
                    style="{{ Request::is('admin/profile*') || Request::is('admin/password*') || Request::is('admin/detail*') ? 'display: block;' : 'display: none;' }}">
                    <li class="{{ Request::is('admin/profile*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.profile') }}">
                            <i class="ph ph-user me-2"></i>My Profile
                        </a>
                    </li>
                    <li class="{{ Request::is('admin/password*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.password') }}">
                            <i class="ph ph-lock-key me-2"></i>Change Password
                        </a>
                    </li>
                </ul>
            </li>

            @can('view-users')
                <li class="dropdown">
                    <a href="javascript:void(0);"
                        class="menu-toggle nav-link has-dropdown {{ Request::is('admin/customers*') ? 'active' : ' ' }}">
                        <i class="ph ph-users" style="font-size: 1.2rem;"></i>
                        <span>User Management</span>
                    </a>
                    <ul class="dropdown-menu"
                        style="{{ Request::is('admin/customers*') ? 'display: block;' : 'display: none;' }}">
                        @can('create-users')
                            <li class="{{ Request::is('admin/customers/create') ? 'active' : ' ' }}">
                                <a class="nav-link" href="{{ route('customers.create') }}">
                                    <i class="ph ph-plus-circle me-2"></i>Create User
                                </a>
                            </li>
                        @endcan
                        <li class="{{ Request::is('admin/customers') ? 'active' : ' ' }}">
                            <a class="nav-link" href="{{ route('customers.index') }}">
                                <i class="ph ph-list-dashes me-2"></i>User List
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

            @can('view-urls')
                <li class="dropdown">
                    <a href="javascript:void(0);"
                        class="menu-toggle nav-link has-dropdown {{ Request::is('admin/url-management*') ? 'active' : ' ' }}">
                        <i class="ph ph-globe" style="font-size: 1.2rem;"></i>
                        <span>URL Management</span>
                    </a>
                    <ul class="dropdown-menu"
                        style="{{ Request::is('admin/url-management*') ? 'display: block;' : 'display: none;' }}">
                        @can('create-urls')
                            <li class="{{ Request::is('admin/url-management/create') ? 'active' : ' ' }}">
                                <a class="nav-link" href="{{ route('url-management.create') }}">
                                    <i class="ph ph-plus-circle me-2"></i>Create URL
                                </a>
                            </li>
                        @endcan
                        <li
                            class="{{ Request::is('admin/url-management') || Request::is('admin/url-management') ? 'active' : ' ' }}">
                            <a class="nav-link" href="{{ route('url-management.index') }}">
                                <i class="ph ph-list-dashes me-2"></i>URL List
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            @can('view-roles')
                <li class="dropdown">
                    <a href="javascript:void(0);"
                        class="menu-toggle nav-link has-dropdown {{ Request::is('admin/roles*') || Request::is('admin/permissions*') ? 'active' : ' ' }}">
                        <i class="ph ph-shield-check" style="font-size: 1.2rem;"></i>
                        <span>Roles & Permissions</span>
                    </a>
                    <ul class="dropdown-menu"
                        style="{{ Request::is('admin/roles*') || Request::is('admin/permissions*') ? 'display: block;' : 'display: none;' }}">

                        <li class="menu-header small text-muted ml-3">Roles</li>
                        {{-- @can('create-roles')
                            <li class="{{ Request::is('admin/roles/create') ? 'active' : ' ' }}">
                                <a class="nav-link" href="{{ route('roles.create') }}">
                                    <i class="ph ph-plus-circle me-2"></i>Create Role
                                </a>
                            </li>
                        @endcan --}}
                        <li
                            class="{{ Request::is('admin/roles') && !Request::is('admin/roles/create') ? 'active' : ' ' }}">
                            <a class="nav-link" href="{{ route('roles.index') }}">
                                <i class="ph ph-list-dashes me-2"></i>Role List
                            </a>
                        </li>

                        {{-- @can('view-permissions')
                            <li class="menu-header small text-muted ml-3">Permissions</li>
                            @can('create-permissions')
                                <li class="{{ Request::is('admin/permissions/create') ? 'active' : ' ' }}">
                                    <a class="nav-link" href="{{ route('permissions.create') }}">
                                        <i class="ph ph-plus-circle me-2"></i>Create Permission
                                    </a>
                                </li>
                            @endcan
                            <li
                                class="{{ Request::is('admin/permissions') && !Request::is('admin/permissions/create') ? 'active' : ' ' }}">
                                <a class="nav-link" href="{{ route('permissions.index') }}">
                                    <i class="ph ph-list-dashes me-2"></i>Permission List
                                </a>
                            </li>
                        @endcan --}}
                    </ul>
                </li>
            @endcan
        </ul>
    </aside>
</div>
