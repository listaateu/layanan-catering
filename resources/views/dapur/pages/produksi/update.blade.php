@extends('dapur.index')

@section('content')
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Ubah Status Produksi</h1>
            <p class="page-subtitle">{{$produksi->order->kode_order}}</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('dapur.dashboard')}}" class="text-decoration-none text-muted-green">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('dapur.produksi.table')}}" class="text-decoration-none text-muted-green">Produksi</a></li>
                <li class="breadcrumb-item active text-main" aria-current="page">Ubah Status</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4 mb-4">
        <!-- Detail Pesanan (hanya dibaca) -->
        <div class="col-12 col-lg-7">
            <div class="card border-light shadow-sm p-4 h-100">
                <h5 class="card-title mb-4">Detail Pesanan</h5>
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

                <h6 class="mt-2 mb-2">Menu yang Harus Dimasak</h6>
                <div class="table-responsive">
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
            </div>
        </div>

        <!-- Form Ubah Status -->
        <div class="col-12 col-lg-5">
            <div class="card border-light shadow-sm p-4 h-100">
                <h5 class="card-title mb-4">Ubah Status</h5>

                <div class="mb-3">
                    <label class="form-label-custom">Status Sekarang</label>
                    <div><span class="badge-table {{$produksi->status_badge}}">{{$produksi->status_label}}</span></div>
                </div>

                {{-- kalau sudah tidak ada status berikutnya (sudah terkirim), form tidak ditampilkan --}}
                @if ($statusBerikut)
                {{-- method="post" + @method('PUT') = form dikirim ke route update yang memakai Route::put --}}
                <form action="{{route('dapur.produksi.update', $produksi->id_produksi)}}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="status_produksi" class="form-label-custom">Status Berikutnya <span class="text-danger">*</span></label>
                        {{-- pilihannya hanya 1, yaitu langkah berikutnya, supaya kerjanya berurutan --}}
                        <select class="form-select-custom" name="status_produksi" id="status_produksi" required>
                            <option value="{{$statusBerikut}}" class="text-capitalize">{{ucwords(str_replace('_', ' ', $statusBerikut))}}</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-custom btn-custom-primary">
                            <i class="bi bi-check-lg"></i> Simpan Status
                        </button>
                        <a href="{{route('dapur.produksi.table')}}" class="btn-custom btn-custom-light">Batal</a>
                    </div>
                </form>
                @else
                <a href="{{route('dapur.produksi.table')}}" class="btn-custom btn-custom-light">Kembali</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
