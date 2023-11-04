@extends('backend.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header bg-warning">
                        <b>Give Refferal Bonus to Users</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <form action="{{url('/filter/for/refferal/bonus')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <label>No of Days</label>
                                        <select name="day" class="form-control" required>
                                            <option value="">Select One</option>
                                            @php
                                                for($start=1; $start<=30; $start++){
                                            @endphp
                                            <option value="{{$start}}" @if($selectedOption == $start) selected @endif>{{$start}} Day</option>
                                            @php
                                                }
                                            @endphp
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label style="color: transparent">No of Days</label>
                                        <input type="submit" value="Filter User" class="btn btn-warning rounded w-100">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if(count($data) > 0)
            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header bg-warning">
                        <b>View All Eligible Users</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <form action="{{url('save/refferal/bonus')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">SL</th>
                                        <th scope="col" class="text-center">Name</th>
                                        <th scope="col" class="text-center">Email</th>
                                        <th scope="col" class="text-center">Refferal Code</th>
                                        <th scope="col" class="text-center">Refferal Count</th>
                                        <th scope="col" class="text-center">Bonus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sl = 1;
                                        $index = 0;
                                    @endphp
                                    @foreach ($data as $item)
                                    <tr>
                                        <td class="text-center">
                                            {{$sl++}}
                                            <input type="hidden" name="user_id[]" value="{{$item['user_id']}}">
                                        </td>
                                        <td class="text-center">
                                            {{$item['name']}}
                                            <input type="hidden" name="user_name[]" value="{{$item['name']}}">
                                        </td>
                                        <td class="text-center">
                                            {{$item['email']}}
                                            <input type="hidden" name="user_email[]" value="{{$item['email']}}">
                                        </td>
                                        <td class="text-center">
                                            {{$item['refferal_code']}}
                                            <input type="hidden" name="refferal_code[]" value="{{$item['refferal_code']}}">
                                        </td>
                                        <td class="text-center">
                                            {{$item['refferal_count']}}
                                            <input type="hidden" name="refferal_count[]" value="{{$item['refferal_count']}}">
                                        </td>
                                        <td class="text-center">
                                            <input type="number" class="" name="bonus[]" value="0">
                                        </td>
                                    </tr>
                                    @php
                                        $index++;
                                    @endphp
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-warning rounded">Save Bonus</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header bg-warning">
                        <b>View All Eligible Users</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">SL</th>
                                    <th scope="col" class="text-center">Name</th>
                                    <th scope="col" class="text-center">Email</th>
                                    <th scope="col" class="text-center">Refferal Code</th>
                                    <th scope="col" class="text-center">Refferal Count</th>
                                    <th scope="col" class="text-center">Bonus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center" colspan="6">No Eligible User Available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>


    </div>
@endsection
