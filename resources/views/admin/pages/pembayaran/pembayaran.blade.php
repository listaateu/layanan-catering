@extends('admin.index')

@section('content')
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Pembayaran</h1>
            <p class="page-subtitle">Terima pembayaran tunai pelanggan yang datang ke toko.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-decoration-none text-muted-green">Home</a></li>
                <li class="breadcrumb-item active text-main" aria-current="page">Pembayaran</li>
            </ol>
        </nav>
    </div>

    {{-- pencarian kode order: kasir tinggal ketik kode yang disebutkan pelanggan --}}
    <form action="{{route('admin.pembayaran.table')}}" method="get" class="d-flex gap-2 mb-4">
        <input type="text" class="form-control-custom" name="cari" value="{{$cari}}" placeholder="Cari kode order, contoh: ORD-20261127-001">
        <button type="submit" class="btn-custom btn-custom-primary"><i class="bi bi-search"></i> Cari</button>
        @if ($cari)
        <a href="{{route('admin.pembayaran.table')}}" class="btn-custom btn-custom-light">Reset</a>
        @endif
    </form>

    <!-- Belum Bayar -->
    <div class="table-card-custom mb-4">
        <div class="table-header-control">
            <h5 class="mb-0">Menunggu Pembayaran</h5>
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Order</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Acara</th>
                        <th>Batas Bayar</th>
                        <th>Total Bayar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($belumBayar as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td class="table-order-id">{{$item->kode_order}}</td>
                        <td>
                            <div class="table-user-name">{{$item->customer->nama}}</div>
                            <div class="table-user-sub">{{$item->customer->nomor_telepon}}</div>
                        </td>
                        <td>{{$item->tanggal_acara->format('d-m-Y')}}</td>
                        <td>{{$item->batas_bayar->format('d-m-Y')}}</td>
                        <td class="table-amount">Rp {{number_format($item->total_bayar, 0, ',', '.')}}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{route('admin.pembayaran.create', $item->id_order)}}" class="table-btn-action" title="Terima pembayaran tunai">
                                    <i class="bi bi-cash-coin"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada pesanan yang menunggu pembayaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-footer-control">
            <span class="table-pagination-info">Total {{$belumBayar->count()}} pesanan</span>
        </div>
    </div>

    <!-- Sudah Dibayar -->
    <div class="table-card-custom">
        <div class="table-header-control">
            <h5 class="mb-0">Pembayaran Diterima</h5>
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Order</th>
                        <th>Pelanggan</th>
                        <th>Jumlah Bayar</th>
                        <th>Tanggal Bayar</th>
                        <th>Diterima Oleh</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembayaran as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td class="table-order-id">{{$item->order->kode_order}}</td>
                        <td>{{$item->order->customer->nama}}</td>
                        <td class="table-amount">Rp {{number_format($item->jumlah_bayar, 0, ',', '.')}}</td>
                        <td>{{$item->tanggal_bayar->format('d-m-Y')}}</td>
                        <td>{{$item->verifier->nama ?? '-'}}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{route('admin.pembayaran.show', $item->id_payment)}}" class="table-btn-action" title="Lihat detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Belum ada pembayaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-footer-control">
            <span class="table-pagination-info">Total {{$pembayaran->count()}} pembayaran</span>
        </div>
    </div>
</div>
@endsection
