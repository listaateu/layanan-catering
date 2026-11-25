@extends('admin.index')

@section('content')
<div>
    <!-- Selamat Datang -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Selamat Datang, {{auth()->user()->nama}}</h1>
            <p class="page-subtitle">{{ date('d-m-Y') }}</p>
        </div>
    </div>
    <!-- Selamat Datang -->
    <!-- Card Info -->
    <div class="row mx-2 my-4">
        <div class="col-md-6 col-xxl-3 my-2">
            <div class="card">
                <div class="card-body d-flex justify-content-start">
                    <div class="icon bg-success d-flex rounded p-3 me-3">
                        <i class="bi bi-box-seam-fill text-light m-auto fs-2"></i>
                    </div>
                    <div class="card-text align-content-center">
                        <h5 class="mb-1">Paket Menu</h5>
                        <h6 class="m-0">{{$totalPaket}}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3 my-2">
            <div class="card">
                <div class="card-body d-flex justify-content-start">
                    <div class="icon bg-primary d-flex rounded p-3 me-3">
                        <i class="bi bi-receipt text-light m-auto fs-2"></i>
                    </div>
                    <div class="card-text align-content-center">
                        <h5 class="mb-1">Total Pesanan</h5>
                        <h6 class="m-0">{{$totalPesanan}}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3 my-2">
            <div class="card">
                <div class="card-body d-flex justify-content-start">
                    <div class="icon bg-warning d-flex rounded p-3 me-3">
                        <i class="bi bi-cash-stack text-light m-auto fs-2"></i>
                    </div>
                    <div class="card-text align-content-center">
                        <h5 class="mb-1">Menunggu Verifikasi</h5>
                        <h6 class="m-0">{{$pembayaranMenunggu}}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3 my-2">
            <div class="card">
                <div class="card-body d-flex justify-content-start">
                    <div class="icon bg-info d-flex rounded p-3 me-3">
                        <i class="bi bi-wallet2 text-light m-auto fs-2"></i>
                    </div>
                    <div class="card-text align-content-center">
                        <h5 class="mb-1">Pendapatan</h5>
                        <h6 class="m-0">Rp {{number_format($totalPendapatan, 0, ',', '.')}}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Card Info -->
    <!-- Data Pesanan Terbaru -->
    <div class="row mx-2 my-4">
        <div class="col-md-12">
            <div class="table-card-custom">
                <div class="table-header-control">
                    <h5 class="mb-0">Pesanan Terbaru</h5>
                    <a href="{{route('admin.pesanan.table')}}" class="btn-custom btn-custom-light">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Kode Order</th>
                                <th>Pelanggan</th>
                                <th>Tanggal Acara</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pesananBaru as $item)
                            <tr>
                                <td class="table-order-id">
                                    <a href="{{route('admin.pesanan.show', $item->id_order)}}" class="text-decoration-none">{{$item->kode_order}}</a>
                                </td>
                                <td>{{$item->customer->nama}}</td>
                                <td>{{$item->tanggal_acara->format('d-m-Y')}}</td>
                                <td class="table-amount">Rp {{number_format($item->total_bayar, 0, ',', '.')}}</td>
                                <td><span class="badge-table {{$item->status_badge}}">{{$item->status_label}}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Belum ada pesanan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Pesanan Terbaru -->
    <!-- Acara Mendatang -->
    <div class="row mx-2 my-4">
        <div class="col-md-12">
            <div class="table-card-custom">
                <div class="table-header-control">
                    <h5 class="mb-0">Acara 7 Hari ke Depan</h5>
                    <a href="{{route('admin.produksi.table')}}" class="btn-custom btn-custom-light">Lihat Produksi</a>
                </div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Kode Order</th>
                                <th>Pelanggan</th>
                                <th>Tanggal Acara</th>
                                <th>Produksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($acaraMendatang as $item)
                            <tr>
                                <td class="table-order-id">
                                    <a href="{{route('admin.pesanan.show', $item->id_order)}}" class="text-decoration-none">{{$item->kode_order}}</a>
                                </td>
                                <td>{{$item->customer->nama}}</td>
                                <td>
                                    {{$item->tanggal_acara->format('d-m-Y')}}
                                    <div class="table-user-sub">Pukul {{substr($item->jam_kirim, 0, 5)}}</div>
                                </td>
                                <td>
                                    @if ($item->productionSchedule)
                                    <span class="badge-table {{$item->productionSchedule->status_badge}}">{{$item->productionSchedule->status_label}}</span>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Tidak ada acara dalam 7 hari ke depan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Acara Mendatang -->
</div>
@endsection
