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
            font-weight: 600;
        }

        table.dataTable tbody td {
            text-align: center !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header bg-info">
                        <div class="row">
                            <div class="col-lg-12">
                                <b class="text-white">View All Account Submission Requests</b>
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
                                    <th scope="col">Amount</th>
                                    <th scope="col">Admin Wallet Address</th>
                                    <th scope="col">Transaction Hash</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
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
@endsection


@section('footer_js')
    <script src="{{ url('dataTable') }}/js/jquery.validate.js"></script>
    <script src="{{ url('dataTable') }}/js/jquery.dataTables.min.js"></script>
    <script src="{{ url('dataTable') }}/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        var table = $(".data-table").DataTable({
            processing: true,
            serverSide: true,
            saveState: true,
            ajax: "{{ url('view/account/submission') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                }, //orderable: true, searchable: true
                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'user_email',
                    name: 'user_email'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'wallet_address_admin',
                    name: 'wallet_address_admin'
                },
                {
                    data: 'transaction_hash',
                    name: 'transaction_hash'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
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

            $('body').on('click', '.editProduct', function() {
                var product_id = $(this).data('id');
                $.get("{{ url('/get/withdraw/data/for/modal') }}" + '/' + product_id + '/edit', function(
                    data) {
                    $('#modelHeading').html("Approve Withdraw");
                    $('#saveBtn').val("Approve");
                    $('#ajaxModel').modal('show');
                    $('#product_id').val(data.data.id);
                    $('#user_id').val(data.data.user_id);
                    $('#points').val(data.data.points);
                    $('#trx').val(data.data.trx);
                    $('#payment_title').val(data.data.payment_title);
                    $('#user_wallet_address').val(data.data.user_wallet_address);
                    $('#admin_wallet_address').val(data.data.admin_wallet_address);
                    $('#transaction_id').val(data.data.transaction_id);
                })
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Updating..');
                $.ajax({
                    data: $('#productForm').serialize(),
                    url: "{{ url('/save/approve/data/wihdraw') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#productForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        location.reload(true);
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

        });
    </script>
@endsection
