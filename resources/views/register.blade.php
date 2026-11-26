@include('admin.partials.head')
<style>
    :root {
        --playfair: "Playfair Display";
        --mulish: "Mulish";

        --berry: #8c3b55;
        --rose: #d9728f;
        --soft-pink: #f4b8c8;
        --blush: #fde4ec;
        --cream: #fff8fa;
    }

    body {
        font-family: var(--mulish), sans-serif;
        background-color: var(--cream);
    }

    /* ===== Sisi kiri: foto catering ===== */
    .foto {
        min-height: 100vh;
        background-color: var(--berry);
        /* lapisan gelap di atas foto supaya tulisan putih tetap terbaca */
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.55), rgba(60, 15, 35, 0.8)),
            url('{{ asset("admin/spark/assets/images/login-catering.jpg") }}');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 48px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .foto-brand {
        font-family: var(--playfair), serif;
        font-size: 22px;
        font-weight: 700;
    }

    .foto-judul {
        font-family: var(--playfair), serif;
        font-size: 40px;
        font-weight: 700;
        line-height: 1.25;
        max-width: 460px;
        margin-bottom: 12px;
    }

    .foto-sub {
        font-size: 15px;
        max-width: 420px;
        opacity: 0.9;
        margin-bottom: 0;
    }

    /* ===== Sisi kanan: form ===== */
    .sisi-form {
        min-height: 100vh;
        padding: 24px;
    }

    .login {
        width: 100%;
        max-width: 380px;
    }

    .logo {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--soft-pink), var(--rose));
        color: white;
        font-size: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 20px rgba(217, 114, 143, 0.35);
    }

    .judul {
        font-family: var(--playfair), serif;
        color: var(--berry);
        font-weight: 700;
        font-size: 28px;
    }

    .sub {
        color: var(--rose);
        font-size: 14px;
    }

    .input-group-text {
        background-color: var(--blush);
        border: 2px solid var(--soft-pink);
        border-right: none;
        color: var(--rose);
        border-radius: 15px 0 0 15px;
    }

    .form-control {
        background-color: var(--cream);
        border: 2px solid var(--soft-pink);
        border-left: none;
        border-radius: 0 15px 15px 0;
        color: var(--berry);
        font-size: 13px;
        height: 3rem;
    }

    .form-control::placeholder {
        color: #d9a0b3;
        font-size: 13px;
    }

    .form-control:focus {
        background-color: var(--cream);
        border-color: var(--rose);
        box-shadow: none;
        color: var(--berry);
    }

    .input-group:focus-within .input-group-text {
        border-color: var(--rose);
    }

    /* pesan error di bawah input */
    .pesan-error {
        color: #b3261e;
        font-size: 12px;
        margin: -8px 0 12px 4px;
    }

    .alert-danger,
    .alert-success {
        background-color: var(--blush);
        border: 1px solid var(--rose);
        color: var(--berry);
        font-size: 13px;
        border-radius: 12px;
        margin: 0 0 12px 0 !important;
        padding: 8px 14px;
    }

    .btn {
        background-color: var(--rose);
        height: 3rem;
        font-size: 15px;
        font-weight: 600;
        border-radius: 15px;
        color: white;
        border: 2px solid var(--rose);
    }

    .btn:hover {
        background-color: var(--berry);
        border: 2px solid var(--berry);
        color: white;
    }

    .footer-text {
        color: var(--rose);
        font-size: 12px;
    }
</style>

<body>
    <div class="row g-0 min-vh-100">
        <!-- Kiri: foto (disembunyikan di layar kecil) -->
        <div class="col-lg-7 d-none d-lg-flex foto">
            <div class="foto-brand">
                <i class="bi bi-cup-hot-fill me-2"></i>Layanan Catering
            </div>
            <div>
                <h2 class="foto-judul">Hidangan untuk setiap acara, tertata dalam satu tempat.</h2>
                <p class="foto-sub">Buat akun, pilih paket menu, dan pesan catering dengan mudah.</p>
            </div>
        </div>
        <!-- Kiri -->

        <!-- Kanan: form login -->
        <div class="col-lg-5 d-flex align-items-center justify-content-center sisi-form">
            <div class="login">
                <div class="logo bi bi-cup-hot-fill d-lg-none mb-3"></div>
                <h1 class="judul mb-1">Buat Akun</h1>
                <p class="sub mb-4">Isi data dirimu untuk mulai memesan</p>

                {{-- semua field ini disimpan ke tabel users, role otomatis pelanggan --}}
                <form id="content" action="{{ route('register.store') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <span class="input-group-text bi bi-person"></span>
                        <input class="form form-control" type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text bi bi-envelope"></span>
                        <input class="form form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Email">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text bi bi-telephone"></span>
                        <input class="form form-control" type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}" placeholder="Nomor telepon">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text bi bi-geo-alt"></span>
                        <input class="form form-control" type="text" name="alamat" value="{{ old('alamat') }}" placeholder="Alamat">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text bi bi-lock"></span>
                        <input class="form form-control" type="password" name="password" placeholder="Password (minimal 8 karakter)">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text bi bi-lock-fill"></span>
                        <input class="form form-control" type="password" name="password_confirmation" placeholder="Ulangi password">
                    </div>

                    @include('admin.components.alert')
                    <button type="submit" class="btn w-100 mt-2 bi bi-person-plus"><span class="mx-2">Daftar</span></button>
                </form>

                <p class="text-center mt-3 mb-0 small">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
                <p class="text-center mt-1 mb-0 small"><a href="{{ route('pelanggan.beranda') }}">Kembali ke beranda</a></p>

                <p class="footer-text text-center mt-4 mb-0">&copy; {{ date('Y') }} Layanan Catering</p>
            </div>
        </div>
        <!-- Kanan -->
    </div>
    @include('admin.partials.script')
</body>
