{{-- Ini layout utama admin. Semua halaman admin memakai @extends('admin.index') --}}
{{-- @include = menyisipkan potongan template (partials/components) ke sini --}}
{{-- @yield('content') = tempat isi halaman yang berbeda-beda (diisi lewat @section('content') di tiap halaman) --}}
<!doctype html>
<html lang="id">
<!-- Head Section -->
@include('admin.partials.head')
<!-- Head Section -->

<body>
    <!-- Sidebar -->
    @include('admin.components.sidebar')
    <!-- Sidebar -->
    <!-- Main Content -->
    <div class="main-wrapper">
        <!-- Navbar -->
        @include('admin.components.navbar')
        <!-- Navbar -->
        <!-- Alert -->
        @include('admin.components.alert')
        <!-- Alert -->
        <!-- Main Content -->
        @yield('content')
        <!-- Main Content -->
        <!-- Footer Section -->
        @include('admin.components.footer')
        <!-- Footer Section -->
    </div>
    <!-- Main Content -->

    <!-- Logout Modal -->
    @include('admin.components.logout-modal')
    <!-- Logout Modal -->

    <!-- Script Section -->
    @include('admin.partials.script')
    <!-- Script Section -->
</body>

</html>
