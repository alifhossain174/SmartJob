@extends('backend.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header bg-info">
                        <b>Facebook Ad Network Config</b>
                    </div>
                    <div class="card-body">
                        <form action="{{url('update/ad/info')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Hash key</label>
                                        <input type="text" name="hash_key" class="form-control" value="{{$data->hash_key}}">
                                    </div>
                                    <div class="form-group">
                                        <label>FB Ad ID</label>
                                        <input type="text" name="fb_ad_id" class="form-control" value="{{$data->fb_ad_id}}">
                                    </div>
                                    <div class="form-group">
                                        <label>Banner Ad ID</label>
                                        <input type="text" name="banner_ad_id" class="form-control" value="{{$data->banner_ad_id}}">
                                    </div>
                                    <div class="form-group">
                                        <label>Native Ad ID</label>
                                        <input type="text" name="native_ad_id" class="form-control" value="{{$data->native_ad_id}}">
                                    </div>
                                    <div class="form-group">
                                        <label>Interstitial Ad ID</label>
                                        <input type="text" name="interstitial_ad_id" class="form-control" value="{{$data->interstitial_ad_id}}">
                                    </div>
                                    <div class="form-group">
                                        <label>Rewarded Video Ad ID</label>
                                        <input type="text" name="rewardedVideoAdID" class="form-control" value="{{$data->rewardedVideoAdID }}">
                                    </div>

                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Show Status</label>
                                        <select class="form-control" name="show_status">
                                            <option value="">Select</option>
                                            <option value="1" @if($data->show_status == 1) selected @endif>Show</option>
                                            <option value="0" @if($data->show_status == 0) selected @endif>Dont Show</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Baner Show Status</label>
                                        <select class="form-control" name="banner_show_status">
                                            <option value="">Select</option>
                                            <option value="1" @if($data->banner_show_status == 1) selected @endif>Show</option>
                                            <option value="0" @if($data->banner_show_status == 0) selected @endif>Dont Show</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Native Show Status</label>
                                        <select class="form-control" name="native_show_status">
                                            <option value="">Select</option>
                                            <option value="1" @if($data->native_show_status == 1) selected @endif>Show</option>
                                            <option value="0" @if($data->native_show_status == 0) selected @endif>Dont Show</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Interstitial Show Status</label>
                                        <select class="form-control" name="interstitial_show_status">
                                            <option value="">Select</option>
                                            <option value="1" @if($data->interstitial_show_status == 1) selected @endif>Show</option>
                                            <option value="0" @if($data->interstitial_show_status == 0) selected @endif>Dont Show</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center pt-2">
                                <input type="submit" value="Update Info" class="btn btn-info rounded">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

