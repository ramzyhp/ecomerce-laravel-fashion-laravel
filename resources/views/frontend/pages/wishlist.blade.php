@extends('frontend.layouts.master')
@section('title', 'Wishlist Page')
@section('main-content')
    <!-- Breadcrumbs -->
    <div class="container mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Favorit</li>
            </ol>
        </nav>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Shopping Cart -->
    <div class="shopping-cart section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @if (Helper::getAllProductFromWishlist() && count(Helper::getAllProductFromWishlist()) > 0)
                        <div class="table-responsive shadow-sm bg-white rounded">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 10%;">Produk</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col" class="text-center">Total</th>
                                        <th scope="col" class="text-center">Tambah ke Keranjang</th>
                                        <th scope="col" class="text-center">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (Helper::getAllProductFromWishlist() as $key => $wishlist)
                                        @php
                                            $product = $wishlist->product;
                                            $photos = is_string($product['photo'])
                                                ? json_decode($product['photo'], true)
                                                : $product['photo'];
                                            $photoUrl =
                                                is_array($photos) && !empty($photos)
                                                    ? $photos[0]
                                                    : asset('backend/img/thumbnail-default.jpg');
                                            $after_discount =
                                                $product->price - ($product->price * $product->discount) / 100;
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('product-detail', $product['slug']) }}">
                                                    <img src="{{ $photoUrl }}" class="img-fluid rounded"
                                                        style="max-width: 75px;" alt="{{ $product['title'] }}">
                                                </a>
                                            </td>

                                            <td>
                                                <a href="{{ route('product-detail', $product['slug']) }}"
                                                    class="text-dark fw-bold text-decoration-none">
                                                    {{ $product['title'] }}
                                                </a>
                                                <p class="text-muted small mb-0">{!! Str::limit(strip_tags($product['summary']), 75) !!}</p>
                                            </td>

                                            <td class="text-center">
                                                <strong
                                                    class="d-block">Rp{{ number_format($after_discount, 0, ',', '.') }}</strong>
                                            </td>

                                            <td class="text-center">
                                                <a href="{{ route('add-to-cart', $product['slug']) }}"
                                                    class="btn btn-dark btn-sm" title="Tambah ke Keranjang">
                                                    <i class="fa fa-shopping-cart"></i>
                                                </a>
                                            </td>

                                            <td class="text-center">
                                                <a href="{{ route('wishlist-delete', $wishlist->id) }}"
                                                    class="btn btn-outline-danger btn-sm delete-wishlist"
                                                    title="Hapus dari Favorit">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center p-5 bg-light rounded shadow-sm">
                            <i class="fa fa-heart-broken fa-4x text-muted mb-3"></i>
                            <h4 class="mb-3">Daftar Favorit Anda Kosong</h4>
                            <p class="text-muted">Ayo, temukan produk yang Anda sukai!</p>
                            <a href="{{ route('product-grids') }}" class="btn btn-primary mt-3">
                                <i class="fa fa-shopping-bag"></i> Mulai Belanja
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--/ End Shopping Cart -->

    <!-- Start Area Layanan Toko -->
    <section class="shop-services section py-5">
        <div class="container">
            <div class="row gap-3 gap-lg-0">
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Layanan Tunggal -->
                    <div class="single-service text-center p-4 border rounded shadow-sm">
                        <i class="fas fa-shipping-fast mb-3"></i>
                        <h4>Pengiriman Gratis</h4>
                        <p>Pesanan di atas $100</p>
                    </div>
                    <!-- End Layanan Tunggal -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Layanan Tunggal -->
                    <div class="single-service text-center p-4 border rounded shadow-sm">
                        <i class="fas fa-undo-alt mb-3"></i>
                        <h4>Pengembalian Gratis</h4>
                        <p>Pengembalian dalam 30 hari</p>
                    </div>
                    <!-- End Layanan Tunggal -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Layanan Tunggal -->
                    <div class="single-service text-center p-4 border rounded shadow-sm">
                        <i class="fas fa-lock mb-3"></i>
                        <h4>Pembayaran Aman</h4>
                        <p>Pembayaran 100% aman</p>
                    </div>
                    <!-- End Layanan Tunggal -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Layanan Tunggal -->
                    <div class="single-service text-center p-4 border rounded shadow-sm">
                        <i class="fas fa-tag mb-3"></i>
                        <h4>Harga Terbaik</h4>
                        <p>Harga yang dijamin</p>
                    </div>
                    <!-- End Layanan Tunggal -->
                </div>
            </div>
        </div>
    </section>
    <!-- End Area Layanan Toko -->
    @include('frontend.layouts.newsletter')



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close"
                            aria-hidden="true"></span></button>
                </div>
                <div class="modal-body">
                    <div class="row no-gutters">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <!-- Product Slider -->
                            <div class="product-gallery">
                                <div class="quickview-slider-active">
                                    <div class="single-slider">
                                        <img src="images/modal1.jpg" alt="#">
                                    </div>
                                    <div class="single-slider">
                                        <img src="images/modal2.jpg" alt="#">
                                    </div>
                                    <div class="single-slider">
                                        <img src="images/modal3.jpg" alt="#">
                                    </div>
                                    <div class="single-slider">
                                        <img src="images/modal4.jpg" alt="#">
                                    </div>
                                </div>
                            </div>
                            <!-- End Product slider -->
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="quickview-content">
                                <h2>Flared Shift Dress</h2>
                                <div class="quickview-ratting-review">
                                    <div class="quickview-ratting-wrap">
                                        <div class="quickview-ratting">
                                            <i class="yellow fa fa-star"></i>
                                            <i class="yellow fa fa-star"></i>
                                            <i class="yellow fa fa-star"></i>
                                            <i class="yellow fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                        <a href="#"> (1 customer review)</a>
                                    </div>
                                    <div class="quickview-stock">
                                        <span><i class="fa fa-check-circle-o"></i> in stock</span>
                                    </div>
                                </div>
                                <h3>$29.00</h3>
                                <div class="quickview-peragraph">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia iste laborum ad
                                        impedit pariatur esse optio tempora sint ullam autem deleniti nam in quos qui nemo
                                        ipsum numquam.</p>
                                </div>
                                <div class="size">
                                    <div class="row">
                                        <div class="col-lg-6 col-12">
                                            <h5 class="title">Size</h5>
                                            <select>
                                                <option selected="selected">s</option>
                                                <option>m</option>
                                                <option>l</option>
                                                <option>xl</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 col-12">
                                            <h5 class="title">Color</h5>
                                            <select>
                                                <option selected="selected">orange</option>
                                                <option>purple</option>
                                                <option>black</option>
                                                <option>pink</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="quantity">
                                    <!-- Input Order -->
                                    <div class="input-group">
                                        <div class="button minus">
                                            <button type="button" class="btn btn-primary btn-number" disabled="disabled"
                                                data-type="minus" data-field="quant[1]">
                                                <i class="ti-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" name="quant[1]" class="input-number" data-min="1"
                                            data-max="1000" value="1">
                                        <div class="button plus">
                                            <button type="button" class="btn btn-primary btn-number" data-type="plus"
                                                data-field="quant[1]">
                                                <i class="ti-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!--/ End Input Order -->
                                </div>
                                <div class="add-to-cart">
                                    <a href="#" class="btn">Add to cart</a>
                                    <a href="#" class="btn min"><i class="ti-heart"></i></a>
                                    <a href="#" class="btn min"><i class="fa fa-compress"></i></a>
                                </div>
                                <div class="default-social">
                                    <h4 class="share-now">Share:</h4>
                                    <ul>
                                        <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li><a class="youtube" href="#"><i class="fa fa-pinterest-p"></i></a></li>
                                        <li><a class="dribbble" href="#"><i class="fa fa-google-plus"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal end -->

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
@endpush
