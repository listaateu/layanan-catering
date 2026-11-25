@if (session('success'))
<div class="alert alert-success fade show m-3" role="alert">
    {{session('success')}}
</div>
@endif
@if (session('error'))
<div class="alert alert-danger fade show m-3" role="alert">
    {{session('error')}}
</div>
@endif
{{-- tampilkan semua pesan error validasi (kalau ada) dalam bentuk daftar --}}
@if ($errors->any())
<div class="alert alert-danger fade show m-3" role="alert">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
