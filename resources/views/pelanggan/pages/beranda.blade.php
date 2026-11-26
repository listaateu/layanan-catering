@extends('pelanggan.index')

@section('content')
<!-- Banner -->
<div class="slider_area">
    <div class="single_slider slider_img_1">
        <div class="single_slider-iner">
            <div class="slider_contant">
                <h3>Catering Lezat <br> untuk Setiap Acara</h3>
                <p>Pilih paket menu favoritmu, <br> kami yang menyiapkan dan mengantar.</p>
                <a href="{{route('pelanggan.menu')}}" class="boxed_btn">Lihat Menu</a>
            </div>
        </div>
    </div>
</div>
<!-- Banner -->

<!-- Paket Terbaru -->
<div class="order_area">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="section_title mb-70">
                    <h3>Paket Menu Terbaru</h3>
                    <p>Beberapa pilihan paket yang bisa kamu pesan.</p>
                </div>
            </div>
        </div>
        <div class="row">
            {{-- loop paket, tiap paket ditampilkan lewat potongan kartu-paket --}}
            @forelse ($paket as $item)
            @include('pelanggan.components.kartu-paket', ['item' => $item])
            @empty
            <div class="col-12 text-center">Belum ada paket menu</div>
            @endforelse
        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{route('pelanggan.menu')}}" class="boxed_btn">Lihat Semua Menu</a>
            </div>
        </div>
    </div>
</div>
<!-- Paket Terbaru -->

<!-- Cara Pesan -->
<div class="single_about_area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-5 col-lg-5">
                <div class="single_about_text">
                    <h3>Cara <br> Memesan</h3>
                    <p class="about_text1">1. Pilih paket menu lalu klik Pesan Sekarang (login atau daftar dulu kalau belum punya akun).</p>
                    <p class="about_text2">2. Isi jumlah porsi, tanggal, jam, dan alamat pengiriman.</p>
                    <p class="about_text2">3. Transfer lalu upload bukti pembayaran. Setelah dicek admin, pesananmu langsung dimasak.</p>
                    <a href="{{route('pelanggan.menu')}}" class="boxed_btn">Mulai Pesan</a>
                </div>
            </div>
            <div class="col-xl-6 offset-xl-1 col-lg-6 offset-lg-1">
                <div class="single_about_thumb thumb_n1">
                    <img src="{{asset('flatter-master/img/about/1.png')}}" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cara Pesan -->
@endsection
