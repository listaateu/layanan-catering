<!-- header-start -->
<header>
    <div class="header-area ">
        <div id="sticky-header" class="main-header-area">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-10 col-lg-10">
                        <div class="main-menu d-none d-lg-block">
                            <nav>
                                <ul class="mein_menu_list" id="navigation">
                                    <li><a href="{{route('pelanggan.beranda')}}" class="{{ request()->routeIs('pelanggan.beranda') ? 'active' : '' }}">Beranda</a></li>
                                    <li><a href="{{route('pelanggan.menu')}}" class="{{ request()->routeIs('pelanggan.menu*') ? 'active' : '' }}">Menu</a></li>
                                    <li><a href="{{route('pelanggan.tentang')}}" class="{{ request()->routeIs('pelanggan.tentang') ? 'active' : '' }}">Tentang</a></li>
                                    <div class="logo-img d-none d-lg-block">
                                        <a href="{{route('pelanggan.beranda')}}">
                                            <img src="{{asset('flatter-master/img/logo.png')}}" alt="">
                                        </a>
                                    </div>
                                    <li><a href="{{route('pelanggan.kontak')}}" class="{{ request()->routeIs('pelanggan.kontak') ? 'active' : '' }}">Kontak</a></li>
                                    {{-- menu Pesanan Saya hanya muncul kalau sudah login --}}
                                    @if (auth()->check())
                                    <li><a href="{{route('pelanggan.pesanan.table')}}" class="{{ request()->routeIs('pelanggan.pesanan*') ? 'active' : '' }}">Pesanan Saya</a></li>
                                    @endif
                                    {{-- Login / Logout untuk layar HP (di layar besar tombolnya ada di kanan atas) --}}
                                    <li class="d-lg-none">
                                        @if (auth()->check())
                                        <form action="{{route('logout')}}" method="post" class="m-0" onsubmit="return confirm('Yakin ingin logout?')">
                                            @csrf
                                            <button type="submit" class="logout-btn border-0 p-0 text-white">Logout</button>
                                        </form>
                                        @else
                                        <a href="{{route('login')}}">Login</a>
                                        @endif
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 d-none d-lg-block">
                        <div class="custom_order">
                            {{-- belum login: tombol Login | sudah login: tombol Logout (harus form POST + @csrf) --}}
                            @if (auth()->check())
                            {{-- confirm() = kotak tanya "yakin?" bawaan browser, kalau pilih Cancel form tidak dikirim (tidak jadi logout) --}}
                            <form action="{{route('logout')}}" method="post" class="m-0" onsubmit="return confirm('Yakin ingin logout?')">
                                @csrf
                                <button type="submit" class="boxed_btn_white logout-btn">Logout</button>
                            </form>
                            @else
                            <a href="{{route('login')}}" class="boxed_btn_white">Login</a>
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                    <div class="logo-img-small d-sm-block d-md-block d-lg-none">
                        <a href="{{route('pelanggan.beranda')}}">
                            <img src="{{asset('flatter-master/img/logo.png')}}" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header-end -->
