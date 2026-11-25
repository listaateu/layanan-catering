@extends('dapur.index')

@section('content')
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Produksi</h1>
            <p class="page-subtitle">Pesanan yang harus dikerjakan dapur.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('dapur.dashboard')}}" class="text-decoration-none text-muted-green">Home</a></li>
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
                        <i class="bi bi-funnel"></i> <span class="text-capitalize">{{ $status ? str_replace('_', ' ', $status) : 'Semua Status' }}</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('dapur.produksi.table')}}">Semua Status</a></li>
                        {{-- pilihan filter status, statusnya dikirim lewat alamat (?status=...) --}}
                        <li><a class="dropdown-item" href="{{route('dapur.produksi.table', ['status' => 'antrean_dapur'])}}">Antrean Dapur</a></li>
                        <li><a class="dropdown-item" href="{{route('dapur.produksi.table', ['status' => 'sedang_dimasak'])}}">Sedang Dimasak</a></li>
                        <li><a class="dropdown-item" href="{{route('dapur.produksi.table', ['status' => 'siap_kirim'])}}">Siap Kirim</a></li>
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
                    {{-- loop semua jadwal produksi, $loop->iteration = nomor urut --}}
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
                            {{$item->order->tanggal_acara->format('d-m-Y')}}
                            <div class="table-user-sub">Pukul {{substr($item->order->jam_kirim, 0, 5)}}</div>
                        </td>
                        <td><span class="badge-table {{$item->status_badge}}">{{$item->status_label}}</span></td>
                        <td>{{$item->handler->nama ?? '-'}}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                {{-- tombol ke halaman ubah status --}}
                                <a href="{{route('dapur.produksi.edit', $item->id_produksi)}}" class="table-btn-action" title="Ubah status">
                                    <i class="bi bi-pencil-square"></i>
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
