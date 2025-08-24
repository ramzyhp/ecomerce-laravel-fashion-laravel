@extends('backend.layouts.master')

@section('main-content')
    <div class="card shadow mb-4">
        <div class="row">
            <div class="col-md-12">
                @include('backend.layouts.notification')
            </div>
        </div>
        <div class="card-header py-3 rounded-top">
            <h6 class="m-0 font-weight-bold text-primary float-left">Daftar Pemesanan</h6>
        </div>
        <div class="card-body">
            <div class="">
                @if (count($orders) > 0)
                    <table class="table table-bordered" id="order-dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>Pemesanan No.</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Qty</th>
                                <th>Charge</th>
                                <th>Total Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                                <th>Detail</th>
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
                                        {{-- 💡 UBAH TOMBOL EDIT MENJADI PEMICU MODAL 💡 --}}
                                        <button type="button" class="btn btn-primary btn-sm float-left mr-1 editBtn"
                                            style="height:30px; width:30px;border-radius:50%" data-toggle="modal"
                                            data-target="#editOrderModal" title="edit" data-placement="bottom"
                                            data-order-id="{{ $order->id }}" data-order-status="{{ $order->status }}"
                                            data-order-number="{{ $order->order_number }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        {{-- 💡 AKHIR UBAH TOMBOL EDIT 💡 --}}
                                        <form method="POST" action="{{ route('order.destroy', [$order->id]) }}">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-danger btn-sm dltBtn" data-id={{ $order->id }}
                                                style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                                data-placement="bottom" title="Delete"><i
                                                    class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                    <td> <a href="{{ route('order.show', $order->id) }}"
                                            class="btn btn-warning btn-sm float-left mr-1"
                                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                                            title="view" data-placement="bottom"><i class="fas fa-eye"></i></a>
                                    </td>
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

    {{-- 💡 STRUKTUR MODAL EDIT PESANAN 💡 --}}
    <div class="modal fade" id="editOrderModal" tabindex="-1" role="dialog" aria-labelledby="editOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOrderModalLabel">Edit Pesanan: <span id="modalOrderNumber"></span></h5>
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
    {{-- 💡 AKHIR STRUKTUR MODAL 💡 --}}

@endsection

@push('styles')
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <style>
        div.dataTables_wrapper div.dataTables_paginate {
            display: none;
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
                "targets": [8] // Kolom "Aksi"
            }, {
                "orderable": false,
                "targets": [9] // Kolom "Detail" baru
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

            // 💡 BAGIAN INI DITAMBAHKAN UNTUK LOGIKA MODAL EDIT 💡
            // Ketika tombol edit diklik
            $('.editBtn').click(function() {
                var orderId = $(this).data('order-id');
                var orderStatus = $(this).data('order-status');
                var orderNumber = $(this).data('order-number');

                // Isi Order Number di judul modal
                $('#modalOrderNumber').text(orderNumber);

                // Set nilai status di dropdown modal
                $('#modalOrderStatus').val(orderStatus);

                // Set action form modal secara dinamis
                var formAction = "{{ route('order.update', ':id') }}";
                formAction = formAction.replace(':id', orderId);
                $('#editOrderStatusForm').attr('action', formAction);

                // Atur disabled option di dropdown sesuai logika status (sama seperti di edit.blade.php)
                $('#modalOrderStatus option').each(function() {
                    var optionVal = $(this).val();
                    $(this).prop('disabled', false); // Aktifkan semua dulu
                    if (orderStatus === 'delivered' || orderStatus === 'cancel') {
                        // Jika status delivered atau cancel, semua option kecuali yang current menjadi disabled
                        if (optionVal !== orderStatus) {
                            $(this).prop('disabled', true);
                        }
                    } else if (orderStatus === 'process') {
                        // Jika status process, hanya 'delivered' atau 'cancel' yang bisa dipilih
                        if (optionVal === 'new') {
                            $(this).prop('disabled', true);
                        }
                    } else if (orderStatus === 'new') {
                        // Jika status new, 'delivered' dan 'cancel' disabled
                        if (optionVal === 'delivered' || optionVal === 'cancel') {
                            $(this).prop('disabled', true);
                        }
                    }
                });
            });
        })
    </script>
@endpush
