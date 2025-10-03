<!DOCTYPE html>
<html lang="en">
<head>
    <title>Elen - Free Bootstrap 4 Template by Colorlib</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="{{ asset('/vendor/elen/css/open-iconic-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/elen/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/elen/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/elen/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/elen/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/elen/css/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/elen/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/elen/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/elen/css/jquery.timepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/elen/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/elen/css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/elen/css/style.css') }}">

    @yield('css')
</head>
<body>

<div id="colorlib-page">
    <a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle"><i></i></a>
    <aside id="colorlib-aside" role="complementary" class="js-fullheight text-center">
        <h1 id="colorlib-logo"><a href="index.html">elen<span>.</span></a></h1>
        <nav id="colorlib-main-menu" role="navigation">
            <ul>
                <li class="colorlib-active"><a href="index.html">Home</a></li>
                <li><a href="photography.html">Photography</a></li>
                <li><a href="travel.html">Travel</a></li>
                <li><a href="fashion.html">Fashion</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
        </nav>

        <div class="colorlib-footer">
            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                Copyright &copy;<script>document.write(new Date().getFullYear());</script>
                All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a
                    href="https://colorlib.com" target="_blank">Colorlib</a>
            <ul>
                <li><a href="#"><i class="icon-facebook"></i></a></li>
                <li><a href="#"><i class="icon-twitter"></i></a></li>
                <li><a href="#"><i class="icon-instagram"></i></a></li>
                <li><a href="#"><i class="icon-linkedin"></i></a></li>
            </ul>
        </div>
    </aside> <!-- END COLORLIB-ASIDE -->

    <div id="colorlib-main">
        @yield('body')

        <footer class="ftco-footer ftco-bg-dark ftco-section">
            <div class="container px-md-5">
                <div class="row mb-5">
                    <div class="col-md">
                        <div class="ftco-footer-widget mb-4 ml-md-4">
                            <h2 class="ftco-heading-2">Category</h2>
                            <ul class="list-unstyled categories">
                                <li><a href="#">Photography <span>(6)</span></a></li>
                                <li><a href="#">Fashion <span>(8)</span></a></li>
                                <li><a href="#">Technology <span>(2)</span></a></li>
                                <li><a href="#">Travel <span>(2)</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="ftco-footer-widget mb-4">
                            <h2 class="ftco-heading-2">Archives</h2>
                            <ul class="list-unstyled categories">
                                <li><a href="#">October 2018 <span>(6)</span></a></li>
                                <li><a href="#">September 2018 <span>(6)</span></a></li>
                                <li><a href="#">August 2018 <span>(8)</span></a></li>
                                <li><a href="#">July 2018 <span>(2)</span></a></li>
                                <li><a href="#">June 2018 <span>(7)</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="ftco-footer-widget mb-4">
                            <h2 class="ftco-heading-2">Have a Questions?</h2>
                            <div class="block-23 mb-3">
                                <ul>
                                    <li><span class="icon icon-map-marker"></span><span class="text">203 Fake St. Mountain View, San Francisco, California, USA</span>
                                    </li>
                                    <li><a href="#"><span class="icon icon-phone"></span><span
                                                class="text">+2 392 3929 210</span></a>
                                    </li>
                                    <li><a href="#"><span class="icon icon-envelope"></span><span class="text">info@yourdomain.com</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            Copyright &copy;<script>document.write(new Date().getFullYear());</script>
                            All rights reserved | This template is made with <i class="icon-heart"
                                                                                aria-hidden="true"></i> by <a
                                href="https://colorlib.com" target="_blank">Colorlib</a>
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- loader -->
<div id="ftco-loader" class="show fullscreen">
    <svg class="circular" width="48px" height="48px">
        <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
        <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
                stroke="#F96D00"/>
    </svg>
</div>

<script src="{{ asset('/vendor/elen/js/jquery.min.js') }}"></script>
<script src="{{ asset('/vendor/elen/js/jquery-migrate-3.0.1.min.js') }}"></script>
<script src="{{ asset('/vendor/elen/js/popper.min.js') }}"></script>
<script src="{{ asset('/vendor/elen/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/vendor/elen/js/jquery.easing.1.3.js') }}"></script>
<script src="{{ asset('/vendor/elen/js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('/vendor/elen/js/jquery.stellar.min.js') }}"></script>
<script src="{{ asset('/vendor/elen/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('/vendor/elen/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('/vendor/elen/js/aos.js') }}"></script>
<script src="{{ asset('/vendor/elen/js/jquery.animateNumber.min.js') }}"></script>
<script src="{{ asset('/vendor/elen/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('/vendor/elen/js/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('/vendor/elen/js/scrollax.min.js') }}"></script>
<script src="{{ asset('/vendor/elen/js/main.js') }}"></script>

@yield('js')

</body>
</html>
