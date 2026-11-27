@extends('admin.index')

@section('content')
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
                {{-- cari berdasarkan kode order, filter status yang dipilih ikut terbawa --}}
                <form action="{{route('admin.pesanan.table')}}" method="get" class="d-flex gap-2 me-2">
                    @if ($status)
                    <input type="hidden" name="status" value="{{$status}}">
                    @endif
                    <input type="text" class="form-control-custom" name="cari" value="{{$cari}}" placeholder="Cari kode order">
                    <button type="submit" class="btn-table-action"><i class="bi bi-search"></i></button>
                </form>
                <div class="dropdown">
                    <button class="btn-table-action dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-funnel"></i> <span class="text-capitalize">{{ $status ? str_replace('_', ' ', $status) : 'Semua Status' }}</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('admin.pesanan.table', ['cari' => $cari])}}">Semua Status</a></li>
                        {{-- pilihan filter status, statusnya dikirim lewat alamat (?status=...) --}}
                        <li><a class="dropdown-item" href="{{route('admin.pesanan.table', ['status' => 'menunggu_pembayaran', 'cari' => $cari])}}">Menunggu Pembayaran</a></li>
                        <li><a class="dropdown-item" href="{{route('admin.pesanan.table', ['status' => 'terkonfirmasi', 'cari' => $cari])}}">Terkonfirmasi</a></li>
                        <li><a class="dropdown-item" href="{{route('admin.pesanan.table', ['status' => 'diproses', 'cari' => $cari])}}">Diproses</a></li>
                        <li><a class="dropdown-item" href="{{route('admin.pesanan.table', ['status' => 'selesai', 'cari' => $cari])}}">Selesai</a></li>
                        <li><a class="dropdown-item" href="{{route('admin.pesanan.table', ['status' => 'dibatalkan', 'cari' => $cari])}}">Dibatalkan</a></li>
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
                    {{-- loop semua pesanan di halaman ini --}}
                    @forelse ($pesanan as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td class="table-order-id">{{$item->kode_order}}</td>
                        <td>
                            <div class="table-user-name">{{$item->customer->nama}}</div>
                            <div class="table-user-sub">{{$item->customer->nomor_telepon}}</div>
                        </td>
                        <td>
                            {{$item->tanggal_acara->format('d-m-Y')}}
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
