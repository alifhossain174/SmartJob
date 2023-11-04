<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class QuizController extends Controller
{
    public function addNewQuiz(){
        return view('backend.add_new_quiz');
    }

    public function saveNewQuiz(Request $request){

        Quiz::insert([
            'question' => $request->question,
            'option1' => $request->option1,
            'option2' => $request->option2,
            'option3' => $request->option3,
            'option4' => $request->option4,
            'answer' => $request->answer[0],
            'created_at' => Carbon::now()
        ]);

        Toastr::success('New Quiz has been Added', 'Success');
        return back();
    }

    public function viewAllQuiz(){
        $data = Quiz::orderBy('id', 'desc')->paginate(15);
        return view('backend.view_all_quiz', compact('data'));
    }

    public function deleteQuiz($id){
        Quiz::where('id', $id)->delete();
        Toastr::error('Quiz has been Deleted', 'Deleted');
        return back();
    }
}

?>
