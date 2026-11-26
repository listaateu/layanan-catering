@extends('pelanggan.index')

@section('content')
<!-- Judul Halaman -->
<div class="breadcam_area breadcam_bg_1 zigzag_bg_2">
    <div class="breadcam_inner">
        <div class="breadcam_text">
            <h3>Menu Kami</h3>
            <p>Pilih paket yang kamu mau, lalu klik Pesan Sekarang.</p>
        </div>
    </div>
</div>
<!-- Judul Halaman -->

<!-- Daftar Paket -->
<div class="order_area">
    <div class="container">
        <div class="row">
            {{-- @forelse = perulangan, kalau datanya kosong tampilkan bagian @empty --}}
            @forelse ($paket as $item)
            @include('pelanggan.components.kartu-paket', ['item' => $item])
            @empty
            <div class="col-12 text-center">Belum ada paket menu</div>
            @endforelse
        </div>
    </div>
</div>
<!-- Daftar Paket -->
@endsection
