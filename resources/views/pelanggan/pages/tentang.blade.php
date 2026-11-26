@extends('pelanggan.index')

@section('content')
<!-- Judul Halaman -->
<div class="breadcam_area breadcam_bg_1 zigzag_bg_2">
    <div class="breadcam_inner">
        <div class="breadcam_text">
            <h3>Tentang Kami</h3>
            <p>Kenali layanan catering kami.</p>
        </div>
    </div>
</div>
<!-- Judul Halaman -->

<div class="single_about_area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-5 col-lg-5">
                <div class="single_about_text">
                    <h3>Hidangan <br> untuk Setiap <br> Acara</h3>
                    <p class="about_text1">
                        Layanan Catering menyediakan berbagai paket menu untuk acara keluarga, kantor, arisan, dan kegiatan lainnya.
                    </p>
                    <p class="about_text2">
                        Semua pesanan dimasak oleh dapur kami sesuai jadwal, lalu diantar tepat waktu ke alamat acaramu.
                    </p>
                    <a href="{{route('pelanggan.menu')}}" class="boxed_btn">Lihat Menu</a>
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
@endsection
