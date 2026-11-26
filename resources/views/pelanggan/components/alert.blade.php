{{-- pesan sukses / error dari controller (->with('success', ...)) dan daftar error validasi --}}
<div class="container">
    @if (session('success'))
    <div class="alert alert-success mt-3" role="alert">
        {{session('success')}}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger mt-3" role="alert">
        {{session('error')}}
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger mt-3" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
