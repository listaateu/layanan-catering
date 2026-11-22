<!doctype html>
<html lang="en">

<!-- Logout Modal -->
@include('admin.components.logout-modal')
<!-- Logout Modal -->

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

    <!-- Script Section -->
    @include('admin.partials.script')
    <!-- Script Section -->
</body>

</html>
