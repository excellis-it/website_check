


<div class="main-sidebar sidebar-style-2" tabindex="1">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}"><span class="logo-name"><img
                        src="{{ asset('admin_assets/img/logo.png') }}" /></span> </a>
            <a href="{{ route('admin.dashboard') }}"><span class="logo-fm "><img class="mb-2"
                        src="{{ asset('admin_assets/img/logo_fm.png') }}" /></span> </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0);"
                    class="menu-toggle nav-link has-dropdown {{ Request::is('admin/profile*') || Request::is('admin/password*') || Request::is('admin/detail*') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    <span>Manage Account</span>
                </a>
                <ul class="dropdown-menu"
                    style="{{ Request::is('admin/profile*') || Request::is('admin/password*') || Request::is('admin/detail*') ? 'display: block;' : 'display: none;' }}">
                    <li class="{{ Request::is('admin/profile*') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin.profile') }}"><i class="fas fa-user"></i> My Profile</a></li>
                    <li class="{{ Request::is('admin/password*') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ route('admin.password') }}"><i class="fas fa-lock"></i> Change Password</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0);" class="menu-toggle nav-link has-dropdown {{ Request::is('admin/customers*') ? 'active' : ' ' }}">
                    <i class="fas fa-users"></i>
                    <span> User Management</span>
                </a>
                <ul class="dropdown-menu" style="{{ Request::is('admin/customers*') ? 'display: block;' : 'display: none;' }}">
                    <li class="{{ Request::is('admin/customers/create') ? 'active' : ' ' }}"><a class="nav-link" href="{{ route('customers.create') }}">Create  User</a></li>
                    <li class="{{ Request::is('admin/customers') ? 'active' : ' ' }}"><a class="nav-link" href="{{ route('customers.index') }}"> User List</a></li>
                </ul>
            </li>
        </ul>
    </aside>
</div>
