@extends('backend.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mt-3">
                    <div class="card-header bg-info">
                        <b>Edit Payment Info</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <form action="{{url('/update/payment')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="payment_id" value="{{$payment->id}}">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Select Type</label>
                                        <select class="form-control" name="type" required="">
                                            @php
                                                echo App\Models\PaymentType::getDropDownList('title', $payment->type);
                                            @endphp
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Number/Email</label>
                                        <input type="text" name="number" value="{{$payment->number}}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="ck_description" class="form-control">{!! $payment->description !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="submit" value="Update Payment" class="btn btn-info rounded">
                                    </div>
                                </div>
                            </div>
                        </form>
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

