@extends('admin.index')

@section('content')
@php
    $daftarStatus = [
        'menunggu_pembayaran' => 'Menunggu Pembayaran',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'terkonfirmasi' => 'Terkonfirmasi',
        'diproses' => 'Diproses',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];
@endphp
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Pesanan</h1>
            <p class="page-subtitle">Pantau semua pesanan catering dari pelanggan.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-decoration-none text-muted-green">Home</a></li>
                <li class="breadcrumb-item active text-main" aria-current="page">Pesanan</li>
            </ol>
        </nav>
    </div>

    <div class="table-card-custom">
        <div class="table-header-control">
            <h5 class="mb-0">Data Pesanan</h5>
            <div class="table-filter-group">
                <div class="dropdown">
                    <button class="btn-table-action dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-funnel"></i> {{ $daftarStatus[$status] ?? 'Semua Status' }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('admin.pesanan')}}">Semua Status</a></li>
                        @foreach ($daftarStatus as $key => $label)
                        <li><a class="dropdown-item" href="{{route('admin.pesanan', ['status' => $key])}}">{{$label}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Order</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Acara</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesanan as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td class="table-order-id">{{$item->kode_order}}</td>
                        <td>
                            <div class="table-user-name">{{$item->customer->nama}}</div>
                            <div class="table-user-sub">{{$item->customer->nomor_telepon}}</div>
                        </td>
                        <td>
                            {{$item->tanggal_acara->locale('id')->translatedFormat('d F Y')}}
                            <div class="table-user-sub">Pukul {{substr($item->jam_kirim, 0, 5)}}</div>
                        </td>
                        <td class="table-amount">Rp {{number_format($item->total_bayar, 0, ',', '.')}}</td>
                        <td><span class="badge-table {{$item->status_badge}}">{{$item->status_label}}</span></td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{route('admin.pesanan.show', $item->id_order)}}" class="table-btn-action" title="Lihat detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Belum ada pesanan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer-control">
            <span class="table-pagination-info">Total {{$pesanan->count()}} pesanan</span>
        </div>
    </div>
</div>
@endsection
