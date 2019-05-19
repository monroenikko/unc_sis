
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 footer-info">
                        <h3>St. John</h3>
                        <p>SAINT JOHN ACADEMY was born out of a clamor for a Catholic educational institution which will provide a deeply-rooted Christian formation to the young and which can supply the volunteers for the Parochial catechetical program at the public schools within the parish... <a href="{{ route('history') }}">readmore</a></p>
                    </div>
                    <div class="col-lg-4 col-md-6 footer-links">
                        <h4>About SJA</h4>
                        <ul>
                            <li><i class="ion-ios-arrow-right"></i> <a href="{{ route('home_page') }}">Home</a></li>
                            <li><i class="ion-ios-arrow-right"></i> <a href="{{ route('vision_mission') }}">Vision Mission</a></li>
                            <li><i class="ion-ios-arrow-right"></i> <a href="{{ route('history') }}">Hymn</a></li>
                            <li><i class="ion-ios-arrow-right"></i> <a href="{{ route('faculty_staff') }}">Faculty and Staff</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-6 footer-contact">
                        <h4>Contact Us</h4>
                        <p>
                            Rizal Street, Dinalupihan
                            <br> Bataan, 2110 Dinalupihan
                            <br> Bataan
                            <br>
                            <strong>Phone:</strong> (047) 481 1762
                            <br>
                        </p>
                        {{-- <div class="social-links">
                            <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
                            <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
                            <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
                            <a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>
                            <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
                        </div> --}}
                    </div>
                    <div {{-- class="col-lg-3 col-md-6 footer-newsletter">
                        <h4>Our Newsletter</h4>
                        <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna veniam enim veniam illum dolore legam minim quorum culpa amet magna export quem marada parida nodela caramase seza.</p>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong>St. John</strong>. All Rights Reserved
            </div>
            {{-- <div class="credits">
                Best <a href="https://bootstrapmade.com/">Bootstrap Templates</a> by BootstrapMade
            </div> --}}
        </div>
    </footer>
    <!-- #footer -->
    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
    <!-- JavaScript Libraries -->
    <script src="{{ asset('theme/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('theme/lib/jquery/jquery-migrate.min.js') }}"></script>
    <script src="{{ asset('theme/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('theme/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('theme/lib/superfish/hoverIntent.js') }}"></script>
    <script src="{{ asset('theme/lib/superfish/superfish.min.js') }}"></script>
    <script src="{{ asset('theme/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('theme/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('theme/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('theme/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('theme/lib/isotope/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('theme/lib/lightbox/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('theme/lib/touchSwipe/jquery.touchSwipe.min.js') }}"></script>
    <!-- Contact Form JavaScript File -->
    <script src="{{ asset('theme/contactform/contactform.js') }}"></script>
    <!-- Template Main Javascript File -->
    <script src="{{ asset('theme/js/main.js') }}"></script>
</body>

</html>