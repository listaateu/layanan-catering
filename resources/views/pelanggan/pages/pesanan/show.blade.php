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
                <h4>Informasi Pesanan <span class="badge status-{{$pesanan->status_badge}}">{{$pesanan->status_label}}</span></h4>
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

                {{-- tombol batal hanya muncul kalau pesanan belum dimasak --}}
                @if (in_array($pesanan->status_pesanan, ['menunggu_pembayaran', 'menunggu_verifikasi', 'terkonfirmasi']))
                {{-- confirm() = kotak tanya "yakin?" bawaan browser, kalau pilih Batal form tidak dikirim --}}
                <form action="{{route('pelanggan.pesanan.batal', $pesanan->id_order)}}" method="post" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini? Pengembalian dana diurus langsung dengan admin.')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">Batalkan Pesanan</button>
                </form>
                @endif
            </div>

            <!-- Pembayaran -->
            <div class="col-lg-5">
                <h4>Pembayaran</h4>

                @if ($pesanan->payment)
                <p class="mt-3 mb-1">
                    Status bukti transfer:
                    <span class="badge {{ $pesanan->payment->status_verifikasi == 'diterima' ? 'status-success' : ($pesanan->payment->status_verifikasi == 'ditolak' ? 'status-failed' : 'status-pending') }}">{{ucfirst($pesanan->payment->status_verifikasi)}}</span>
                </p>
                <p class="mb-3">Jumlah transfer: Rp {{number_format($pesanan->payment->jumlah_transfer, 0, ',', '.')}}</p>
                @if ($pesanan->payment->status_verifikasi == 'ditolak' && $pesanan->status_pesanan == 'menunggu_pembayaran')
                <div class="alert alert-warning">Bukti transfer sebelumnya ditolak admin. Silakan upload ulang bukti yang benar.</div>
                @endif
                @endif

                {{-- form upload hanya muncul kalau pesanan masih menunggu pembayaran --}}
                @if ($pesanan->status_pesanan == 'menunggu_pembayaran')
                {{-- enctype="multipart/form-data" wajib ada kalau form mengirim file --}}
                <form action="{{route('pelanggan.pembayaran.store', $pesanan->id_order)}}" method="post" enctype="multipart/form-data" class="mt-3">
                    @csrf

                    <div class="form-group">
                        <label for="jumlah_transfer">Jumlah Transfer <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="jumlah_transfer" id="jumlah_transfer" value="{{old('jumlah_transfer', (int) $pesanan->total_bayar)}}" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="bukti_transfer">Bukti Transfer <span class="text-danger">*</span></label>
                        <input type="file" class="form-control-file" name="bukti_transfer" id="bukti_transfer" accept="image/*" required>
                        <small class="text-muted">Format JPG atau PNG, maksimal 2 MB.</small>
                    </div>

                    <button type="submit" class="boxed_btn">Kirim Bukti</button>
                </form>
                @elseif ($pesanan->status_pesanan == 'menunggu_verifikasi')
                <p class="mt-3">Bukti transfermu sedang dicek admin.</p>
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
