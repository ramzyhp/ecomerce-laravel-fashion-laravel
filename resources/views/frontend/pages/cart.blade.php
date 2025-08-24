@extends('frontend.layouts.master')
@section('title', 'Cart Page')
@section('main-content')

    <div class="shopping-cart section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table shopping-summery">
                            <thead>
                                <tr class="main-heading">
                                    <th>Produk</th>
                                    <th>Nama</th>
                                    <th class="text-center">Harga Unit</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Size</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center"><i class="fa fa-trash remove-icon"></i></th>
                                </tr>
                            </thead>
                            <tbody id="cart_item_list">
                                {{-- 💡 TETAP PERTAHANKAN FORM INI SESUAI PERMINTAAN 💡 --}}
                                <form action="{{ route('cart.update') }}" method="POST">
                                    @csrf
                                    @if (Helper::getAllProductFromCart())
                                        @foreach (Helper::getAllProductFromCart() as $key => $cart)
                                            <tr>
                                                @php
                                                    $photos = json_decode($cart->product['photo'], true);
                                                    if (!is_array($photos)) {
                                                        $photos = [$cart->product['photo']]; // fallback untuk data lama
                                                    }
                                                @endphp
                                                <td class="image" data-title="No">
                                                    <img src="{{ $photos[0] }}" class="img-fluid rounded"
                                                        style="max-height: 100px" alt="{{ $cart->product['title'] }}">
                                                </td>
                                                <td class="product-des" data-title="Description">
                                                    <p class="product-name">
                                                        <a href="{{ route('product-detail', $cart->product['slug']) }}"
                                                            target="_blank">
                                                            {{ $cart->product['title'] }}
                                                        </a>
                                                    </p>
                                                    <p class="product-des">{!! $cart['summary'] !!}</p>
                                                </td>
                                                <td class="price" data-title="Price">
                                                    <span>Rp{{ number_format($cart['price'], 2) }}</span>
                                                </td>
                                                <td class="qty" data-title="Qty">
                                                    <div class="d-flex gap-2">
                                                        <div class="button minus">
                                                            {{-- 💡 HAPUS disabled="disabled" DARI SINI AGAR BISA DIKONTROL JS 💡 --}}
                                                            <button type="button" class="btn btn-primary btn-number"
                                                                data-type="minus" data-field="quant[{{ $key }}]">
                                                                <i class="fa fa-minus"></i>
                                                            </button>
                                                        </div>
                                                        <input type="text" name="quant[{{ $key }}]"
                                                            class="rounded px-2 input-number" data-min="1" data-max="100"
                                                            value="{{ $cart->quantity }}"
                                                            data-cart-id="{{ $cart->id }}"> {{-- 💡 Tambahkan data-cart-id 💡 --}}
                                                        <input type="hidden" name="qty_id[]" value="{{ $cart->id }}">
                                                        {{-- Ini tetap dibutuhkan untuk form submission jika tombol "Update" dipakai --}}
                                                        <div class="button plus">
                                                            <button type="button" class="btn btn-primary btn-number"
                                                                data-type="plus" data-field="quant[{{ $key }}]">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center" data-title="Size">
                                                    <span class="text-capitalize">{{ $cart['size'] }}</span>
                                                </td>
                                                <td class="total-amount cart_single_price" data-title="Total">
                                                    <span class="money">Rp{{ $cart['amount'] }}</span>
                                                </td>
                                                <td class="action text-center" data-title="Remove">
                                                    <a href="{{ route('cart-delete', $cart->id) }}">
                                                        <i class="fa fa-trash text-danger"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="5"></td>
                                            <td class="float-right">
                                                <button class="btn btn-primary" type="submit">Update</button>
                                                {{-- 💡 TOMBOL UPDATE TETAP ADA 💡 --}}
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="6">
                                                Belum ada produk di keranjang
                                                <a href="{{ route('product-grids') }}" class="text-primary">Lanjut
                                                    Belanja</a>
                                            </td>
                                        </tr>
                                    @endif
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="total-amount">
                        <div class="row">
                            <div class="col-lg-8 col-md-5 col-12">
                                <div class="left">
                                    <div class="coupon">
                                        <h4>Kode Kupon</h4>
                                        <p>Masukkan kode kupon Anda jika punya.</p>
                                        <form action="{{ route('coupon-store') }}" method="POST">
                                            @csrf
                                            <input name="code" class="form-control" placeholder="Masukkan kode kupon"
                                                type="text">
                                            <button class="btn btn-secondary mt-3" type="submit">Apply</button>
                                        </form>

                                        {{-- 💡 BAGIAN INI DITAMBAHKAN UNTUK MENAMPILKAN KUPON TERSEDIA 💡 --}}
                                        @if (isset($coupons) && $coupons->count() > 0)
                                            <h5 class="mt-4">Kupon Tersedia:</h5>
                                            <ul class="list-group">
                                                @foreach ($coupons as $coupon)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <strong>{{ $coupon->code }}</strong>
                                                        @if ($coupon->type == 'fixed')
                                                            <span>Diskon Rp{{ number_format($coupon->value, 2) }}</span>
                                                        @else
                                                            <span>Diskon {{ $coupon->value }}%</span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="mt-3">Tidak ada kupon yang tersedia saat ini.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-7 col-12">
                                <div class="right">
                                    <ul class="list-unstyled">
                                        <li class="order_subtotal" data-price="{{ Helper::totalCartPrice() }}">
                                            Subtotal <span>Rp{{ number_format(Helper::totalCartPrice(), 2) }}</span>
                                        </li>

                                        @if (session()->has('coupon'))
                                            <li class="coupon_price" data-price="{{ Session::get('coupon')['value'] }}">
                                                Kamu hemat
                                                <span>Rp{{ number_format(Session::get('coupon')['value'], 2) }}</span>
                                            </li>
                                        @endif

                                        @php
                                            $total_amount = Helper::totalCartPrice();
                                            if (session()->has('coupon')) {
                                                $total_amount = $total_amount - Session::get('coupon')['value'];
                                            }
                                        @endphp
                                        <li class="last" id="order_total_price">
                                            Total <span>Rp{{ number_format($total_amount, 2) }}</span>
                                        </li>
                                    </ul>
                                    <div class="button5">
                                        <a href="{{ route('checkout') }}" class="btn btn-dark">Checkout <i
                                                class="fa fa-long-arrow-right"></i></a>
                                        <a href="{{ route('product-grids') }}" class="btn btn-secondary">Lanjut
                                            Belanja</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="shop-services section py-5">
        <div class="container">
            <div class="row gap-3 gap-lg-0">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-service text-center p-4 border rounded shadow-sm">
                        <i class="fas fa-shipping-fast mb-3"></i>
                        <h4>Pengiriman Gratis</h4>
                        <p>Pesanan di atas $100</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-service text-center p-4 border rounded shadow-sm">
                        <i class="fas fa-undo-alt mb-3"></i>
                        <h4>Pengembalian Gratis</h4>
                        <p>Pengembalian dalam 30 hari</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-service text-center p-4 border rounded shadow-sm">
                        <i class="fas fa-lock mb-3"></i>
                        <h4>Pembayaran Aman</h4>
                        <p>Pembayaran 100% aman</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-service text-center p-4 border rounded shadow-sm">
                        <i class="fas fa-tag mb-3"></i>
                        <h4>Harga Terbaik</h4>
                        <p>Harga yang dijamin</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('frontend.layouts.newsletter')

@endsection

@push('styles')
    <style>
        .form-select .nice-select {
            border: none;
            border-radius: 0;
            height: 40px;
            background: #f6f6f6 !important;
            padding-left: 45px;
            padding-right: 40px;
        }

        .list li {
            margin-bottom: 0 !important;
        }

        .list li:hover {
            background: #F7941D !important;
            color: white !important;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {

            .shopping-summery th,
            .shopping-summery td {
                font-size: 12px;
            }

            .shopping-summery td .product-des {
                max-width: 100px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .total-amount .right ul {
                font-size: 14px;
            }

            .input-group {
                flex-direction: column;
            }

            .button {
                width: 100%;
                text-align: center;
            }

            .button5 {
                display: flex;
                flex-direction: column;
            }

            .button5 a {
                margin-bottom: 10px;
            }
        }

        /* For Mobile */
        @media (max-width: 576px) {
            .shopping-summery td .product-des {
                display: none;
            }

            .shopping-summery td .price,
            .shopping-summery td .qty {
                text-align: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $("select.select2").select2();
            $('select.nice-select').niceSelect();
        });

        $(document).ready(function() {
            $('.shipping select[name=shipping]').change(function() {
                let cost = parseFloat($(this).find('option:selected').data('price')) || 0;
                let subtotal = parseFloat($('.order_subtotal').data('price'));
                let coupon = parseFloat($('.coupon_price').data('price')) || 0;
                $('#order_total_price span').text('$' + (subtotal + cost - coupon).toFixed(2));
            });
        });

        // BAGIAN INI UNTUK FUNGSI QUANTITY +/-
        $(document).ready(function() {
            $('.btn-number').click(function(e) {
                e.preventDefault(); // Mencegah perilaku default tombol

                fieldName = $(this).attr('data-field');
                type = $(this).attr('data-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseInt(input.val());

                if (!isNaN(currentVal)) {
                    if (type == 'minus') {
                        if (currentVal > input.attr('data-min')) {
                            input.val(currentVal - 1).change(); // Trigger change event
                        }
                    } else if (type == 'plus') {
                        if (currentVal < input.attr('data-max')) {
                            input.val(currentVal + 1).change(); // Trigger change event
                        }
                    }
                } else {
                    input.val(input.attr('data-min')); // Jika input bukan angka, set ke nilai minimum
                }
                // Panggil fungsi toggleButtonsState setelah setiap klik
                toggleButtonsState(input);
            });

            $('.input-number').focusin(function() {
                $(this).data('oldValue', $(this).val());
            });

            $('.input-number').change(function() {
                minValue = parseInt($(this).attr('data-min'));
                maxValue = parseInt($(this).attr('data-max'));
                valueCurrent = parseInt($(this).val());

                name = $(this).attr('name');
                if (valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled');
                } else {
                    alert('Maaf, kuantitas minimum adalah ' + minValue);
                    $(this).val($(this).data('oldValue'));
                }
                if (valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled');
                } else {
                    alert('Maaf, kuantitas maksimum adalah ' + maxValue);
                    $(this).val($(this).data('oldValue'));
                }

                // 💡 BAGIAN INI DITAMBAHKAN/DIUBAH UNTUK UPDATE TOTAL & SUBTOTAL 💡
                // Ketika kuantitas berubah, update total harga item dan subtotal keranjang
                let row = $(this).closest('tr');
                let price = parseFloat(row.find('.price span').text().replace('Rp', '').replace(/,/g, ''));
                let newQuantity = parseInt($(this).val());
                let newAmount = price * newQuantity;

                // Update total harga per item
                row.find('.cart_single_price span').text('Rp' + newAmount.toLocaleString('id-ID', {
                    minimumFractionDigits: 2
                }));

                // Hitung ulang total keranjang
                updateCartTotals(); // Panggil fungsi update total keranjang

                // 💡 HAPUS BLOK AJAX INI KARENA KITA KEMBALI KE UPDATE MANUAL 💡
                // let cartId = $(this).data('cart-id');
                // let newQty = $(this).val();
                // $.ajax({ ... });
                // 💡 AKHIR BAGIAN DIHAPUS 💡
            });

            $(".input-number").keydown(function(e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A, Command+A
                    (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode >
                        105)) {
                    e.preventDefault();
                }
            });

            // 💡 FUNGSI BARU UNTUK MENGATUR STATUS TOMBOL +/- 💡
            function toggleButtonsState(inputElement) {
                let currentVal = parseInt(inputElement.val());
                let minValue = parseInt(inputElement.attr('data-min'));
                let maxValue = parseInt(inputElement.attr('data-max'));
                let fieldName = inputElement.attr('name');

                // Atur tombol minus
                if (currentVal <= minValue) {
                    $(".btn-number[data-type='minus'][data-field='" + fieldName + "']").attr('disabled', true);
                } else {
                    $(".btn-number[data-type='minus'][data-field='" + fieldName + "']").removeAttr('disabled');
                }

                // Atur tombol plus
                if (currentVal >= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + fieldName + "']").attr('disabled', true);
                } else {
                    $(".btn-number[data-type='plus'][data-field='" + fieldName + "']").removeAttr('disabled');
                }
            }

            // 💡 Panggil toggleButtonsState untuk semua input kuantitas saat halaman dimuat 💡
            $('.input-number').each(function() {
                toggleButtonsState($(this));
            });

            // FUNGSI BARU UNTUK UPDATE TOTAL KESELURUHAN (sudah ada)
            function updateCartTotals() {
                let subtotal = 0;
                $('.shopping-summery tbody tr').not(':last').each(function() {
                    let priceText = $(this).find('.cart_single_price span').text().replace('Rp', '')
                        .replace(/,/g, '');
                    subtotal += parseFloat(priceText) || 0;
                });

                $('.order_subtotal span').text('Rp' + subtotal.toLocaleString('id-ID', {
                    minimumFractionDigits: 2
                }));
                $('.order_subtotal').data('price', subtotal);

                let coupon = parseFloat($('.coupon_price').data('price')) || 0; // Pastikan default ke 0
                let finalTotal = subtotal - coupon;
                $('#order_total_price span').text('Rp' + finalTotal.toLocaleString('id-ID', {
                    minimumFractionDigits: 2
                }));
            }

            updateCartTotals(); // Panggil saat halaman dimuat
        });
    </script>
@endpush
