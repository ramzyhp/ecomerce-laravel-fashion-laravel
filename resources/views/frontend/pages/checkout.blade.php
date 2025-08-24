@extends('frontend.layouts.master')

@section('title', 'Checkout page')

@section('main-content')

    <!-- Start Checkout -->
    <section class="checkout-section py-5">
        <div class="container">
            <form class="checkout-form" id="payment-form" method="POST" action="{{ route('cart.order') }}">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <h2 class="card-title fw-bold mb-2">Checkout Disini</h2>
                                <p class="text-muted mb-4">Mohon Register agar dapat checkout lebih cepat</p>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstName" class="form-label">Nama Depan<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="firstName" name="first_name"
                                                value="{{ old('first_name') }}">
                                            @error('first_name')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="lastName" class="form-label">Nama Belakang<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="lastName" name="last_name"
                                                value="{{ old('lat_name') }}">
                                            @error('last_name')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Alamat Email<span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ old('email') }}">
                                            @error('email')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Nomor HP<span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="phone" name="phone"
                                                required value="{{ old('phone') }}">
                                            @error('phone')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="country" class="form-label">Negara<span
                                                    class="text-danger">*</span></label>
                                            <select name="country" id="country">
                                                <option value="ID">Indonesia</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address1" class="form-label">Alamat 1<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="address1" name="address1"
                                                value="{{ old('address1') }}">
                                            @error('address1')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address2" class="form-label">Alamat 2</label>
                                            <input type="text" class="form-control" id="address2" name="address2"
                                                value="{{ old('address2') }}">
                                            @error('address2')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="postCode" class="form-label">Kode Pos</label>
                                            <input type="text" class="form-control" id="postCode" name="post_code"
                                                value="{{ old('post_code') }}">
                                            @error('post_code')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <h2 class="card-title fs-4 fw-bold mb-3">Total Keranjang</h2>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-medium">Subtotal Keranjang</span>
                                    <span class="fw-bold order_subtotal"
                                        data-price="{{ Helper::totalCartPrice() }}">Rp{{ number_format(Helper::totalCartPrice(), 2) }}</span>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-medium">Biaya Pengiriman</span>
                                        @if (count(Helper::shipping()) > 0 && Helper::cartCount() > 0)
                                            <div class="shipping-select w-50">
                                                <select name="shipping" class="form-select form-select-sm">
                                                    <option value="">Pilih Pengiriman</option>
                                                    @foreach (Helper::shipping() as $shipping)
                                                        <option value="{{ $shipping->id }}" class="shippingOption"
                                                            data-price="{{ $shipping->price }}">{{ $shipping->type }}:
                                                            Rp.{{ $shipping->price }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <span>Gratis</span>
                                        @endif
                                    </div>
                                </div>

                                @if (session('coupon'))
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-medium">Kamu hemat</span>
                                        <span class="text-success coupon_price"
                                            data-price="{{ session('coupon')['value'] }}">Rp{{ number_format(session('coupon')['value'], 2) }}</span>
                                    </div>
                                @endif

                                @php
                                    $total_amount = Helper::totalCartPrice();
                                    if (session('coupon')) {
                                        $total_amount = $total_amount - session('coupon')['value'];
                                    }
                                @endphp

                                <div class="d-flex justify-content-between border-top pt-3 mt-3">
                                    <span class="fw-bold fs-5">Total</span>
                                    <span class="fw-bold fs-5"
                                        id="order_total_price">Rp{{ number_format($total_amount, 2) }}</span>
                                </div>
                            </div>

                            <div class="card-body border-top p-4">
                                <h2 class="card-title fs-4 fw-bold mb-3">Metode Pembayaran</h2>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="midtrans"
                                        value="midtrans" checked>
                                    <label class="form-check-label" for="midtrans">Midtrans</label>
                                </div>
                            </div>

                            <div class="card-body border-top p-4">
                                <div class="text-center mb-3">
                                    <img src="{{ 'backend/img/payment-method.png' }}" alt="Payment Methods"
                                        class="img-fluid">
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">CHECKOUT</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!--/ End Checkout -->

    <!-- Start Shop Services Section -->
    <section class="shop-services py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-shipping-fast fs-1 text-secondary mb-3"></i>
                            <h4 class="fs-5 fw-bold">Free Shipping</h4>
                            <p class="text-muted mb-0">Orders over $100</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-undo-alt fs-1 text-secondary mb-3"></i>
                            <h4 class="fs-5 fw-bold">Free Returns</h4>
                            <p class="text-muted mb-0">Within 30 days</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-lock fs-1 text-secondary mb-3"></i>
                            <h4 class="fs-5 fw-bold">Secure Payment</h4>
                            <p class="text-muted mb-0">100% secure payment</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-tag fs-1 text-secondary mb-3"></i>
                            <h4 class="fs-5 fw-bold">Best Price</h4>
                            <p class="text-muted mb-0">Guaranteed price</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Shop Services Section -->

    <!-- Start Newsletter Section -->
    <section class="newsletter-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 text-center">
                            <h4 class="fw-bold mb-2">Newsletter</h4>
                            <p class="mb-4">Subscribe to our newsletter and get <span class="text-primary fw-bold">10%
                                    discount</span> on your first purchase</p>
                            <form action="{{ route('subscribe') }}" method="post" class="newsletter-form">
                                @csrf
                                <div class="input-group">
                                    <input type="email" class="form-control" name="email"
                                        placeholder="Your email address" required>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-paper-plane me-1"></i> Subscribe
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Newsletter Section -->
@endsection

@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        $(document).ready(function() {
            // Update total when shipping option changes
            $('select[name=shipping]').change(function() {
                let cost = parseFloat($(this).find('option:selected').data('price')) || 0;
                let subtotal = parseFloat($('.order_subtotal').data('price'));
                let coupon = parseFloat($('.coupon_price').data('price')) || 0;
                let total = subtotal + cost - coupon;

                // Format dengan toLocaleString untuk pemisah ribuan koma dan desimal titik
                $('#order_total_price').text('Rp' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            });




        });
    </script>
    <script>
            $(document).ready(function() {
            // Tangani tombol checkout
            $('form.checkout-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit default

                // Ambil data form
                var formData = $(this).serialize();

                // Kirim data form ke server untuk mendapatkan Snap token
                $.ajax({
                    url: "{{ route('cart.order') }}", // URL untuk mendapatkan snapToken dari server
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        // Pastikan response berisi snapToken dari Midtrans
                        var snapToken = response.snapToken;

                        // Panggil pop-up Snap Midtrans
                        snap.pay(snapToken, {
                            onSuccess: function(result) {
                                console.log("Pembayaran Sukses");
                                
                                // Handle success, redirect atau tampilkan pesan
                                window.location.href = '{{ route('cartOrderSave') }}' + '?data=' + encodeURIComponent(response.encryptedData);
                            },
                            onPending: function(result) {
                                // Handle pending status
                                console.log(result);
                                alert('Pembayaran pending');
                            },
                            onError: function(result) {
                                // Handle error, redirect atau tampilkan pesan
                                console.log(result);
                                console.log('Pembayaran gagal');
                                
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });
            });
        });

    </script>
@endpush
