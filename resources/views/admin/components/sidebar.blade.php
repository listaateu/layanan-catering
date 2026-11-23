<div class="sidebar-wrapper" id="sidebar">
    <!-- Sidebar Logo -->
    <a href="{{route('admin.dashboard')}}" class="sidebar-brand">
        <i class="bi bi-cup-hot-fill"></i>
        <span>Layanan Catering</span>
    </a>
    <!-- Sidebar Logo -->

    <!-- Sidebar Navigation -->
    <div class="flex-grow-1 overflow-y-auto">
        <div class="sidebar-menu-section">
            <div class="sidebar-menu-title">Menu</div>
            <ul class="sidebar-menu-list">
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-link {{request()->routeIs('admin.dashboard') ? 'active' : ''}}" href="{{route('admin.dashboard')}}">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-link {{request()->routeIs('admin.paket*') ? 'active' : ''}}" href="{{route('admin.paket')}}">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Paket Menu</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-link {{request()->routeIs('admin.pesanan*') ? 'active' : ''}}" href="{{route('admin.pesanan')}}">
                        <i class="bi bi-receipt"></i>
                        <span>Pesanan</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-link {{request()->routeIs('admin.pembayaran*') ? 'active' : ''}}" href="{{route('admin.pembayaran')}}">
                        <i class="bi bi-cash-stack"></i>
                        <span>Pembayaran</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-link {{request()->routeIs('admin.produksi*') ? 'active' : ''}}" href="{{route('admin.produksi')}}">
                        <i class="bi bi-fire"></i>
                        <span>Produksi</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="bi bi-door-open-fill"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Sidebar Navigation -->

    <!-- Sidebar Profile -->
    <div class="sidebar-profile">
        <img src="{{asset('admin/spark/assets/images/avatar.png')}}" alt="Admin" class="sidebar-profile-img">
        <div class="sidebar-profile-info">
            <div class="sidebar-profile-name">{{auth()->user()->nama}}</div>
            <div class="sidebar-profile-email">{{auth()->user()->email}}</div>
        </div>
    </div>
    <!-- Sidebar Profile -->
</div>
