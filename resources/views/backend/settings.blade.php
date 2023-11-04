@extends('backend.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card mt-3">
                    <div class="card-header bg-info">
                        <b><i class="fas fa-wrench"></i> Settings </a></b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <form action="{{url('/update/settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 m-auto">
                                    <div class="form-group">
                                        <label>Minimum Withdraw Points :</label>
                                        <input type="number" name="minimum_withdraw_points_limit" value="{{$settings->minimum_withdraw_points_limit}}" class="form-control" required>
                                    </div>
                                </div>
                                <input type="hidden" name="withdraw_date" value="{{$settings->withdraw_date}}" class="form-control">
                                {{-- <div class="col-lg-12 m-auto">
                                    <div class="form-group">
                                        <label>Withdraw Date :</label>

                                    </div>
                                </div> --}}
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="submit" value="Update Settings" class="btn btn-info rounded">
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

