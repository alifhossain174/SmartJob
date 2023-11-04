@extends('backend.master')

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header text-white bg-info">
                        <b>View All Quiz</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">SL</th>
                                    <th scope="col" class="text-center">Question</th>
                                    <th scope="col" class="text-center">Options</th>
                                    <th scope="col" class="text-center">Answer</th>
                                    <th scope="col" style="text-align: center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sl=1; ?>
                                @foreach ($data as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index+$data->firstItem() }}</td>
                                        <td class="text-center">{{$item->question}}</td>
                                        <td class="text-center">1. {{$item->option1}}<br>2. {{$item->option2}}<br>3. {{$item->option3}}<br>4. {{$item->option4}}</td>
                                        <td class="text-center">{{$item->answer}}</td>
                                        <td class="text-center">
                                            <a href="{{url('/delete/quiz')}}/{{$item->id}}" class="mb-1 btn btn-danger btn-sm rounded"><i class="far fa-trash-alt"></i></a>
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


