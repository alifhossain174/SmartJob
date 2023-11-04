@extends('backend.master')

@section('header_css')
    <link href="{{ url('dataTable') }}/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="{{ url('dataTable') }}/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button{
            padding: 0px;
            border-radius: 4px;
        }
        table.dataTable tbody td:nth-child(1){
            text-align: center !important;
            font-weight: 600;
        }
        table.dataTable tbody td:nth-child(2){
            text-align: center !important;
        }
        table.dataTable tbody td:nth-child(3){
            text-align: center !important;
        }
        table.dataTable tbody td:nth-child(4){
            text-align: center !important;
        }
        table.dataTable tbody td:nth-child(5){
            text-align: center !important;
        }
        table.dataTable tbody td:nth-child(6){
            text-align: center !important;
        }
        table.dataTable tbody td:nth-child(7){
            text-align: center !important;
        }
        table.dataTable tbody td:nth-child(8){
            text-align: center !important;
        }
        table.dataTable tbody td:nth-child(9){
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
                        <b>View All Users</b>
                    </div>
                    <div class="card-body table-responsive" style="border-left: 1px solid #46BC53 !important; border-bottom: 1px solid #46BC53 !important;">
                        <table class="table table-striped data-table w-100">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">SL</th>
                                    <th scope="col" class="text-center">Name</th>
                                    <th scope="col" class="text-center">Email</th>
                                    <th scope="col" class="text-center">Contact</th>
                                    <th scope="col" class="text-center">Country</th>
                                    <th scope="col" class="text-center">Refferal</th>
                                    <th scope="col" class="text-center">Ref Refferal</th>
                                    <th scope="col" class="text-center">Balance</th>
                                    <th scope="col" class="text-center">Banned Upto</th>
                                    <th scope="col" class="text-center">Action</th>
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
                        <input type="hidden" name="user_id" id="user_id">

                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">Ban User For</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="ban_day" name="ban_day" placeholder="Days" value="" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">Reason</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="reason" name="reason" placeholder="Reason of Banned" value="" required="">
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Ban User</button>
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
            ajax: "{{ url('users/lists') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'}, //orderable: true, searchable: true
                {data: 'email', name: 'email'},
                {data: 'contact', name: 'contact'},
                {data: 'country', name: 'country'},
                {data: 'refferal_code', name: 'refferal_code'},
                {data: 'ref_refferal_code', name: 'ref_refferal_code'},
                {data: 'balance', name: 'balance'},
                {data: 'banned_day', name: 'banned_day'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
                // {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    </script>


    <script type="text/javascript">
        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('click', '.editProduct', function () {
                var product_id = $(this).data('id');
                // $.get("{{ url('/get/existing/project/data/for/modal') }}" +'/' + product_id +'/edit', function (data) {
                    $('#modelHeading').html("Ban User");
                    $('#saveBtn').val("Edit Project");
                    $('#ajaxModel').modal('show');
                    $('#user_id').val(product_id);
                // })
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Updating..');
                $.ajax({
                    data: $('#productForm').serialize(),
                    url: "{{ url('/make/user/banned') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        $('#productForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        location.reload(true);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
                    }
                });
            });

        });
    </script>
@endsection

