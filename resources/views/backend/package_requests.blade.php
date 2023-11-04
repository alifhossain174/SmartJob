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
                    <div class="card-header bg-info">
                        <b>View All Package Requests</b>
                    </div>
                    <div class="card-body table-responsive" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <table class="table table-striped data-table" id="myTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">SL</th>
                                    <th scope="col" class="text-center">User Info</th>
                                    <th scope="col" class="text-center">Package</th>
                                    <th scope="col" class="text-center">Points</th>
                                    <th scope="col" class="text-center">Amount</th>
                                    <th scope="col" class="text-center">Validity</th>
                                    <th scope="col" class="text-center">Pay. Method</th>
                                    <th scope="col" class="text-center">Pay. Info</th>
                                    <th scope="col" class="text-center">TNX ID</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" style="text-align: center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <?php //$sl=1; ?>
                                @foreach ($package_requests as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index+$package_requests->firstItem() }}</td>
                                        <td class="text-center">{{$item->user_name}}<br>{{$item->email}}</td>
                                        <td class="text-center">{{$item->package_title}}</td>
                                        <td class="text-center">{{$item->package_ponts_per_day}}/Day<br>{{$item->package_ponts_per_hour}}/Hour</td>
                                        <td class="text-center">BDT {{$item->package_amount_bdt}} <br> USD {{$item->package_amount_usd}}</td>
                                        <td class="text-center">{{$item->package_validity }}</td>
                                        <td class="text-center">{{$item->payment_method }}</td>
                                        <td class="text-center">{{$item->payment_info }}</td>
                                        <td class="text-center">{{$item->transaction_id }}</td>
                                        <td class="text-center">
                                            @if($item->status == 0)
                                            Pending
                                            @elseif($item->status == 1)
                                            Approved
                                            @else
                                            Denied
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{url('/delete/package/request')}}/{{$item->id}}" class="mb-1 btn btn-danger btn-sm rounded"><i class="far fa-trash-alt"></i></a>
                                            @if($item->status == 0)
                                                <a href="{{url('/approve/package/request')}}/{{$item->id}}" class="mb-1 btn btn-info btn-sm rounded">Approve</a>
                                                <a href="{{url('/deny/package/request')}}/{{$item->id}}" class="mb-1 btn btn-info btn-sm rounded">Deny</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach --}}
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
            ajax: "{{ url('manage/package') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'user_name', name: 'user_name'}, //orderable: true, searchable: true
                {data: 'package_title', name: 'package_title'},
                {data: 'package_ponts_per_day', name: 'package_ponts_per_day'},
                {data: 'package_amount_bdt', name: 'package_amount_bdt'},
                {data: 'package_validity', name: 'package_validity'},
                {data: 'payment_method', name: 'payment_method'},
                {data: 'payment_info', name: 'payment_info'},
                {data: 'transaction_id', name: 'transaction_id'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    </script>
@endsection



