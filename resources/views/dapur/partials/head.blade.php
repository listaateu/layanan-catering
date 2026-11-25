<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kepala Dapur - Layanan Catering</title>
    <link rel="icon" type="image/x-icon" href="{{asset('admin/spark/assets/images/favicon.ico')}}">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{asset('admin/spark/assets/libs/bootstrap/css/bootstrap.min.css')}}" />

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="{{asset('admin/spark/assets/libs/bootstrap-icons/bootstrap-icons.css')}}" />

    <!-- Apexcharts & Flatpickr -->
    <link rel="stylesheet" href="{{asset('admin/spark/assets/libs/apexcharts/apexcharts.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/spark/assets/libs/flatpickr/flatpickr.min.css')}}" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@400;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <!-- Local Style -->
    <link rel="stylesheet" href="{{asset('admin/spark/assets/css/main.css')}}" />

    {{-- Beda warna dengan admin: kita timpa variabel warna dari main.css --}}
    {{-- Admin = hijau tua + lime, Kepala Dapur = coklat tua + oranye. Cukup ganti variabel di sini, CSS lainnya tetap dipakai --}}
    <style>
        :root {
            --brand-forest-dark: #2B1608;
            --brand-forest-medium: #3D200C;
            --brand-forest-light: #5A3416;
            --brand-lime: #F59E0B;
            --brand-lime-hover: #FBB02D;
            --brand-lime-translucent: rgba(245, 158, 11, 0.15);
        }
    </style>
</head>
