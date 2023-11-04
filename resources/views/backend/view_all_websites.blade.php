@extends('backend.master')

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header bg-info">
                        <b>View All Websites</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #46BC53 !important; border-bottom: 1px solid #46BC53 !important;">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">SL</th>
                                    <th scope="col" class="text-center">Image</th>
                                    <th scope="col" class="text-center">Title</th>
                                    <th scope="col" class="text-center">Description</th>
                                    <th scope="col" class="text-center">Link</th>
                                    <th scope="col" class="text-center">Visiting Seconds</th>
                                    <th scope="col" style="text-align: center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sl=1; ?>
                                @foreach ($data as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index+$data->firstItem() }}</td>
                                        <td class="text-center">@if(file_exists(public_path($item->logo)) && $item->logo != '' && $item->logo != NULL)<img src="{{url($item->logo)}}" style="width: 60px">@endif</td>
                                        <td class="text-center">{{$item->title}}</td>
                                        <td class="text-center">{{$item->description}}</td>
                                        <td class="text-center">{{$item->link}}</td>
                                        <td class="text-center">{{$item->visiting_seconds}}</td>
                                        <td class="text-center">
                                            <a href="{{url('/delete/website')}}/{{$item->id}}" class="mb-1 btn btn-danger btn-sm rounded"><i class="far fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{ $data->links() }}
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


