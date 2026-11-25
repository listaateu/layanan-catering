@extends('admin.index')

@section('content')
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Detail Pesanan</h1>
            <p class="page-subtitle">{{$pesanan->kode_order}}</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-decoration-none text-muted-green">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.pesanan.table')}}" class="text-decoration-none text-muted-green">Pesanan</a></li>
                <li class="breadcrumb-item active text-main" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4 mb-4">
        <!-- Info Pesanan -->
        <div class="col-12 col-lg-7">
            <div class="card border-light shadow-sm p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">Informasi Pesanan</h5>
                    <span class="badge-table {{$pesanan->status_badge}}">{{$pesanan->status_label}}</span>
                </div>
                <div class="mb-3">
                    <label class="form-label-custom">Pelanggan</label>
                    <input type="text" class="form-control-custom" value="{{$pesanan->customer->nama}} ({{$pesanan->customer->nomor_telepon}})" readonly>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Tanggal Acara</label>
                        <input type="text" class="form-control-custom" value="{{$pesanan->tanggal_acara->format('d-m-Y')}}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Jam Kirim</label>
                        <input type="text" class="form-control-custom" value="{{substr($pesanan->jam_kirim, 0, 5)}}" readonly>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label-custom">Alamat Kirim</label>
                    <textarea class="form-control-custom" rows="3" readonly>{{$pesanan->alamat_kirim}}</textarea>
                </div>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="col-12 col-lg-5">
            <div class="card border-light shadow-sm p-4 h-100">
                <h5 class="card-title mb-4">Ringkasan</h5>
                <div class="mb-3">
                    <label class="form-label-custom">Total Bayar</label>
                    <input type="text" class="form-control-custom" value="Rp {{number_format($pesanan->total_bayar, 0, ',', '.')}}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label-custom">Pembayaran</label>
                    @if ($pesanan->payment)
                    <div>
                        <span class="badge-table {{ $pesanan->payment->status_verifikasi == 'diterima' ? 'success' : ($pesanan->payment->status_verifikasi == 'ditolak' ? 'failed' : 'pending') }}">
                            {{ucfirst($pesanan->payment->status_verifikasi)}}
                        </span>
                        <a href="{{ $pesanan->payment->status_verifikasi == 'menunggu' ? route('admin.pembayaran.validasi', $pesanan->payment->id_payment) : route('admin.pembayaran.show', $pesanan->payment->id_payment) }}" class="ms-2 text-decoration-none">Lihat bukti</a>
                    </div>
                    @else
                    <input type="text" class="form-control-custom" value="Belum ada bukti transfer" readonly>
                    @endif
                </div>
                <div class="mb-4">
                    <label class="form-label-custom">Produksi</label>
                    <input type="text" class="form-control-custom"
                        value="{{ $pesanan->productionSchedule ? ucwords(str_replace('_', ' ', $pesanan->productionSchedule->status_produksi)) : 'Belum masuk antrean' }}" readonly>
                </div>
                @if (in_array($pesanan->status_pesanan, ['menunggu_pembayaran', 'menunggu_verifikasi', 'terkonfirmasi']))
                <form action="{{route('admin.pesanan.batal', $pesanan->id_order)}}" method="post" onsubmit="return confirm('Pesanan akan dibatalkan dan tidak bisa diproses lagi. Apakah anda yakin?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn-custom btn-custom-outline-danger">
                        <i class="bi bi-x-circle"></i> Batalkan Pesanan
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Item Pesanan -->
    <div class="table-card-custom">
        <div class="table-header-control">
            <h5 class="mb-0">Item Pesanan</h5>
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Paket</th>
                        <th>Jumlah Porsi</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesanan->items as $item)
                    <tr>
                        <td class="table-product-name">{{$item->package->nama_paket}}</td>
                        <td>{{$item->jumlah_porsi}} porsi</td>
                        <td>Rp {{number_format($item->harga_satuan, 0, ',', '.')}}</td>
                        <td class="table-amount">Rp {{number_format($item->subtotal, 0, ',', '.')}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-footer-control">
            <a href="{{route('admin.pesanan.table')}}" class="btn-custom btn-custom-light"><i class="bi bi-arrow-left"></i> Kembali</a>
            <strong>Total: Rp {{number_format($pesanan->total_bayar, 0, ',', '.')}}</strong>
        </div>
    </div>
</div>
@endsection
