@extends('admin.index')

@section('content')
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Detail Pembayaran</h1>
            <p class="page-subtitle">{{$pembayaran->order->kode_order}}</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-decoration-none text-muted-green">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.pembayaran.table')}}" class="text-decoration-none text-muted-green">Pembayaran</a></li>
                <li class="breadcrumb-item active text-main" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-lg-8">
            <div class="card border-light shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">Pembayaran Tunai</h5>
                    {{-- kalau pesanan dibatalkan setelah dibayar, kasir diingatkan uangnya perlu dikembalikan --}}
                    @if ($pembayaran->order->status_pesanan == 'dibatalkan')
                    <span class="badge-table failed">Pesanan Dibatalkan</span>
                    @else
                    <span class="badge-table success">Lunas</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Pelanggan</label>
                    <input type="text" class="form-control-custom" value="{{$pembayaran->order->customer->nama}}" readonly>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Total Tagihan</label>
                        <input type="text" class="form-control-custom" value="Rp {{number_format($pembayaran->order->total_bayar, 0, ',', '.')}}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Jumlah Bayar</label>
                        <input type="text" class="form-control-custom" value="Rp {{number_format($pembayaran->jumlah_bayar, 0, ',', '.')}}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Diterima Oleh</label>
                        <input type="text" class="form-control-custom" value="{{$pembayaran->verifier->nama ?? '-'}}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Tanggal Bayar</label>
                        <input type="text" class="form-control-custom" value="{{$pembayaran->tanggal_bayar->format('d-m-Y')}}" readonly>
                    </div>
                </div>

                <h6 class="mt-2 mb-2">Isi Pesanan</h6>
                <div class="table-responsive mb-3">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Paket</th>
                                <th>Porsi</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pembayaran->order->items as $item)
                            <tr>
                                <td>{{$item->package->nama_paket}}</td>
                                <td>{{$item->jumlah_porsi}}</td>
                                <td>Rp {{number_format($item->subtotal, 0, ',', '.')}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{route('admin.pembayaran.table')}}" class="btn-custom btn-custom-light">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
