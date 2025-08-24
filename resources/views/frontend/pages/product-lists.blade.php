@extends('frontend.layouts.master')

@section('title', 'Prolific || PRODUCT PAGE')

@section('main-content')


    <form action="{{ route('shop.filter') }}" method="POST">
        @csrf
        <section class="product-area shop-sidebar shop-list section py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="shop-sidebar">
                            <!-- Single Widget -->
                            <div class="single-widget category">
                                <h2>Products</h2>
                                <hr>
                                <ul class="list-unstyled">
                                    @php
                                        $menu = App\Models\Category::getAllParentWithChild();
                                    @endphp
                                    @if ($menu)
                                        @foreach ($menu as $cat_info)
                                            <li class="fs-3">
                                                @if ($cat_info->child_cat->count() > 0)
                                                    <a href="{{ route('product-cat', $cat_info->slug) }}"
                                                        class="text-dark text-decoration-none
                                                   {{ request()->routeIs('product-cat') && request()->segment(2) == $cat_info->slug ? 'active' : '' }}">
                                                        {{ $cat_info->title }}
                                                    </a>
                                                    <ul class="list-unstyled ms-3">
                                                        @foreach ($cat_info->child_cat as $sub_menu)
                                                            <li>
                                                                <a href="{{ route('product-sub-cat', [$cat_info->slug, $sub_menu->slug]) }}"
                                                                    class="text-dark text-decoration-none
                                                               {{ request()->routeIs('product-sub-cat') && request()->segment(2) == $cat_info->slug && request()->segment(3) == $sub_menu->slug ? 'active' : '' }}">
                                                                    {{ $sub_menu->title }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <a href="{{ route('product-cat', $cat_info->slug) }}"
                                                        class="text-dark text-decoration-none
                                                   {{ request()->routeIs('product-cat') && request()->segment(2) == $cat_info->slug ? 'active' : '' }}">
                                                        {{ $cat_info->title }}
                                                    </a>
                                                @endif
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            <!--/ End Single Widget -->
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-12">
                        <div class="row">
                            @if (count($products))
                                @foreach ($products as $data)
                                    <div class="col-md-4 col-sm-6 product-item mb-4" data-category="{{ $data->cat_id }}">
                                        <div class="card h-100">
                                            @php
                                                $photos = json_decode($data->photo, true);
                                                if (!is_array($photos)) {
                                                    $photos = [$data->photo]; // fallback untuk data lama
                                                }
                                            @endphp
                                            <a href="{{ route('product-detail', $data->slug) }}">
                                                <img src="{{ $photos[0] }}" class="card-img-top"
                                                    alt="{{ $data->title }}">
                                            </a>
                                            <div class="card-body text-center">
                                                <h5 class="card-title">
                                                    <a href="{{ route('product-detail', $data->slug) }}"
                                                        class="text-decoration-none text-dark">{{ $data->title }}</a>
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
                                                        class="btn btn-outline-danger"><i class="fa fa-heart"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h4 class="text-warning text-center w-100">Produk tidak ditemukan.</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>

@endsection

@push('styles')
    <style>
        /* Active category link style */
        a.active {
            font-weight: bold;
            color: #d9534f;
            /* Change color as desired */
        }

        /* Underline effect on hover */
        a:hover {
            text-decoration: underline;
        }
    </style>
@endpush
