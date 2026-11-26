{{-- Satu kartu paket menu (potongan "single_order" dari template menu.html) --}}
{{-- Dipakai di beranda dan halaman menu lewat @include, variabelnya $item = 1 paket --}}
<div class="col-xl-4 col-md-6">
    <div class="single_order">
        <div class="order_thumb">
            {{-- kalau paket punya gambar tampilkan gambarnya, kalau tidak pakai gambar bawaan template --}}
            <img src="{{ $item->gambar_paket ? asset('storage/' . $item->gambar_paket) : asset('flatter-master/img/order/order-1.png') }}"
                alt="{{$item->nama_paket}}" style="width: 100%; height: 260px; object-fit: cover;">
            <div class="order_prise">
                <span>Rp {{number_format($item->harga_porsi, 0, ',', '.')}}</span>
            </div>
        </div>
        <div class="order_info">
            {{-- klik nama paket untuk melihat detail isi paketnya --}}
            <h3><a href="{{route('pelanggan.menu.show', $item->id_paket)}}">{{$item->nama_paket}}</a></h3>
            <p>{{ Str::limit($item->deskripsi, 80) }} <br>
                Minimal {{$item->min_porsi}} porsi
            </p>
            {{-- kalau belum login, route ini otomatis mengarahkan ke halaman login (dijaga middleware CekPelanggan) --}}
            <a href="{{route('pelanggan.pesanan.create', $item->id_paket)}}" class="boxed_btn">Pesan Sekarang!</a>
            <a href="{{route('pelanggan.menu.show', $item->id_paket)}}" class="d-inline-block ml-3">Lihat detail</a>
        </div>
    </div>
</div>
