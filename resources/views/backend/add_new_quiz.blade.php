@extends('backend.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header bg-info">
                        <b>Add New Quiz</b>
                    </div>
                    <div class="card-body" style="border-left: 1px solid #ADBC7A !important; border-bottom: 1px solid #ADBC7A !important;">

                        <form action="{{url('/save/new/quiz')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 border-right">
                                    <div class="form-group">
                                        <label><b>Quistion</b></label>
                                        <textarea name="question" rows="7" class="form-control" required></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Option-1 <i> <input type="radio" name="answer[]" value="1"> Select the right Answer</i></label>
                                        <input type="text" name="option1" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Option-2 <i> <input type="radio" name="answer[]" value="2"> Select the right Answer</i></label>
                                        <input type="text" name="option2" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Option-3 <i> <input type="radio" name="answer[]" value="3"> Select the right Answer</i></label>
                                        <input type="text" name="option3" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Option-4 <i> <input type="radio" name="answer[]" value="4"> Select the right Answer</i></label>
                                        <input type="text" name="option4" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="Save Quiz" class="w-100 btn btn-info rounded">
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

