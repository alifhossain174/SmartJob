@extends('backend.master')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-8">
                <div class="card mt-3">
                    <div class="card-header bg-info text-white">
                        <b>Edit Pyament Channel</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <form action="{{url('/update/payment/type')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="payment_type_id" value="{{$type->id}}">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="title" value="{{$type->title}}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="ck_description" class="form-control">{!! $type->description !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select name="type" class="form-control" required>
                                            <option value="">Select One</option>
                                            <option value="1" @if($type->type == 1) selected @endif>Wallet</option>
                                            <option value="2" @if($type->type == 2) selected @endif>Phone</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Upload Image</label>
                                        <input type="file" name="logo" onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])" class="form-control" accept=".png, .jpg, .jpeg ,.svg, .JPG, .PNG">
                                        <img id="blah2" alt="" src="{{url('/')}}/{{$type->logo}}" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="submit" value="Update Channel" class="btn btn-info rounded">
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

