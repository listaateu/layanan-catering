<!DOCTYPE html>
<html lang="id">
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
        min-height: 100vh;
        display: flex;
        align-items: center;
        font-family: var(--mulish), sans-serif;
        background: linear-gradient(135deg, #fff1f5 0%, #fde4ec 50%, #f9d3df 100%);
        position: relative;
        overflow-x: hidden;
    }

    body::before,
    body::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        z-index: 0;
    }

    body::before {
        width: 320px;
        height: 320px;
        background: rgba(244, 184, 200, 0.45);
        top: -90px;
        left: -90px;
    }

    body::after {
        width: 260px;
        height: 260px;
        background: rgba(217, 114, 143, 0.22);
        bottom: -80px;
        right: -70px;
    }

    .wrapper {
        position: relative;
        z-index: 1;
        width: 100%;
    }

    .login {
        background-color: var(--cream);
        border: 2px solid var(--soft-pink);
        box-shadow: 0 20px 45px rgba(217, 114, 143, 0.25);
    }

    .logo {
        width: 76px;
        height: 76px;
        margin: 0 auto;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--soft-pink), var(--rose));
        color: white;
        font-size: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 20px rgba(217, 114, 143, 0.35);
    }

    .judul {
        font-family: var(--playfair), serif;
        color: var(--berry);
        font-weight: 700;
        font-size: 26px;
    }

    .sub {
        color: var(--rose);
        font-size: 13px;
    }

    form {
        padding: 5px;
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
    <div class="wrapper">
        <div class="col-md-4 col-sm-8 p-3 mx-auto align-content-center">
            <div class="login p-4 rounded rounded-5">
                <div class="text-center mt-2 mb-4">
                    <div class="logo bi bi-cup-hot-fill"></div>
                    <h1 class="judul mt-3 mb-1">Layanan Catering</h1>
                    <p class="sub mb-0">Silakan masuk untuk melanjutkan</p>
                </div>

                <form id="content" action="{{ route('authenticate') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <span class="input-group-text bi bi-envelope"></span>
                        <input class="form form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Email">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text bi bi-lock"></span>
                        <input class="form form-control" type="password" name="password" placeholder="Password">
                    </div>
                    @include('admin.components.alert')
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn w-100 mt-2 bi bi-box-arrow-in-right"><span class="mx-2">Sign In</span></button>
                    </div>
                </form>

                <p class="footer-text text-center mt-4 mb-0">&copy; {{ date('Y') }} Layanan Catering</p>
            </div>
        </div>
    </div>
</body>
</html>
