<div class="sidebar-wrapper" id="sidebar">
    <!-- Sidebar Logo -->
    <a href="{{route('dapur.dashboard')}}" class="sidebar-brand">
        <i class="bi bi-egg-fried"></i>
        <span>Dapur Catering</span>
    </a>
    <!-- Sidebar Logo -->

    <!-- Sidebar Navigation -->
    <div class="flex-grow-1 overflow-y-auto">
        <div class="sidebar-menu-section">
            <div class="sidebar-menu-title">Menu</div>
            <ul class="sidebar-menu-list">
                {{-- request()->routeIs() = cek halaman yang sedang dibuka, kalau cocok menu diberi class "active" --}}
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-link {{request()->routeIs('dapur.dashboard') ? 'active' : ''}}" href="{{route('dapur.dashboard')}}">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-link {{request()->routeIs('dapur.produksi*') ? 'active' : ''}}" href="{{route('dapur.produksi.table')}}">
                        <i class="bi bi-fire"></i>
                        <span>Produksi</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-link {{request()->routeIs('dapur.riwayat*') ? 'active' : ''}}" href="{{route('dapur.riwayat.table')}}">
                        <i class="bi bi-clock-history"></i>
                        <span>Riwayat</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Sidebar Navigation -->

    <!-- Sidebar Profile -->
    <div class="sidebar-profile">
        <img src="{{asset('admin/spark/assets/images/avatar.png')}}" alt="Kepala Dapur" class="sidebar-profile-img">
        <div class="sidebar-profile-info">
            <div class="sidebar-profile-name">{{auth()->user()->nama}}</div>
            <div class="sidebar-profile-email">{{auth()->user()->email}}</div>
        </div>
    </div>
    <!-- Sidebar Profile -->
</div>
