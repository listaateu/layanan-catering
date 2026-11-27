@extends('pelanggan.index')

@section('content')
<!-- Judul Halaman -->
<div class="breadcam_area breadcam_bg_1 zigzag_bg_2">
    <div class="breadcam_inner">
        <div class="breadcam_text">
            <h3>Detail Pesanan</h3>
            <p>{{$pesanan->kode_order}}</p>
        </div>
    </div>
</div>
<!-- Judul Halaman -->

<section class="contact-section">
    <div class="container">
        <div class="row">
            <!-- Info Pesanan -->
            <div class="col-lg-7 mb-4">
                {{-- kode order dibuat besar, pelanggan tinggal menyebutkannya ke kasir di toko --}}
                <p class="mb-0 text-muted">Kode Pesanan</p>
                <h2 class="mb-3" style="letter-spacing: 2px;">{{$pesanan->kode_order}}</h2>

                <h4>Informasi Pesanan <span class="badge status-{{$pesanan->status_badge}}">{{$pesanan->status_label_pelanggan}}</span></h4>
                <table class="table mt-3">
                    <tr><th style="width: 35%;">Tanggal Acara</th><td>{{$pesanan->tanggal_acara->format('d-m-Y')}}</td></tr>
                    <tr><th>Jam Kirim</th><td>{{substr($pesanan->jam_kirim, 0, 5)}}</td></tr>
                    <tr><th>Alamat Kirim</th><td>{{$pesanan->alamat_kirim}}</td></tr>
                    <tr><th>Total Bayar</th><td>Rp {{number_format($pesanan->total_bayar, 0, ',', '.')}}</td></tr>
                </table>

                <h5 class="mt-4">Isi Pesanan</h5>
                <table class="table">
                    <thead>
                        <tr><th>Paket</th><th>Porsi</th><th>Harga</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($pesanan->items as $detail)
                        <tr>
                            <td>{{$detail->package->nama_paket}}</td>
                            <td>{{$detail->jumlah_porsi}}</td>
                            <td>Rp {{number_format($detail->harga_satuan, 0, ',', '.')}}</td>
                            <td>Rp {{number_format($detail->subtotal, 0, ',', '.')}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- tombol batal hanya muncul selama pesanan belum dibayar, kalau sudah dibayar pembatalan lewat admin di toko --}}
                @if ($pesanan->status_pesanan == 'menunggu_pembayaran')
                {{-- confirm() = kotak tanya "yakin?" bawaan browser, kalau pilih Batal form tidak dikirim --}}
                <form action="{{route('pelanggan.pesanan.batal', $pesanan->id_order)}}" method="post" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">Batalkan Pesanan</button>
                </form>
                @elseif ($pesanan->status_pesanan == 'terkonfirmasi')
                <p class="text-muted">Pesanan sudah dibayar. Kalau ingin membatalkan, silakan datang ke toko dan hubungi admin.</p>
                @endif
            </div>

            <!-- Pembayaran -->
            <div class="col-lg-5">
                <h4>Pembayaran</h4>

                @if ($pesanan->payment && $pesanan->status_pesanan == 'dibatalkan')
                <div class="alert alert-secondary mt-3">
                    Pesanan dibatalkan. Uang tunai yang sudah kamu bayarkan dikembalikan langsung oleh admin di toko.
                </div>
                @elseif ($pesanan->payment)
                <div class="alert alert-success mt-3">
                    Pembayaran tunai sebesar Rp {{number_format($pesanan->payment->jumlah_bayar, 0, ',', '.')}}
                    sudah diterima pada {{$pesanan->payment->tanggal_bayar->format('d-m-Y')}}.
                </div>
                @elseif ($pesanan->status_pesanan == 'menunggu_pembayaran')
                <div class="alert alert-warning mt-3">
                    <strong>Silakan datang ke toko untuk bayar tunai.</strong><br>
                    Sebutkan kode pesanan <strong>{{$pesanan->kode_order}}</strong> ke kasir.<br>
                    Batas bayar paling lambat <strong>{{$pesanan->batas_bayar->format('d-m-Y')}}</strong>.
                    Kalau lewat dari tanggal itu, pesanan otomatis dibatalkan.
                </div>
                @elseif ($pesanan->status_pesanan == 'dibatalkan')
                <p class="mt-3">Pesanan ini sudah dibatalkan.</p>
                @endif

                {{-- info kerja dapur, diambil dari status produksi --}}
                @if ($pesanan->productionSchedule)
                <h4 class="mt-4">Proses Dapur</h4>
                <p class="mt-2">Status: <span class="badge status-{{$pesanan->productionSchedule->status_badge}}">{{$pesanan->productionSchedule->status_label}}</span></p>
                @endif
            </div>
        </div>

        <div class="mt-4">
            <a href="{{route('pelanggan.pesanan.table')}}" class="boxed_btn_white">Kembali</a>
        </div>
    </div>
</section>
@endsection
