@extends('admin.index')

@section('content')
<div>
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Paket Menu</h1>
            <p class="page-subtitle">Ubah data paket {{$paket->nama_paket}}.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="text-decoration-none text-muted-green">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.paket')}}" class="text-decoration-none text-muted-green">Paket Menu</a></li>
                <li class="breadcrumb-item active text-main" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-lg-8">
            <div class="card border-light shadow-sm p-4">
                <form action="{{route('admin.paket.update', $paket->id_paket)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama_paket" class="form-label-custom">Nama Paket</label>
                        <input type="text" class="form-control-custom @error('nama_paket') is-invalid-custom @enderror"
                            name="nama_paket" id="nama_paket" value="{{old('nama_paket', $paket->nama_paket)}}" required>
                        @error('nama_paket')
                        <div class="form-feedback-custom invalid-custom"><i class="bi bi-exclamation-circle-fill"></i> {{$message}}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label-custom">Deskripsi</label>
                        <textarea class="form-control-custom @error('deskripsi') is-invalid-custom @enderror"
                            name="deskripsi" id="deskripsi" rows="3">{{old('deskripsi', $paket->deskripsi)}}</textarea>
                        @error('deskripsi')
                        <div class="form-feedback-custom invalid-custom"><i class="bi bi-exclamation-circle-fill"></i> {{$message}}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="harga_porsi" class="form-label-custom">Harga per Porsi</label>
                            <div class="input-group-custom">
                                <span class="input-group-text-custom">Rp</span>
                                <input type="number" class="form-control-custom @error('harga_porsi') is-invalid-custom @enderror"
                                    name="harga_porsi" id="harga_porsi" value="{{old('harga_porsi', (int) $paket->harga_porsi)}}" min="1" required>
                            </div>
                            @error('harga_porsi')
                            <div class="form-feedback-custom invalid-custom"><i class="bi bi-exclamation-circle-fill"></i> {{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="min_porsi" class="form-label-custom">Minimal Porsi</label>
                            <input type="number" class="form-control-custom @error('min_porsi') is-invalid-custom @enderror"
                                name="min_porsi" id="min_porsi" value="{{old('min_porsi', $paket->min_porsi)}}" min="1" required>
                            @error('min_porsi')
                            <div class="form-feedback-custom invalid-custom"><i class="bi bi-exclamation-circle-fill"></i> {{$message}}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="gambar_paket" class="form-label-custom">Gambar Paket</label>
                        @if ($paket->gambar_paket)
                        <div class="mb-2">
                            <img src="{{asset('storage/' . $paket->gambar_paket)}}" alt="{{$paket->nama_paket}}"
                                class="rounded" style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        @endif
                        <input type="file" class="form-control-custom @error('gambar_paket') is-invalid-custom @enderror"
                            name="gambar_paket" id="gambar_paket" accept="image/*">
                        <span class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</span>
                        @error('gambar_paket')
                        <div class="form-feedback-custom invalid-custom"><i class="bi bi-exclamation-circle-fill"></i> {{$message}}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status_aktif" class="form-label-custom">Status</label>
                        <select class="form-select-custom" name="status_aktif" id="status_aktif" required>
                            <option value="aktif" @selected(old('status_aktif', $paket->status_aktif) == 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status_aktif', $paket->status_aktif) == 'nonaktif')>Non-Aktif</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-custom btn-custom-primary">
                            <i class="bi bi-check-lg"></i> Simpan Perubahan
                        </button>
                        <a href="{{route('admin.paket')}}" class="btn-custom btn-custom-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
