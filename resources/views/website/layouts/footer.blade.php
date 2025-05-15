<footer class="text-light pt-5 pb-3 mt-5" style="background-color: rgba(0, 0, 0, 0.8);">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="text-primary">TrackNet</h5>
                <p>Your one-stop shop for all computer parts and accessories.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="text-light text-decoration-none">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-light text-decoration-none">Products</a></li>
                    <li><a href="#" class="text-light text-decoration-none">About Us</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Contact Us</h5>
                <address>
                    143 ByteMe Street<br>
                    Davao City, TC 12345<br>
                    <abbr title="Phone">Phone:</abbr> (123) 8-70000
                </address>
            </div>
        </div>
        <hr class="border-light">
        <div class="text-center">
            <p class="mb-0">&copy; {{ date('Y') }} <span class="text-primary">Tracknet</span>. All rights reserved.</p>
        </div>
    </div>
</footer>
