@extends('admin.index')

@section('content')
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Terima Pembayaran Tunai</h1>
            <p class="page-subtitle">{{$pesanan->kode_order}}</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-decoration-none text-muted-green">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.pembayaran.table')}}" class="text-decoration-none text-muted-green">Pembayaran</a></li>
                <li class="breadcrumb-item active text-main" aria-current="page">Terima</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4 mb-4">
        <!-- Ringkasan pesanan -->
        <div class="col-12 col-lg-5">
            <div class="card border-light shadow-sm p-4 h-100">
                <h5 class="card-title mb-4">Ringkasan Pesanan</h5>
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
                        <label class="form-label-custom">Batas Bayar</label>
                        <input type="text" class="form-control-custom" value="{{$pesanan->batas_bayar->format('d-m-Y')}}" readonly>
                    </div>
                </div>

                <h6 class="mt-2 mb-2">Isi Pesanan</h6>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Paket</th>
                                <th>Porsi</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesanan->items as $item)
                            <tr>
                                <td>{{$item->package->nama_paket}}</td>
                                <td>{{$item->jumlah_porsi}}</td>
                                <td>Rp {{number_format($item->subtotal, 0, ',', '.')}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form terima uang -->
        <div class="col-12 col-lg-7">
            <div class="card border-light shadow-sm p-4 h-100">
                <h5 class="card-title mb-4">Pembayaran Tunai</h5>
                <div class="alert alert-warning">
                    Pastikan uang yang diterima pas <strong>Rp {{number_format($pesanan->total_bayar, 0, ',', '.')}}</strong>.
                    Setelah disimpan, pesanan langsung masuk antrean dapur.
                </div>

                <form action="{{route('admin.pembayaran.store', $pesanan->id_order)}}" method="post">
                    @csrf

                    <div class="mb-3">
                        <label for="jumlah_bayar" class="form-label-custom">Jumlah Uang Diterima <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-group-text-custom">Rp</span>
                            <input type="number" class="form-control-custom" name="jumlah_bayar" id="jumlah_bayar"
                                value="{{old('jumlah_bayar', (int) $pesanan->total_bayar)}}" min="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        {{-- tanggal bayar otomatis hari ini, kasir tinggal ubah kalau perlu --}}
                        <label for="tanggal_bayar" class="form-label-custom">Tanggal Bayar <span class="text-danger">*</span></label>
                        <input type="date" class="form-control-custom" name="tanggal_bayar" id="tanggal_bayar"
                            value="{{old('tanggal_bayar', date('Y-m-d'))}}" max="{{date('Y-m-d')}}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Diterima Oleh</label>
                        <input type="text" class="form-control-custom" value="{{auth()->user()->nama}}" readonly>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-custom btn-custom-primary">
                            <i class="bi bi-check-lg"></i> Simpan Pembayaran
                        </button>
                        <a href="{{route('admin.pembayaran.table')}}" class="btn-custom btn-custom-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
