<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Layanan Catering</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="{{asset('flatter-master/img/logo.png')}}">

    {{-- CSS bawaan template Flatter, asset() = alamat file di folder public --}}
    <link rel="stylesheet" href="{{asset('flatter-master/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('flatter-master/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('flatter-master/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('flatter-master/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('flatter-master/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('flatter-master/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{asset('flatter-master/css/flaticon.css')}}">
    <link rel="stylesheet" href="{{asset('flatter-master/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('flatter-master/css/slicknav.css')}}">
    <link rel="stylesheet" href="{{asset('flatter-master/css/style.css')}}">

    {{-- sedikit tambahan: banner dikecilkan, menu atas selalu terlihat (fixed), footer dipendekkan, tombol logout dibuat seperti tombol Login --}}
    <style>
        .slider_area .single_slider { height: 640px; }
        .header-area { position: fixed; }
        /* daftar isi paket diberi titik (template menghilangkan titik bawaan list) */
        .isi-paket { padding-left: 20px; margin-bottom: 15px; }
        .isi-paket li { list-style: disc; display: list-item; }
        .footer_area { padding-top: 90px; padding-bottom: 30px; }
        .footer_area .footer-bottom { padding-top: 30px; }
        .logout-btn { background: transparent; cursor: pointer; }
        button.boxed_btn { background: #fff; cursor: pointer; }
        /* warna lencana status: pending = kuning, success = hijau, failed = merah (sama dengan nama dari status_badge di model) */
        .status-pending { background: #ffc107; color: #212529; }
        .status-success { background: #28a745; color: #fff; }
        .status-failed { background: #dc3545; color: #fff; }
    </style>
</head>
