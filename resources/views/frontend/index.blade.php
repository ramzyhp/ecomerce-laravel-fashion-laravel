@extends('frontend.layouts.master')

@section('title', 'Prolific || HOME PAGE')

@section('main-content')
    @if (count($banners) > 0)
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-indicators">
                @foreach ($banners as $key => $banner)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $key }}"
                        class="{{ $key == 0 ? 'active' : '' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $key + 1 }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach ($banners as $key => $banner)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <div class="carousel-image-container position-relative">
                            <img src="{{ $banner->photo }}" class="d-block w-100 h-auto" alt="{{ $banner->title }}">
                            <div class="carousel-overlay position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25">
                            </div>
                        </div>
                        <div class="carousel-caption d-none d-md-block position-absolute top-50 start-50 translate-middle">
                            <h1>{{ $banner->title }}</h1>
                            <p>{!! html_entity_decode($banner->description) !!}</p>
                            <a class="btn btn-lg ws-btn" href="{{ route('product-grids') }}" role="button">Shop Now <i
                                    class="far fa-arrow-alt-circle-right"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    @endif

    <section class="all-products py-5">
        <div class="container">
            <div class="d-md-flex text-center justify-content-between align-items-center mb-5">
                <h2>Pilihan Gaya Untuk Kamu</h2>
                <a href="{{ route('product-grids') }}" class="text-decoration-none">Lihat Semua</a>
            </div>
            <div class="row">
                @if (count($product_lists))
                    @foreach ($product_lists as $data)
                        <div class="col-lg-3 col-md-4 col-sm-6 product-item mb-4" data-category="{{ $data->cat_id }}">
                            <div class="card h-100">
                                @php
                                    $photos = json_decode($data->photo, true);
                                    if (!is_array($photos)) {
                                        $photos = [$data->photo]; // fallback untuk data lama
                                    }
                                @endphp
                                <a href="{{ route('product-detail', $data->slug) }}">
                                    <img src="{{ $photos[0] }}" class="card-img-top" alt="{{ $data->title }}">
                                </a>
                                <div class="card-body text-center">
                                    <h5 class="card-title">
                                        <a href="{{ route('product-detail', $data->slug) }}"
                                            class="text-decoration-none text-dark">{{ $data->title }}</a>
                                    </h5>
                                    <p class="text-muted">
                                        {{-- 💡 BAGIAN INI DIUBAH: HARGA CORET HANYA MUNCUL JIKA ADA DISKON 💡 --}}
                                        @if ($data->discount > 0)
                                            <span
                                                class="text-decoration-line-through">Rp{{ number_format($data->price, 2) }}</span>
                                        @endif
                                        <span
                                            class="fw-bold text-danger">Rp{{ number_format($data->price - ($data->discount * $data->price) / 100, 2) }}</span>
                                        {{-- 💡 AKHIR BAGIAN DIUBAH 💡 --}}
                                    </p>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('add-to-wishlist', $data->slug) }}"
                                            class="btn {{ $data->is_favorited ? 'btn-danger' : 'btn-outline-secondary' }}">
                                            <i class="fa fa-heart"></i>
                                        </a>
                                        <a href="{{ route('add-to-cart', $data->slug) }}"
                                            class="btn btn-outline-primary"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @else
                    <h4 class="text-warning text-center w-100">No products found.</h4>
                @endif
            </div>
        </div>
    </section>
@endsection
