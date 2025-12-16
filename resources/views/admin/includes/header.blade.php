<nav class="navbar navbar-expand-lg main-navbar sticky"
    style="background-color: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li>
                <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn text-dark">
                    <i class="ph ph-list" style="font-size: 1.5rem;"></i>
                </a>
            </li>
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user text-dark d-flex align-items-center">
                @if (Auth::user()->profile_picture)
                    <img alt="image" src="{{ Storage::url(Auth::user()->profile_picture) }}"
                        class="user-img-radious-style rounded-circle shadow-sm"
                        style="width: 35px; height: 35px; object-fit: cover; margin-right: 10px;" />
                @else
                    <img alt="image" src="{{ asset('admin_assets/img/profile_dummy.png') }}"
                        class="user-img-radious-style rounded-circle shadow-sm"
                        style="width: 35px; height: 35px; object-fit: cover; margin-right: 10px;" />
                @endif
                <span class="d-none d-lg-inline-block font-weight-bold">{{ Auth::user()->name }}</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right pullDown shadow-lg border-0">
                <div class="dropdown-title px-3 py-2 text-muted small text-uppercase font-weight-bold">
                    Logged in as <br>
                    <span class="text-dark" style="font-size: 1rem;">{{ Auth::user()->name }}</span>
                </div>
                <div class="dropdown-divider"></div>

                <!-- Profile -->
                <a href="{{ route('admin.profile') }}" class="dropdown-item has-icon py-2">
                    <i class="ph ph-user mr-2 text-primary"></i> Profile
                </a>

                <!-- Change Password -->
                <a href="{{ route('admin.password') }}" class="dropdown-item has-icon py-2">
                    <i class="ph ph-key mr-2 text-warning"></i> Change Password
                </a>

                <div class="dropdown-divider"></div>

                <!-- Logout -->
                <a href="{{ route('admin.logout') }}" class="dropdown-item has-icon text-danger py-2">
                    <i class="ph ph-sign-out mr-2"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
