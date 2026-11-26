@extends('pelanggan.index')

@section('content')
<!-- Judul Halaman -->
<div class="breadcam_area breadcam_bg_1 zigzag_bg_2">
    <div class="breadcam_inner">
        <div class="breadcam_text">
            <h3>Pesan Sekarang</h3>
            <p>Isi data pesananmu di bawah ini.</p>
        </div>
    </div>
</div>
<!-- Judul Halaman -->

<section class="contact-section">
    <div class="container">
        <div class="row">
            <!-- Ringkasan paket yang dipilih (hanya dibaca, tidak bisa diganti di sini) -->
            <div class="col-lg-5 mb-4">
                <img src="{{ $paket->gambar_paket ? asset('storage/' . $paket->gambar_paket) : asset('flatter-master/img/order/order-1.png') }}"
                    alt="{{$paket->nama_paket}}" style="width: 100%; max-height: 300px; object-fit: cover;">
                <h4 class="mt-3">{{$paket->nama_paket}}</h4>
                <p class="mb-1">Rp {{number_format($paket->harga_porsi, 0, ',', '.')}} / porsi</p>
                <p class="mb-2">Minimal pesan {{$paket->min_porsi}} porsi</p>
                <ul class="isi-paket">
                    @foreach (explode(',', $paket->deskripsi) as $isi)
                    <li>{{trim($isi)}}</li>
                    @endforeach
                </ul>
                <a href="{{route('pelanggan.menu')}}">Pilih paket lain</a>
            </div>

            <!-- Form pesan -->
            <div class="col-lg-7">
                <form action="{{route('pelanggan.pesanan.store')}}" method="post">
                    @csrf

                    {{-- paket dikirim lewat input tersembunyi (hidden), jadi pelanggan tidak perlu memilih lagi --}}
                    <input type="hidden" name="id_paket" value="{{$paket->id_paket}}">

                    <div class="form-group">
                        <label for="jumlah_porsi">Jumlah Porsi <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="jumlah_porsi" id="jumlah_porsi" value="{{old('jumlah_porsi', $paket->min_porsi)}}" min="{{$paket->min_porsi}}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_acara">Tanggal Acara <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_acara" id="tanggal_acara" value="{{old('tanggal_acara')}}" min="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jam_kirim">Jam Kirim <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="jam_kirim" id="jam_kirim" value="{{old('jam_kirim')}}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat_kirim">Alamat Kirim <span class="text-danger">*</span></label>
                        {{-- alamat otomatis diisi dari alamat akunmu, boleh diganti --}}
                        <textarea class="form-control" name="alamat_kirim" id="alamat_kirim" rows="3" required>{{old('alamat_kirim', auth()->user()->alamat)}}</textarea>
                    </div>

                    <p class="text-muted">Total bayar dihitung otomatis: harga per porsi x jumlah porsi.</p>

                    <button type="submit" class="boxed_btn">Buat Pesanan</button>
                    <a href="{{route('pelanggan.menu')}}" class="boxed_btn_white">Batal</a>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
