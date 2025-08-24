<!-- Start Shop Newsletter -->
<section class="shop-newsletter section py-5">
    <div class="container">
        <div class="inner-top">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-12">
                    <!-- Start Newsletter Inner -->
                    <div class="inner text-center">
                        <h4>Newsletter</h4>
                        <p>Berlangganan newsletter kami dan dapatkan diskon <span>10%</span> untuk pembelian pertama Anda</p>
                        <form action="{{route('subscribe')}}" method="post" class="newsletter-inner d-flex justify-content-center align-items-center">
                            @csrf
                            <div class="input-group w-75">
                                <input name="email" placeholder="Alamat email Anda" required="" type="email" class="form-control">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-paper-plane"></i> Subscribe
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- End Newsletter Inner -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Shop Newsletter -->
