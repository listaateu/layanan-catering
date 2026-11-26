@extends('pelanggan.index')

@section('content')
<!-- Judul Halaman -->
<div class="breadcam_area breadcam_bg_1 zigzag_bg_2">
    <div class="breadcam_inner">
        <div class="breadcam_text">
            <h3>Detail Paket</h3>
            <p>{{$paket->nama_paket}}</p>
        </div>
    </div>
</div>
<!-- Judul Halaman -->

<section class="contact-section">
    <div class="container">
        <div class="row">
            <!-- Gambar -->
            <div class="col-lg-6 mb-4">
                <img src="{{ $paket->gambar_paket ? asset('storage/' . $paket->gambar_paket) : asset('flatter-master/img/order/order-1.png') }}"
                    alt="{{$paket->nama_paket}}" style="width: 100%; max-height: 420px; object-fit: cover;">
            </div>

            <!-- Keterangan -->
            <div class="col-lg-6">
                <h2>{{$paket->nama_paket}}</h2>
                <h4 class="my-3">Rp {{number_format($paket->harga_porsi, 0, ',', '.')}} <small class="text-muted">/ porsi</small></h4>
                <p>Minimal pesan: <strong>{{$paket->min_porsi}} porsi</strong></p>

                <h5 class="mt-4">Isi Paket</h5>
                {{-- deskripsi paket diisi admin dipisah koma, explode() = memecah teks jadi potongan per koma --}}
                <ul class="isi-paket">
                    @foreach (explode(',', $paket->deskripsi) as $isi)
                    <li>{{trim($isi)}}</li>
                    @endforeach
                </ul>

                <div class="mt-4">
                    <a href="{{route('pelanggan.pesanan.create', $paket->id_paket)}}" class="boxed_btn">Pesan Sekarang!</a>
                    <a href="{{route('pelanggan.menu')}}" class="d-inline-block ml-3">Kembali ke menu</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
