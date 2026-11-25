{{-- Ini layout utama untuk halaman kepala dapur (strukturnya sama dengan admin/index.blade.php) --}}
{{-- Semua halaman kepala dapur memakai @extends('dapur.index') --}}
{{-- @include = menyisipkan potongan template (partials/components) ke sini --}}
{{-- @yield('content') = tempat isi halaman yang berbeda-beda (diisi lewat @section('content') di tiap halaman) --}}
<!doctype html>
<html lang="id">
<!-- Head Section -->
@include('dapur.partials.head')
<!-- Head Section -->

<body>
    <!-- Sidebar -->
    @include('dapur.components.sidebar')
    <!-- Sidebar -->
    <!-- Main Content -->
    <div class="main-wrapper">
        <!-- Navbar -->
        @include('dapur.components.navbar')
        <!-- Navbar -->
        <!-- Alert -->
        @include('dapur.components.alert')
        <!-- Alert -->
        <!-- Main Content -->
        @yield('content')
        <!-- Main Content -->
        <!-- Footer Section -->
        @include('dapur.components.footer')
        <!-- Footer Section -->
    </div>
    <!-- Main Content -->

    <!-- Logout Modal -->
    @include('dapur.components.logout-modal')
    <!-- Logout Modal -->

    <!-- Script Section -->
    @include('dapur.partials.script')
    <!-- Script Section -->
</body>

</html>
