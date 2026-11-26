<!-- footer-start -->
<footer class="footer_area footer-bg zigzag_bg_1">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-md-6 col-lg-4">
                    <div class="footer_widget">
                        <h3 class="heading">Layanan Catering</h3>
                        <p class="offer_text">Pesan catering untuk acara keluarga, kantor, dan kegiatan lainnya dengan mudah.</p>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 col-lg-4">
                    <div class="footer_widget">
                        <h3 class="heading">Menu</h3>
                        <ul>
                            <li><a href="{{route('pelanggan.beranda')}}">Beranda</a></li>
                            <li><a href="{{route('pelanggan.menu')}}">Menu</a></li>
                            <li><a href="{{route('pelanggan.tentang')}}">Tentang</a></li>
                            <li><a href="{{route('pelanggan.kontak')}}">Kontak</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 col-md-12 col-lg-4">
                    <div class="footer_widget">
                        <h3 class="heading">Hubungi Kami</h3>
                        <ul>
                            <li><a href="#">Jl. Contoh No. 1, Surakarta</a></li>
                            <li><a href="#">0812-3456-7890</a></li>
                            <li><a href="#">catering@contoh.com</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12">
                    <div class="copyright">
                        {{-- kredit template (Colorlib) jangan dihapus karena syarat lisensi template --}}
                        <p class="footer-text">
                            Copyright &copy; {{ date('Y') }} Layanan Catering | Template Flatter by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer-end -->
