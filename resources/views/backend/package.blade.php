@extends('backend.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mt-3">
                    <div class="card-header bg-info">
                        <b>Add New Package</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <form action="{{url('/add/new/package')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Points/Day</label>
                                        <input type="number" name="ponts_per_day" class="form-control" required>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Points/Hour</label> --}}
                                        <input type="hidden" name="ponts_per_hour" value="0" class="form-control" required>
                                    {{-- </div>
                                </div> --}}
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Amount (BDT)</label>
                                        <input type="number" name="amount_bdt" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Amount (USD)</label>
                                        <input type="number" name="amount_usd" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Validity (In Days)</label>
                                        <input type="number" name="validity" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="submit" value="Save Package" class="btn btn-info rounded">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mt-3">
                    <div class="card-header bg-info">
                        <b>View All Packages</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">SL</th>
                                    <th scope="col" class="text-center">Title</th>
                                    <th scope="col" class="text-center">Points/Day</th>
                                    {{-- <th scope="col" class="text-center">Points/Hour</th> --}}
                                    <th scope="col" class="text-center">Amount (BDT)</th>
                                    <th scope="col" class="text-center">Amount (USD)</th>
                                    <th scope="col" class="text-center">Validity</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sl=1; ?>
                                @foreach ($packages as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index+$packages->firstItem() }}</td>
                                        <td class="text-center">{{$item->title}}</td>
                                        <td class="text-center">{{$item->ponts_per_day}}</td>
                                        {{-- <td class="text-center">{{$item->ponts_per_hour}}</td> --}}
                                        <td class="text-center">{{$item->amount_bdt}}</td>
                                        <td class="text-center">{{$item->amount_usd}}</td>
                                        <td class="text-center">{{$item->validity}}</td>
                                        <td class="text-center">
                                            <a href="{{url('/edit/package')}}/{{$item->id}}" class="btn btn-warning btn-sm rounded mb-1"><i class="fas fa-edit"></i></a>
                                            <a href="{{url('/delete/package')}}/{{$item->id}}" class="btn btn-danger btn-sm rounded"><i class="far fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{ $packages->links() }}
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

