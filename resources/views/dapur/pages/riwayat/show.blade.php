@extends('dapur.index')

@section('content')
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Detail Riwayat</h1>
            <p class="page-subtitle">{{$produksi->order->kode_order}}</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('dapur.dashboard')}}" class="text-decoration-none text-muted-green">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('dapur.riwayat.table')}}" class="text-decoration-none text-muted-green">Riwayat</a></li>
                <li class="breadcrumb-item active text-main" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-lg-8">
            <div class="card border-light shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">Informasi Pesanan</h5>
                    <span class="badge-table {{$produksi->status_badge}}">{{$produksi->status_label}}</span>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Tanggal Acara</label>
                        <input type="text" class="form-control-custom" value="{{$produksi->order->tanggal_acara->format('d-m-Y')}}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Jam Kirim</label>
                        <input type="text" class="form-control-custom" value="{{substr($produksi->order->jam_kirim, 0, 5)}}" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label-custom">Alamat Kirim</label>
                    <textarea class="form-control-custom" rows="3" readonly>{{$produksi->order->alamat_kirim}}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label-custom">Penanggung Jawab</label>
                    <input type="text" class="form-control-custom" value="{{$produksi->handler->nama ?? '-'}}" readonly>
                </div>

                <h6 class="mt-2 mb-2">Menu</h6>
                <div class="table-responsive mb-3">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Paket</th>
                                <th>Jumlah Porsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produksi->order->items as $detail)
                            <tr>
                                <td>{{$detail->package->nama_paket}}</td>
                                <td>{{$detail->jumlah_porsi}} porsi</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    <a href="{{route('dapur.riwayat.table')}}" class="btn-custom btn-custom-light">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
