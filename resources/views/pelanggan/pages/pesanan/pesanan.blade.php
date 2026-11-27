@extends('pelanggan.index')

@section('content')
<!-- Judul Halaman -->
<div class="breadcam_area breadcam_bg_1 zigzag_bg_2">
    <div class="breadcam_inner">
        <div class="breadcam_text">
            <h3>Pesanan Saya</h3>
            <p>Semua pesanan yang pernah kamu buat.</p>
        </div>
    </div>
</div>
<!-- Judul Halaman -->

<section class="contact-section">
    <div class="container">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Order</th>
                        <th>Menu</th>
                        <th>Tanggal Acara</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesanan as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->kode_order}}</td>
                        <td>
                            @foreach ($item->items as $detail)
                            <div>{{$detail->package->nama_paket}} ({{$detail->jumlah_porsi}} porsi)</div>
                            @endforeach
                        </td>
                        <td>{{$item->tanggal_acara->format('d-m-Y')}}</td>
                        <td>Rp {{number_format($item->total_bayar, 0, ',', '.')}}</td>
                        <td><span class="badge status-{{$item->status_badge}}">{{$item->status_label_pelanggan}}</span></td>
                        <td><a href="{{route('pelanggan.pesanan.show', $item->id_order)}}" class="btn btn-sm btn-outline-secondary">Detail</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Kamu belum punya pesanan. <a href="{{route('pelanggan.menu')}}">Lihat menu</a></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
