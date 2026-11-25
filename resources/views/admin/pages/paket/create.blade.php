@extends('admin.index')

@section('content')
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Tambah Paket Menu</h1>
            <p class="page-subtitle">Isi data paket catering yang baru.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-decoration-none text-muted-green">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.paket.table')}}" class="text-decoration-none text-muted-green">Paket Menu</a></li>
                <li class="breadcrumb-item active text-main" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-lg-8">
            <div class="card border-light shadow-sm p-4">
                <form action="{{route('admin.paket.store')}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="nama_paket" class="form-label-custom">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" class="form-control-custom"
                            name="nama_paket" id="nama_paket" value="{{old('nama_paket')}}" placeholder="Contoh: Paket Nasi Box Ayam" required>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label-custom">Deskripsi</label>
                        <textarea class="form-control-custom"
                            name="deskripsi" id="deskripsi" rows="3" placeholder="Isi menu dalam paket ini...">{{old('deskripsi')}}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="harga_porsi" class="form-label-custom">Harga per Porsi <span class="text-danger">*</span></label>
                            <div class="input-group-custom">
                                <span class="input-group-text-custom">Rp</span>
                                <input type="number" class="form-control-custom"
                                    name="harga_porsi" id="harga_porsi" value="{{old('harga_porsi')}}" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="min_porsi" class="form-label-custom">Minimal Porsi <span class="text-danger">*</span></label>
                            <input type="number" class="form-control-custom"
                                name="min_porsi" id="min_porsi" value="{{old('min_porsi', 1)}}" min="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="gambar_paket" class="form-label-custom">Gambar Paket</label>
                        <input type="file" class="form-control-custom"
                            name="gambar_paket" id="gambar_paket" accept="image/*">
                        <span class="text-muted">Format JPG, PNG, atau WEBP. Maksimal 2 MB.</span>
                    </div>

                    <div class="mb-4">
                        <label for="status_aktif" class="form-label-custom">Status <span class="text-danger">*</span></label>
                        <select class="form-select-custom" name="status_aktif" id="status_aktif" required>
                            <option value="aktif" {{ old('status_aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status_aktif') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-custom btn-custom-primary">
                            <i class="bi bi-check-lg"></i> Simpan Paket Menu
                        </button>
                        <a href="{{route('admin.paket.table')}}" class="btn-custom btn-custom-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
