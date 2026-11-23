@extends('admin.index')

@section('content')
<div>
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Paket Menu</h1>
            <p class="page-subtitle">Kelola daftar paket catering yang tersedia untuk pelanggan.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-decoration-none text-muted-green">Home</a></li>
                <li class="breadcrumb-item active text-main" aria-current="page">Paket Menu</li>
            </ol>
        </nav>
    </div>
    <!-- Page Header -->

    <!-- Table Card -->
    <div class="table-card-custom">
        <div class="table-header-control">
            <h5 class="mb-0">Data Paket Menu</h5>
            <a href="{{route('admin.paket.create')}}" class="btn-custom btn-custom-primary">
                <i class="bi bi-plus-lg"></i> Tambah Paket Menu
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Paket</th>
                        <th>Harga / Porsi</th>
                        <th>Min. Porsi</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paket as $item)
                    <tr>
                        <td class="table-order-id">{{$loop->iteration}}</td>
                        <td>
                            <div class="table-user-cell">
                                <img src="{{ $item->gambar_paket ? asset('storage/' . $item->gambar_paket) : asset('admin/spark/assets/images/avatar.png') }}"
                                    alt="{{$item->nama_paket}}" class="table-user-avatar" style="object-fit: cover;">
                                <div>
                                    <div class="table-user-name">{{$item->nama_paket}}</div>
                                    <div class="table-user-sub">{{ \Illuminate\Support\Str::limit($item->deskripsi, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="table-amount">Rp {{number_format($item->harga_porsi, 0, ',', '.')}}</td>
                        <td>{{$item->min_porsi}} porsi</td>
                        <td>
                            <span class="badge-table {{ $item->status_aktif == 'aktif' ? 'success' : 'failed' }}">
                                {{ $item->status_aktif == 'aktif' ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{route('admin.paket.edit', $item->id_paket)}}" class="table-btn-action" title="Ubah data">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{route('admin.paket.destroy', $item->id_paket)}}" method="post" onsubmit="return alertConfirm(event, this, 'Data paket akan dihapus', 'Apakah anda yakin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="table-btn-action delete" title="Hapus data">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Belum ada data paket menu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer-control">
            <span class="table-pagination-info">Total {{$paket->count()}} paket menu</span>
        </div>
    </div>
    <!-- Table Card -->
</div>
@endsection
