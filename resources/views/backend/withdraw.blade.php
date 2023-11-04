@extends('backend.master')

@section('header_css')
    <link href="{{ url('dataTable') }}/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="{{ url('dataTable') }}/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0px;
            border-radius: 4px;
        }

        table.dataTable tbody td:nth-child(1) {
            text-align: center !important;
            font-weight: 600;
        }

        table.dataTable tbody td:nth-child(2) {
            text-align: center !important;
        }

        table.dataTable tbody td:nth-child(3) {
            text-align: center !important;
        }

        table.dataTable tbody td:nth-child(4) {
            text-align: center !important;
        }

        table.dataTable tbody td:nth-child(5) {
            text-align: center !important;
        }

        table.dataTable tbody td:nth-child(6) {
            text-align: center !important;
        }

        table.dataTable tbody td:nth-child(7) {
            text-align: center !important;
        }

        table.dataTable tbody td:nth-child(8) {
            text-align: center !important;
        }

        table.dataTable tbody td:nth-child(9) {
            text-align: center !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header text-white bg-info">
                        <div class="row">
                            <div class="col-lg-12">
                                <b>View All Withdraw Requests</b>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive"
                        style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <table class="table table-striped data-table w-100">
                            <thead>
                                <tr>
                                    <th scope="col">SL</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">TRX</th>
                                    <th scope="col">Payment Through</th>
                                    <th scope="col">User Wallet</th>
                                    <th scope="col">Admin Wallet</th>
                                    <th scope="col">Transaction ID</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="text-align: center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>

                <div class="modal-body">
                    <form id="productForm" name="productForm" class="form-horizontal">
                        <input type="hidden" name="product_id" id="product_id">
                        <input type="hidden" name="user_id" id="user_id">

                        <div class="form-group">
                            <label for="points" class="col-sm-12 control-label">Points</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="points" name="points" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="trx" class="col-sm-12 control-label">TRX</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="trx" name="trx" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="payment_title" class="col-sm-12 control-label">Payment Title</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="payment_title" name="payment_title" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="user_wallet_address" class="col-sm-12 control-label">User Wallet Address</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="user_wallet_address"
                                    name="user_wallet_address" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="admin_wallet_address" class="col-sm-12 control-label">Admin Wallet Address</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="admin_wallet_address"
                                    name="admin_wallet_address">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="transaction_id" class="col-sm-12 control-label">Transaction ID</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="transaction_id" name="transaction_id">
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save
                                changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer_js')

    <script src="{{ url('dataTable') }}/js/jquery.validate.js"></script>
    <script src="{{ url('dataTable') }}/js/jquery.dataTables.min.js"></script>
    <script src="{{ url('dataTable') }}/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        var table = $(".data-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('withdraw/amount/page') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'created_at', name: 'created_at'}, //orderable: true, searchable: true
                {data: 'user_name', name: 'user_name'},
                {data: 'user_email', name: 'user_email'},
                {data: 'trx', name: 'trx'},
                {data: 'payment_title', name: 'payment_title'},
                {data: 'user_wallet_address', name: 'user_wallet_address'},
                {data: 'admin_wallet_address', name: 'admin_wallet_address'},
                {data: 'transaction_id', name: 'transaction_id'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
                // {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    </script>

    <script type="text/javascript">
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // $('body').on('click', '.editProduct', function() {
            //     var product_id = $(this).data('id');
            //     $.get("{{ url('/get/withdraw/data/for/modal') }}" + '/' + product_id + '/edit', function(
            //         data) {
            //         $('#modelHeading').html("Approve Withdraw");
            //         $('#saveBtn').val("Approve");
            //         $('#ajaxModel').modal('show');
            //         $('#product_id').val(data.data.id);
            //         $('#user_id').val(data.data.user_id);
            //         $('#points').val(data.data.points);
            //         $('#trx').val(data.data.trx);
            //         $('#payment_title').val(data.data.payment_title);
            //         $('#user_wallet_address').val(data.data.user_wallet_address);
            //         $('#admin_wallet_address').val(data.data.admin_wallet_address);
            //         $('#transaction_id').val(data.data.transaction_id);
            //     })
            // });

            // $('#saveBtn').click(function(e) {
            //     e.preventDefault();
            //     $(this).html('Updating..');
            //     $.ajax({
            //         data: $('#productForm').serialize(),
            //         url: "{{ url('/save/approve/data/wihdraw') }}",
            //         type: "POST",
            //         dataType: 'json',
            //         success: function(data) {
            //             $('#productForm').trigger("reset");
            //             $('#ajaxModel').modal('hide');
            //             location.reload(true);
            //         },
            //         error: function(data) {
            //             console.log('Error:', data);
            //             $('#saveBtn').html('Save Changes');
            //         }
            //     });
            // });


            $('body').on('click', '.approveWithdraw', function () {
                var slug = $(this).data("id");
                if(confirm("Are You sure want to Approve !")){
                    $.ajax({
                        type: "GET",
                        url: "{{ url('save/approve/data/wihdraw') }}"+'/'+slug,
                        success: function (data) {
                            table.draw(false);
                            toastr.success("Request Approved", "Approved Successfully");
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });


            $('body').on('click', '.denyWithdraw', function () {
                var slug = $(this).data("id");
                if(confirm("Are You sure want to Deny !")){
                    $.ajax({
                        type: "GET",
                        url: "{{ url('deny/withdraw') }}"+'/'+slug,
                        success: function (data) {
                            table.draw(false);
                            toastr.error("Request Denied", "Denied Successfully");
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });

        });
    </script>
@endsection
