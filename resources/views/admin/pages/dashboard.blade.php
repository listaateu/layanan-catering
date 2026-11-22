    <!-- Data Pesanan Terbaru -->
    <div class="row mx-2 my-4">
        <div class="col-md-12">
            <div class="table-card-custom">
                <div class="table-header-control">
                    <h5 class="mb-0">Pesanan Terbaru</h5>
                    <a href="{{route('admin.pesanan')}}" class="btn-custom btn-custom-light">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Kode Order</th>
                                <th>Pelanggan</th>
                                <th>Tanggal Acara</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pesananBaru as $item)
                            <tr>
                                <td class="table-order-id">
                                    <a href="{{route('admin.pesanan.show', $item->id_order)}}" class="text-decoration-none">{{$item->kode_order}}</a>
                                </td>
                                <td>{{$item->customer->nama}}</td>
                                <td>{{$item->tanggal_acara->locale('id')->translatedFormat('d F Y')}}</td>
                                <td class="table-amount">Rp {{number_format($item->total_bayar, 0, ',', '.')}}</td>
                                <td><span class="badge-table {{$item->status_badge}}">{{$item->status_label}}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Belum ada pesanan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Pesanan Terbaru -->
