@extends('admin.index')

@section('content')
@php
    $daftarStatus = [
        'antrean_dapur' => 'Antrean Dapur',
        'sedang_dimasak' => 'Sedang Dimasak',
        'siap_kirim' => 'Siap Kirim',
        'terkirim' => 'Terkirim',
    ];
@endphp
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Produksi</h1>
            <p class="page-subtitle">Pantau antrean dan progres masakan di dapur.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-decoration-none text-muted-green">Home</a></li>
                <li class="breadcrumb-item active text-main" aria-current="page">Produksi</li>
            </ol>
        </nav>
    </div>

    <div class="table-card-custom">
        <div class="table-header-control">
            <h5 class="mb-0">Data Produksi</h5>
            <div class="table-filter-group">
                <div class="dropdown">
                    <button class="btn-table-action dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-funnel"></i> {{ $daftarStatus[$status] ?? 'Semua Status' }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('admin.produksi')}}">Semua Status</a></li>
                        @foreach ($daftarStatus as $key => $label)
                        <li><a class="dropdown-item" href="{{route('admin.produksi', ['status' => $key])}}">{{$label}}</a></li>
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
                        <th>Menu</th>
                        <th>Tanggal Acara</th>
                        <th>Status Produksi</th>
                        <th>Penanggung Jawab</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produksi as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td class="table-order-id">{{$item->order->kode_order}}</td>
                        <td>
                            @foreach ($item->order->items as $detail)
                            <div>{{$detail->package->nama_paket}} <span class="table-user-sub">({{$detail->jumlah_porsi}} porsi)</span></div>
                            @endforeach
                        </td>
                        <td>
                            {{$item->order->tanggal_acara->locale('id')->translatedFormat('d F Y')}}
                            <div class="table-user-sub">Pukul {{substr($item->order->jam_kirim, 0, 5)}}</div>
                        </td>
                        <td><span class="badge-table {{$item->status_badge}}">{{$item->status_label}}</span></td>
                        <td>{{$item->handler->nama ?? '-'}}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{route('admin.pesanan.show', $item->id_order)}}" class="table-btn-action" title="Lihat pesanan">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Belum ada pesanan di antrean dapur</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer-control">
            <span class="table-pagination-info">Total {{$produksi->count()}} jadwal produksi</span>
        </div>
    </div>
</div>
@endsection
