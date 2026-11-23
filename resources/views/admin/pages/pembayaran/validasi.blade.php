@extends('admin.index')

@section('content')
@php
    $buktiAda = $pembayaran->bukti_transfer
        && \Illuminate\Support\Facades\Storage::disk('public')->exists($pembayaran->bukti_transfer);
    $selisih = (float) $pembayaran->jumlah_transfer !== (float) $pembayaran->order->total_bayar;
@endphp
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Validasi Pembayaran</h1>
            <p class="page-subtitle">{{$pembayaran->order->kode_order}}</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-decoration-none text-muted-green">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.pembayaran')}}" class="text-decoration-none text-muted-green">Pembayaran</a></li>
                <li class="breadcrumb-item active text-main" aria-current="page">Validasi</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4 mb-4">
        <!-- Bukti Transfer -->
        <div class="col-12 col-lg-5">
            <div class="card border-light shadow-sm p-4 h-100">
                <h5 class="card-title mb-4">Bukti Transfer</h5>
                @if ($buktiAda)
                <a href="{{asset('storage/' . $pembayaran->bukti_transfer)}}" target="_blank">
                    <img src="{{asset('storage/' . $pembayaran->bukti_transfer)}}" alt="Bukti transfer" class="img-fluid rounded">
                </a>
                <span class="text-muted mt-2">Klik gambar untuk memperbesar.</span>
                @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-image fs-1"></i>
                    <p class="mb-0">File bukti transfer tidak ditemukan</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Detail & Form -->
        <div class="col-12 col-lg-7">
            <div class="card border-light shadow-sm p-4 h-100">
                <h5 class="card-title mb-4">Detail Pembayaran</h5>
                <form action="{{route('admin.pembayaran.update', $pembayaran->id_payment)}}" method="post" id="formValidasi">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label-custom">Pelanggan</label>
                        <input type="text" class="form-control-custom" value="{{$pembayaran->order->customer->nama}}" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Total Tagihan</label>
                            <input type="text" class="form-control-custom" value="Rp {{number_format($pembayaran->order->total_bayar, 0, ',', '.')}}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Jumlah Transfer</label>
                            <input type="text" class="form-control-custom {{$selisih ? 'is-invalid-custom' : ''}}" value="Rp {{number_format($pembayaran->jumlah_transfer, 0, ',', '.')}}" readonly>
                            @if ($selisih)
                            <div class="form-feedback-custom invalid-custom"><i class="bi bi-exclamation-circle-fill"></i> Jumlah transfer berbeda dengan total tagihan.</div>
                            @endif
                        </div>
                    </div>

                    <h6 class="mt-2 mb-2">Isi Pesanan</h6>
                    <div class="table-responsive mb-3">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>Paket</th>
                                    <th>Porsi</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembayaran->order->items as $item)
                                <tr>
                                    <td>{{$item->package->nama_paket}}</td>
                                    <td>{{$item->jumlah_porsi}}</td>
                                    <td>Rp {{number_format($item->subtotal, 0, ',', '.')}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-3">
                        <label for="status_verifikasi" class="form-label-custom">Keputusan</label>
                        <select class="form-select-custom @error('status_verifikasi') is-invalid-custom @enderror" name="status_verifikasi" id="status_verifikasi" required>
                            <option value="" disabled selected>Pilih keputusan</option>
                            <option value="diterima">Diterima</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                        @error('status_verifikasi')
                        <div class="form-feedback-custom invalid-custom"><i class="bi bi-exclamation-circle-fill"></i> {{$message}}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Diverifikasi Oleh</label>
                        <input type="text" class="form-control-custom" value="{{auth()->user()->nama}}" readonly>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{route('admin.pembayaran')}}" class="btn-custom btn-custom-light">Kembali</a>
                        <button type="submit" class="btn-custom btn-custom-primary">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
