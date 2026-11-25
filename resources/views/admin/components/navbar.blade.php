<header class="navbar-custom">
    <div class="navbar-left">
        <!-- Toggle Desktop -->
        <button class="btn-desktop-toggle d-none d-xl-flex align-items-center justify-content-center me-3"
            id="desktop-sidebar-toggle" aria-label="Minimize Sidebar">
            <i class="bi bi-chevron-bar-left"></i>
        </button>
        <!-- Toggle Mobile -->
        <button class="sidebar-toggle-btn me-2" id="sidebar-toggle" aria-label="Toggle Navigation">
            <i class="bi bi-list"></i>
        </button>
    </div>

    {{-- navbar ini memakai grid 3 kolom, grid-column: 3 = taruh profil di kolom paling kanan --}}
    <div class="navbar-actions ms-auto" style="grid-column: 3;">
        <!-- Profile -->
        <div class="dropdown ms-2">
            <button class="navbar-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false" id="profile-dropdown">
                <img src="{{ asset('admin/spark/assets/images/avatar.png') }}" alt="Profile"
                    class="navbar-profile-img">
                <span class="navbar-profile-name d-none d-md-inline">{{ auth()->user()->nama }}</span>
                <i class="bi bi-chevron-down navbar-profile-caret"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profile" aria-labelledby="profile-dropdown">
                <li class="dropdown-header">Admin Kasir</li>
                <li>
                    <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                        data-bs-target="#logoutModal"> <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
