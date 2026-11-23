@extends('admin.index')

@section('content')
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Verifikasi Pembayaran</h1>
            <p class="page-subtitle">Periksa bukti transfer dari pelanggan.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-decoration-none text-muted-green">Home</a></li>
                <li class="breadcrumb-item active text-main" aria-current="page">Pembayaran</li>
            </ol>
        </nav>
    </div>

    <div class="table-card-custom">
        <div class="table-header-control">
            <h5 class="mb-0">Data Pembayaran</h5>
        </div>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Order</th>
                        <th>Pelanggan</th>
                        <th>Jumlah Transfer</th>
                        <th>Tanggal Upload</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembayaran as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td class="table-order-id">{{$item->order->kode_order}}</td>
                        <td>{{$item->order->customer->nama}}</td>
                        <td class="table-amount">Rp {{number_format($item->jumlah_transfer, 0, ',', '.')}}</td>
                        <td>{{$item->created_at->locale('id')->translatedFormat('d F Y, H:i')}}</td>
                        <td>
                            <span class="badge-table {{ $item->status_verifikasi == 'diterima' ? 'success' : ($item->status_verifikasi == 'ditolak' ? 'failed' : 'pending') }}">
                                {{ucfirst($item->status_verifikasi)}}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                @if ($item->status_verifikasi == 'menunggu')
                                <a href="{{route('admin.pembayaran.validasi', $item->id_payment)}}" class="table-btn-action" title="Validasi pembayaran">
                                    <i class="bi bi-check-circle"></i>
                                </a>
                                @else
                                <a href="{{route('admin.pembayaran.show', $item->id_payment)}}" class="table-btn-action" title="Lihat detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endif
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
