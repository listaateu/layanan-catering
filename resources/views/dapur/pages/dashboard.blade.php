@extends('dapur.index')

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
    {{-- 4 kotak ringkasan, angkanya dikirim dari DashboardController --}}
    <div class="row mx-2 my-4">
        <div class="col-md-6 col-xxl-3 my-2">
            <div class="card">
                <div class="card-body d-flex justify-content-start">
                    <div class="icon bg-warning d-flex rounded p-3 me-3">
                        <i class="bi bi-hourglass-split text-light m-auto fs-2"></i>
                    </div>
                    <div class="card-text align-content-center">
                        <h5 class="mb-1">Antrean Dapur</h5>
                        <h6 class="m-0">{{$antrean}}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3 my-2">
            <div class="card">
                <div class="card-body d-flex justify-content-start">
                    <div class="icon bg-danger d-flex rounded p-3 me-3">
                        <i class="bi bi-fire text-light m-auto fs-2"></i>
                    </div>
                    <div class="card-text align-content-center">
                        <h5 class="mb-1">Sedang Dimasak</h5>
                        <h6 class="m-0">{{$dimasak}}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3 my-2">
            <div class="card">
                <div class="card-body d-flex justify-content-start">
                    <div class="icon bg-primary d-flex rounded p-3 me-3">
                        <i class="bi bi-box-seam-fill text-light m-auto fs-2"></i>
                    </div>
                    <div class="card-text align-content-center">
                        <h5 class="mb-1">Siap Kirim</h5>
                        <h6 class="m-0">{{$siapKirim}}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3 my-2">
            <div class="card">
                <div class="card-body d-flex justify-content-start">
                    <div class="icon bg-success d-flex rounded p-3 me-3">
                        <i class="bi bi-check-circle-fill text-light m-auto fs-2"></i>
                    </div>
                    <div class="card-text align-content-center">
                        <h5 class="mb-1">Terkirim</h5>
                        <h6 class="m-0">{{$terkirim}}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Card Info -->

    <!-- Kerjaan Terdekat -->
    <div class="row mx-2 my-4">
        <div class="col-md-12">
            <div class="table-card-custom">
                <div class="table-header-control">
                    <h5 class="mb-0">Pesanan yang Harus Dikerjakan</h5>
                    <a href="{{route('dapur.produksi.table')}}" class="btn-custom btn-custom-light">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Kode Order</th>
                                <th>Menu</th>
                                <th>Tanggal Acara</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @forelse = perulangan, kalau datanya kosong tampilkan bagian @empty --}}
                            @forelse ($kerjaan as $item)
                            <tr>
                                <td class="table-order-id">{{$item->order->kode_order}}</td>
                                <td>
                                    @foreach ($item->order->items as $detail)
                                    <div>{{$detail->package->nama_paket}} <span class="table-user-sub">({{$detail->jumlah_porsi}} porsi)</span></div>
                                    @endforeach
                                </td>
                                <td>{{$item->order->tanggal_acara->format('d-m-Y')}} <div class="table-user-sub">Pukul {{substr($item->order->jam_kirim, 0, 5)}}</div></td>
                                <td><span class="badge-table {{$item->status_badge}}">{{$item->status_label}}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Tidak ada pesanan yang harus dikerjakan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Kerjaan Terdekat -->
</div>
@endsection
