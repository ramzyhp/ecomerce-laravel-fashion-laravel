@extends('user.layouts.master')

@section('main-content')
    <div class="card shadow mb-4">
        <div class="row">
            <div class="col-md-12">
                @include('user.layouts.notification')
            </div>
        </div>
        <div class="card-header py-3 rounded-top">
            <h6 class="m-0 font-weight-bold text-primary float-left">Daftar Pesanan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @if (count($orders) > 0)
                    <table class="table table-bordered" id="order-dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>No Pesanan.</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Qty</th>
                                <th>Charge</th>
                                <th>Total Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                                <th>Ulasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                @php
                                    $shipping_charge = DB::table('shippings')
                                        ->where('id', $order->shipping_id)
                                        ->pluck('price');
                                @endphp
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                                    <td>{{ $order->email }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>
                                        @foreach ($shipping_charge as $data)
                                            Rp{{ number_format($data, 2) }}
                                        @endforeach
                                    </td>
                                    <td>Rp{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @if ($order->status == 'new')
                                            <span class="badge badge-primary">Baru</span>
                                        @elseif($order->status == 'process')
                                            <span class="badge badge-warning">Diproses</span>
                                        @elseif($order->status == 'delivered')
                                            <span class="badge badge-success">Dikirim</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge badge-info">Selesai</span>
                                        @else
                                            <span class="badge badge-danger">Dibatalkan</span>
                                        @endif

                                    </td>
                                    <td>
                                        <a href="{{ route('user.order.show', $order->id) }}"
                                            class="btn btn-warning btn-sm float-left mr-1"
                                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                            title="view" data-placement="bottom"><i class="fas fa-eye"></i></a>
                                    </td>
                                    <td>
                                        @if ($order->status == 'delivered' || $order->status == 'completed')
                                            {{-- Loop melalui setiap produk dalam pesanan --}}
                                            @foreach ($order->cart_info as $cart_item)
                                                {{-- Asumsi relasi cart_info di model Order ke item Cart --}}
                                                @php
                                                    $product_slug = $cart_item->product->slug ?? '#';
                                                    $product_title = $cart_item->product->title ?? 'Produk';
                                                    // Cek apakah produk ini sudah diulas oleh user yang sedang login
                                                    $alreadyReviewed = \App\Models\ProductReview::where(
                                                        'user_id',
                                                        Auth::id(),
                                                    )
                                                        ->where('product_id', $cart_item->product_id)
                                                        ->exists();
                                                @endphp
                                                @if (!$alreadyReviewed)
                                                    <button type="button" class="btn btn-info btn-sm mb-1 reviewBtn"
                                                        style="width:auto;border-radius:5px" data-toggle="modal"
                                                        data-target="#reviewModal" title="Beri Ulasan"
                                                        data-placement="bottom"
                                                        data-product-id="{{ $cart_item->product_id }}"
                                                        data-product-slug="{{ $product_slug }}"
                                                        data-product-title="{{ $product_title }}">
                                                        Ulas {{ Str::limit($product_title, 15) }}
                                                    </button><br>
                                                @else
                                                    <span class="badge badge-success mb-1">Sudah Diulas</span><br>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="badge badge-secondary">Belum Tersedia</span>
                                        @endif
                                    </td>
                                    {{-- 💡 AKHIR BAGIAN DITAMBAHKAN 💡 --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <span style="float:right">{{ $orders->links() }}</span>
                @else
                    <h6 class="text-center">No orders found!!! Please order some products</h6>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Berikan Ulasan untuk: <span
                            id="modalReviewProductTitle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="reviewForm" action="" method="POST"> {{-- Action akan diisi via JS --}}
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="product_id" id="modalProductId">
                        <input type="hidden" name="slug" id="modalProductSlug"> {{-- Slug untuk route --}}

                        <div class="form-group mb-3">
                            <label class="form-label">Rating</label>
                            <div class="rating-box">
                                <div class="star-rating">
                                    <div class="star-rating__wrap">
                                        <i class="star-rating__ico far fa-star text-warning" data-rating="1"></i>
                                        <i class="star-rating__ico far fa-star text-warning" data-rating="2"></i>
                                        <i class="star-rating__ico far fa-star text-warning" data-rating="3"></i>
                                        <i class="star-rating__ico far fa-star text-warning" data-rating="4"></i>
                                        <i class="star-rating__ico far fa-star text-warning" data-rating="5"></i>
                                        <input type="hidden" name="rate" id="selected-rating-modal" value="0">
                                    </div>
                                </div>
                                <div id="rate-error" class="text-danger mt-1"></div> {{-- Untuk pesan error rating --}}
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="review-text" class="form-label">Ulasan Anda</label>
                            <textarea class="form-control" id="review-text" name="review" rows="3" required></textarea>
                            <div id="review-error" class="text-danger mt-1"></div> {{-- Untuk pesan error ulasan --}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="submitReviewBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="btn-text">Kirim Ulasan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- 💡 MODAL EDIT PESANAN (sudah ada) 💡 --}}
    <div class="modal fade" id="editOrderModal" tabindex="-1" role="dialog" aria-labelledby="editOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOrderModalLabel">Edit Pesanan: <span id="modalOrderNumber"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editOrderStatusForm" action="" method="POST"> {{-- Action akan diisi via JS --}}
                    @csrf
                    @method('PATCH') {{-- Method tetap PATCH --}}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="status">Status :</label>
                            <select name="status" id="modalOrderStatus" class="form-control">
                                <option value="new">Baru</option>
                                <option value="process">Diproses</option>
                                <option value="delivered">Dikirim</option>
                                <option value="cancel">Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- 💡 AKHIR MODAL EDIT PESANAN 💡 --}}


@endsection

@push('styles')
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <style>
        div.dataTables_wrapper div.dataTables_paginate {
            display: none;
        }

        /* Tambahan styling untuk tombol ulasan */
        .btn.btn-info.btn-sm {
            background-color: #17a2b8;
            /* Warna default info */
            border-color: #17a2b8;
            color: white;
        }

        /* Styling untuk bintang rating di modal */
        .star-rating__ico {
            font-size: 1.5rem;
            /* Sesuaikan ukuran bintang */
            cursor: pointer;
            margin-right: 2px;
            transition: color 0.2s ease;
        }

        .star-rating__ico:hover {
            transform: scale(1.1);
        }

        /* Loading animation untuk tombol submit */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Success badge animation */
        .badge-success {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Modal animation improvement */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
        }

        /* Form validation styling */
        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .text-danger {
            font-size: 0.875rem;
            font-weight: 500;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script src="{{ asset('backend/js/demo/datatables-demo.js') }}"></script>
    <script>
        $('#order-dataTable').DataTable({
            "columnDefs": [{
                "orderable": false,
                "targets": [8, 9] // 💡 Tambahkan index kolom "Ulasan" (index 9) 💡
            }]
        });

        // Sweet alert untuk delete (sudah ada)
        function deleteData(id) {
            // ... kode deleteData ...
        }
    </script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.dltBtn').click(function(e) {
                var form = $(this).closest('form');
                var dataID = $(this).data('id');
                e.preventDefault();
                swal({
                        title: "Apa kamu yakin?",
                        text: "Setelah dihapus, data tidak bisa dikembalikan!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            form.submit();
                        } else {
                            swal("Data kamu aman!");
                        }
                    });
            })

            // 💡 LOGIKA MODAL EDIT (sudah ada) 💡
            $('.editBtn').click(function() {
                var orderId = $(this).data('order-id');
                var orderStatus = $(this).data('order-status');
                var orderNumber = $(this).data('order-number');

                $('#modalOrderNumber').text(orderNumber);
                $('#modalOrderStatus').val(orderStatus);
                var formAction = "{{ route('order.update', ':id') }}";
                formAction = formAction.replace(':id', orderId);
                $('#editOrderStatusForm').attr('action', formAction);

                $('#modalOrderStatus option').each(function() {
                    var optionVal = $(this).val();
                    $(this).prop('disabled', false);
                    if (orderStatus === 'delivered' || orderStatus === 'cancel') {
                        if (optionVal !== orderStatus) {
                            $(this).prop('disabled', true);
                        }
                    } else if (orderStatus === 'process') {
                        if (optionVal === 'new') {
                            $(this).prop('disabled', true);
                        }
                    } else if (orderStatus === 'new') {
                        if (optionVal === 'delivered' || optionVal === 'cancel') {
                            $(this).prop('disabled', true);
                        }
                    }
                });
            });

            // 💡 BAGIAN INI DITAMBAHKAN UNTUK LOGIKA MODAL ULASAN (REVIEW) 💡
            // Inisialisasi status bintang untuk modal review
            var $modalStars = $('#reviewModal .star-rating__ico');
            var $modalHiddenInput = $('#reviewModal #selected-rating-modal');

            // Fungsi untuk mengisi bintang di modal
            function fillModalStars(count) {
                $modalStars.each(function(index) {
                    if (index < count) {
                        $(this).removeClass('far').addClass('fa'); // Bintang penuh
                    } else {
                        $(this).removeClass('fa').addClass('far'); // Bintang kosong
                    }
                });
            }

            // Event saat modal review dibuka
            $('#reviewModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Tombol yang memicu modal
                var productId = button.data('product-id');
                var productSlug = button.data('product-slug');
                var productTitle = button.data('product-title');

                // Reset form dan pesan error sebelumnya
                $('#reviewForm')[0].reset(); // Reset form
                $('#rate-error').text('');
                $('#review-error').text('');
                fillModalStars(0); // Reset bintang ke nol

                // Isi data ke dalam modal
                $('#modalReviewProductTitle').text(productTitle);
                $('#modalProductId').val(productId);
                $('#modalProductSlug').val(productSlug);

                // Set action form modal secara dinamis
                var formAction = "{{ route('review.store', ':slug') }}";
                formAction = formAction.replace(':slug', productSlug);
                $('#reviewForm').attr('action', formAction);

                $modalHiddenInput.val(0); // Pastikan nilai rating awal 0
            });

            // Hover effect untuk bintang di modal
            $modalStars.on('mouseover', function() {
                var currentRating = $(this).data('rating');
                fillModalStars(currentRating);
            }).on('mouseout', function() {
                fillModalStars(parseInt($modalHiddenInput.val()));
            });

            // Click event untuk bintang di modal
            $modalStars.on('click', function() {
                var clickedRating = $(this).data('rating');
                $modalHiddenInput.val(clickedRating); // Set nilai di hidden input
                fillModalStars(clickedRating); // Tetap isi bintang setelah klik
            });

            // Submit form review via AJAX
            $('#reviewForm').submit(function(e) {
                e.preventDefault(); // Mencegah submit form default

                var form = $(this);
                var url = form.attr('action');
                var formData = form.serialize();
                var productId = $('#modalProductId').val();

                // Bersihkan pesan error sebelumnya
                $('#rate-error').text('');
                $('#review-error').text('');

                // Disable tombol submit untuk mencegah double submit
                var submitBtn = form.find('button[type="submit"]');
                var originalText = submitBtn.text();
                submitBtn.prop('disabled', true).text('Mengirim...');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#reviewModal').modal('hide'); // Tutup modal

                            // Tampilkan pesan sukses yang lebih menarik
                            swal({
                                title: "Berhasil!",
                                text: response.message,
                                icon: "success",
                                button: "OK",
                            }).then(() => {
                                // Update button menjadi "Sudah Diulas" setelah sukses
                                $('button[data-product-id="' + productId + '"]').each(
                                    function() {
                                        $(this).replaceWith(
                                            '<span class="badge badge-success mb-1">Sudah Diulas</span><br>'
                                            );
                                    });
                            });
                        } else {
                            swal("Gagal!", response.message, "error");
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            if (errors.rate) {
                                $('#rate-error').text(errors.rate[0]);
                            }
                            if (errors.review) {
                                $('#review-error').text(errors.review[0]);
                            }
                        } else {
                            var message = xhr.responseJSON && xhr.responseJSON.message ?
                                xhr.responseJSON.message :
                                "Terjadi kesalahan saat mengirim ulasan.";
                            swal("Gagal!", message, "error");
                        }
                    },
                    complete: function() {
                        // Re-enable tombol submit
                        submitBtn.prop('disabled', false).text(originalText);
                    }
                });
            });

        })
    </script>
@endpush
