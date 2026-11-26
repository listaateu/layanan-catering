{{-- Ini layout utama untuk halaman pelanggan (potongan dari template Flatter) --}}
{{-- Semua halaman pelanggan memakai @extends('pelanggan.index') --}}
{{-- @include = menyisipkan potongan template (partials/components) ke sini --}}
{{-- @yield('content') = tempat isi halaman yang berbeda-beda (diisi lewat @section('content') di tiap halaman) --}}
<!doctype html>
<html class="no-js" lang="id">
<!-- Head Section -->
@include('pelanggan.partials.head')
<!-- Head Section -->

<body>
    <!-- Navbar -->
    @include('pelanggan.components.navbar')
    <!-- Navbar -->

    <!-- Alert -->
    @include('pelanggan.components.alert')
    <!-- Alert -->

    <!-- Main Content -->
    @yield('content')
    <!-- Main Content -->

    <!-- Footer Section -->
    @include('pelanggan.components.footer')
    <!-- Footer Section -->

    <!-- Script Section -->
    @include('pelanggan.partials.script')
    <!-- Script Section -->
</body>

</html>
