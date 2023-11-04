@extends('backend.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header bg-info">
                        <b>Add New Website</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">

                        <form action="{{url('/save/new/website')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 border-right">
                                    <div class="form-group">
                                        <label><b>Title</b></label>
                                        <input type="text" name="title" class="form-control rounded" required>
                                    </div>
                                    <div class="form-group">
                                        <label><b>Logo</b></label>
                                        <input type="file" name="logo" onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])" class="form-control rounded" accept=".png, .jpg, .jpeg , .JPG, .PNG" required>
                                        <img id="blah2" alt="" class="img-fluid">
                                    </div>

                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label><b>Description</b></label>
                                        <textarea name="description" rows="" class="form-control rounded"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label><b>Link</b></label>
                                        <input type="text" name="link" class="form-control rounded">
                                    </div>
                                    <div class="form-group">
                                        <label><b>Visiting Seconds</b></label>
                                        <input type="text" name="visiting_seconds" class="form-control rounded">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 text-center pt-3">
                                    <button type="submit" class="btn btn-info rounded">Save Data</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

