@extends('backend.master')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="card mt-3">
                    <div class="card-header bg-info">
                        <b>Edit Package</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <form action="{{url('/update/package')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="package_id" value="{{$data->id}}" readonly>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" name="title" value="{{$data->title}}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Points/Day</label>
                                        <input type="text" name="ponts_per_day" value="{{$data->ponts_per_day}}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Points/Hour</label>
                                        <input type="text" name="ponts_per_hour" value="{{$data->ponts_per_hour}}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Amount (BDT)</label>
                                        <input type="text" name="amount_bdt" value="{{$data->amount_bdt}}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Amount (USD)</label>
                                        <input type="text" name="amount_usd" value="{{$data->amount_usd}}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Validity (In Days)</label>
                                        <input type="text" name="validity" value="{{$data->validity}}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="submit" value="Update Package" class="btn btn-info rounded">
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

