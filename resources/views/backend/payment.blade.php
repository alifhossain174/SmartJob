@extends('backend.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mt-3">
                    <div class="card-header bg-info text-white">
                        <b>Add New Payment</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <form action="{{url('/add/new/payment')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Select Type</label>
                                        <select class="form-control" name="type" required="">
                                            @php
                                                echo App\Models\PaymentType::getDropDownList('title');
                                            @endphp
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Number/Wallet</label>
                                        <input type="text" name="number" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="ck_description" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="submit" value="Save Payment" class="btn btn-info rounded">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mt-3">
                    <div class="card-header text-white bg-info">
                        <b>View All Payments</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th scope="col">SL</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Number</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sl=1; ?>
                                @foreach ($payments as $index => $item)
                                    <tr>
                                        <td>{{ $index+$payments->firstItem() }}</td>
                                        <td>
                                            @php
                                                echo App\Models\PaymentType::title($item->type);
                                            @endphp
                                        </td>
                                        <td>{{$item->number}}</td>
                                        <td>{!! $item->description !!}</td>
                                        <td>
                                            <a href="{{url('/delete/payment')}}/{{$item->id}}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm rounded mb-1"><i class="fas fa-trash-alt"></i></a>
                                            <a href="{{url('/edit/payment')}}/{{$item->id}}" class="btn btn-warning btn-sm rounded mb-1"><i class="fas fa-edit"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('footer_js')
    <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('ck_description', {
            filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
            height: 150,
        });
    </script>
@endsection

