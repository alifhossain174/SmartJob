@extends('backend.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mt-3">
                    <div class="card-header bg-info text-white">
                        <b>Add New Pyament Channel</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <form action="{{url('/add/new/payment/type')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="title" class="form-control" required>
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
                                        <label>Type</label>
                                        <select name="type" class="form-control" required>
                                            <option value="">Select One</option>
                                            <option value="1">Wallet</option>
                                            <option value="2">Phone</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Upload Image</label>
                                        <input type="file" name="logo" onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])" class="form-control" accept=".png, .jpg, .jpeg ,.svg, .JPG, .PNG" required>
                                        <img id="blah2" alt="" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="submit" value="Save Channel" class="btn btn-info rounded">
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
                        <b>View All Payment Channel</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">SL</th>
                                    <th scope="col" class="text-center">Name</th>
                                    <th scope="col" class="text-center">Type</th>
                                    <th scope="col" class="text-center">Description</th>
                                    <th scope="col" class="text-center">Image</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sl=1; ?>
                                @foreach ($types as $index => $type)
                                    <tr>
                                        <td class="text-center">{{ $index+$types->firstItem() }}</td>
                                        <td class="text-center">{{$type->title}}</td>
                                        <td class="text-center">@if($type->type == 1) Email @else Phone @endif</td>
                                        <td class="text-center">{!! $type->description !!}</td>
                                        <td class="text-center">@if(file_exists(public_path($type->logo)) && $type->logo != '' && $type->logo != NULL) <img src="{{url($type->logo)}}" style="width: 70px">@endif</td>
                                        <td class="text-center">
                                            <a href="{{url('/delete/payment/type')}}/{{$type->id}}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm rounded mb-1"><i class="fas fa-trash-alt"></i></a>
                                            <a href="{{url('/edit/payment/type')}}/{{$type->id}}" class="btn btn-warning btn-sm rounded mb-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $types->links() }}
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
