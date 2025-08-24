@extends('frontend.layouts.master')

@section('meta')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='copyright' content=''>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="online shop, purchase, cart, ecommerce site, best online shopping">
    <meta name="description" content="{{ $product_detail->summary }}">
    <meta property="og:url" content="{{ route('product-detail', $product_detail->slug) }}">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $product_detail->title }}">
    <meta property="og:image" content="{{ $product_detail->photo }}">
    <meta property="og:description" content="{{ $product_detail->description }}">
@endsection

@section('title', 'Prolific || PRODUCT DETAIL')

@section('main-content')

    <div class="container mb-5">
        <div class="row align-items-start">
            <div class="col-md-3">
                <h2>{{ $product_detail->title }}</h2>
                <div class="d-flex align-items-center mb-3">
                    <div class="rating me-2">
                        @php
                            $rate = ceil($product_detail->getReview->avg('rate'));
                        @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($rate >= $i)
                                <i class="fa fa-star text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i> {{-- 💡 UBAH INI: fa fa-star-o jadi far fa-star 💡 --}}
                            @endif
                        @endfor
                    </div>
                </div>
                <p>{!! $product_detail->summary !!}</p>
            </div>

            <div class="col-md-5">
                <div class="row">
                    @php
                        $photos = json_decode($product_detail->photo, true);
                        if (!is_array($photos)) {
                            $photos = [$product_detail->photo]; // fallback untuk data lama
                        }
                    @endphp
                    <div class="carousel-thumbnails d-none d-md-flex flex-column gap-2 col-md-2 hidden-md">
                        @foreach ($photos as $key => $img)
                            <img src="{{ $img }}" alt="Thumbnail {{ $key + 1 }}" class="img-thumbnail mb-2"
                                data-bs-target="#productCarousel" data-bs-slide-to="{{ $key }}"
                                @if ($key == 0) class="active" @endif>
                        @endforeach
                    </div>
                    <div id="productCarousel" class="carousel slide col-md-10" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($photos as $key => $img)
                                <div class="carousel-item @if ($key == 0) active @endif">
                                    <img src="{{ $img }}" class="d-block w-100" alt="Product {{ $key + 1 }}">
                                </div>
                            @endforeach
                        </div>
                        @if (count($photos) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            <!-- Photo counter -->
                            <div class="position-absolute top-0 end-0 m-2 bg-dark text-white px-2 py-1 rounded"
                                style="font-size: 12px;">
                                <i class="fa fa-images"></i> {{ count($photos) }} Foto
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                @php
                    $after_discount =
                        $product_detail->price - ($product_detail->price * $product_detail->discount) / 100;
                @endphp
                <h3 class="mb-2">Rp{{ number_format($after_discount, 2) }}</h3>
                @if ($product_detail->discount > 0)
                    <div class="price">
                        @if ($product_detail->discount > 0)
                            <span
                                class="text-decoration-line-through">Rp{{ number_format($product_detail->price, 2) }}</span>
                        @endif
                        Rp{{ number_format($product_detail->price - ($product_detail->discount * $product_detail->price) / 100, 2) }}
                    </div>
                @endif

                @if ($product_detail->size)
                    <h5 class="mt-4">UKURAN</h5>
                    <div class="d-flex flex-wrap gap-2 my-4">
                        @php
                            $sizes = explode(',', $product_detail->size);
                        @endphp
                        @foreach ($sizes as $size)
                            <div class="col-3 text-center border rounded p-2">{{ $size }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('single-add-to-cart') }}" method="POST">
                    @csrf
                    <input type="hidden" name="slug" value="{{ $product_detail->slug }}">

                    <div class="mb-4">
                        <h5>Quantity:</h5>
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary" id="decrement">
                                <i class="fa fa-minus"></i>
                            </button>
                            <input type="text" name="quant[1]" class="form-control text-center" value="1"
                                id="quantity" min="1" max="{{ $product_detail->stock }}">
                            <button type="button" class="btn btn-outline-secondary" id="increment">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <h6>
                            Kategori:
                            <span class="fw-normal">
                                <a href="{{ route('product-cat', $product_detail->cat_info['slug']) }}"
                                    class="text-decoration-none">
                                    {{ $product_detail->cat_info['title'] }}
                                </a>
                            </span>
                        </h6>
                        @if ($product_detail->sub_cat_info)
                            <h6>
                                Sub Kategori:
                                <span class="fw-normal">
                                    <a href="{{ route('product-sub-cat', [$product_detail->cat_info['slug'], $product_detail->sub_cat_info['slug']]) }}"
                                        class="text-decoration-none">
                                        {{ $product_detail->sub_cat_info['title'] }}
                                    </a>
                                </span>
                            </h6>
                        @endif
                        <h6>
                            Stock:
                            @if ($product_detail->stock > 0)
                                <span class="badge bg-success">{{ $product_detail->stock }}</span>
                            @else
                                <span class="badge bg-danger">Stok Habis</span>
                            @endif
                        </h6>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-dark">
                            <i class="fa fa-shopping-cart"></i> Tambah ke Keranjang
                        </button>
                        <a href="{{ route('add-to-wishlist', $product_detail->slug) }}"
                            class="btn {{ $product_detail->is_favorited ? 'btn-danger' : 'btn-outline-secondary' }}">
                            <i class="fa fa-heart"></i>
                            {{ $product_detail->is_favorited ? ' Hapus dari Favorit' : ' Tambah ke Favorit' }}

                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                            data-bs-target="#description" type="button" role="tab" aria-controls="description"
                            aria-selected="true">Deskripsi</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                            type="button" role="tab" aria-controls="reviews" aria-selected="false">Ulasan</button>
                    </li>
                </ul>
                <div class="tab-content p-4 border border-top-0 rounded-bottom" id="productTabsContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel"
                        aria-labelledby="description-tab">
                        {!! $product_detail->description !!}
                    </div>
                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        <div class="mb-4">
                            <h4>Ringkasan Rating</h4>
                            <div class="d-flex align-items-center">
                                <h2 class="me-3 mb-0">{{ ceil($product_detail->getReview->avg('rate')) }}/5</h2>
                                <div>
                                    <div class="rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if (ceil($product_detail->getReview->avg('rate')) >= $i)
                                                <i class="fa fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i> {{-- 💡 UBAH INI: fa fa-star-o jadi far fa-star 💡 --}}
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="mb-0">Berdasarkan {{ $product_detail->getReview->count() }} ulasan</p>
                                </div>
                            </div>
                        </div>

                        <div class="customer-reviews">
                            <h4 class="mb-4">Ulasan Customer</h4>
                            @foreach ($product_detail['getReview'] as $review)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex mb-3">
                                            <div class="flex-shrink-0">
                                                @if ($review->user_info['photo'])
                                                    <img src="{{ $review->user_info['photo'] }}"
                                                        alt="{{ $review->user_info['name'] }}" class="rounded-circle"
                                                        width="50" height="50">
                                                @else
                                                    <img src="{{ asset('backend/img/avatar.png') }}" alt="Profile"
                                                        class="rounded-circle" width="50" height="50">
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h5 class="mb-1">{{ $review->user_info['name'] }}</h5>
                                                <div class="rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($review->rate >= $i)
                                                            <i class="fa fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mb-0">{{ $review->review }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Add Review Form -->
                        @auth
                            <div class="add-review-form mt-5">
                                <h4 class="mb-3">Tulis Ulasan</h4>
                                <form id="reviewForm" method="post"
                                    action="{{ route('review.store', $product_detail->slug) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Rating <span class="text-danger">*</span></label>
                                            <div class="rating-input">
                                                <div class="star-rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="star-rating__ico far fa-star text-warning"
                                                            data-rating="{{ $i }}"
                                                            style="font-size: 2rem; cursor: pointer;"></i>
                                                    @endfor
                                                    <input type="hidden" name="rate" id="selected-rating"
                                                        value="{{ old('rate', 0) }}">
                                                </div>
                                                @error('rate')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Ulasan Anda</label>
                                            <textarea name="review" class="form-control" rows="4" placeholder="Tulis ulasan Anda tentang produk ini..."></textarea>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="login-prompt mt-5 text-center p-4 bg-light rounded">
                                <p class="mb-2">Anda perlu masuk untuk memberikan ulasan</p>
                                <a href="{{ route('login.form') }}" class="btn btn-primary me-2">Login</a>
                                <a href="{{ route('register.form') }}" class="btn btn-outline-primary">Register</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        <div class="container py-5">
            <div class="row">
                <div class="col-12 mb-4">
                    <h2>Rekomendasi Untuk Kamu</h2>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                @foreach ($product_detail->rel_prods as $data)
                    @if ($data->id !== $product_detail->id)
                        <div class="col">
                            <div class="card text-center h-100">
                                @php
                                    $photos = json_decode($data->photo, true);
                                    if (!is_array($photos)) {
                                        $photos = [$data->photo]; // fallback untuk data lama
                                    }
                                @endphp
                                <a href="{{ route('product-detail', $data->slug) }}">
                                    <img src="{{ $photos[0] }}" class="card-img-top img-fluid"
                                        alt="{{ $data->title }}">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a class="text-decoration-none text-dark"
                                            href="{{ route('product-detail', $data->slug) }}">{{ $data->title }}</a>
                                    </h5>
                                    <p class="text-muted">
                                        @if ($data->discount > 0)
                                            <span
                                                class="text-decoration-line-through">Rp{{ number_format($data->price, 2) }}</span>
                                        @endif
                                        <span
                                            class="fw-bold text-danger">Rp{{ number_format($data->price - ($data->discount * $data->price) / 100, 2) }}</span>
                                    </p>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('add-to-wishlist', $data->slug) }}"
                                            class="btn {{ $data->is_favorited ? 'btn-danger' : 'btn-outline-secondary' }}">
                                            <i class="fa fa-heart"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    </section>

@endsection


@push('scripts')
    <script>
        // ... script untuk add and minus quantity yang sudah ada ...

        // 💡 BAGIAN INI DITAMBAHKAN UNTUK RATING BINTANG INTERAKTIF 💡
        $(document).ready(function() {
            var $stars = $('.star-rating__ico');
            var $hiddenInput = $('#selected-rating');
            var initialRate = parseInt($hiddenInput.val());

            // Fungsi untuk mengisi bintang
            function fillStars(count) {
                $stars.each(function(index) {
                    if (index < count) {
                        $(this).removeClass('far').addClass('fa'); // Bintang penuh
                    } else {
                        $(this).removeClass('fa').addClass('far'); // Bintang kosong
                    }
                });
            }

            // Inisialisasi tampilan bintang berdasarkan nilai yang sudah ada (misal dari old('rate'))
            if (initialRate > 0) {
                fillStars(initialRate);
            }

            // Hover effect
            $stars.on('mouseover', function() {
                var currentRating = $(this).data('rating');
                fillStars(currentRating);
            }).on('mouseout', function() {

                fillStars(parseInt($hiddenInput.val()));
            });

            // Click event
            $stars.on('click', function() {
                var clickedRating = $(this).data('rating');
                $hiddenInput.val(clickedRating); // Set nilai di hidden input
                fillStars(clickedRating); // Tetap isi bintang setelah klik
            });
        });
        // 💡 AKHIR BAGIAN DITAMBAHKAN 💡
    </script>
@endpush
