<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function paymentPage(){
        $payments = Payment::orderBy('id','desc')->paginate(15);
        return view('backend.payment',compact('payments'));
    }

    public function addNewPayment(Request $request){
        Payment::insert([
            'type' => $request->type,
            'number' => $request->number,
            'description' => $request->ck_description,
            'created_at' => Carbon::now(),
        ]);
        Toastr::success('Payment Gateway has been Added', 'Success');
        return back();
    }

    public function deletePayment($id){
        Payment::where('id',$id)->delete();
        Toastr::error('Payment Gateway has been Deleted', 'Deleted');
        return back();
    }

    public function editPayment($id){
        $payment = Payment::where('id', $id)->first();
        return view('backend.edit_payment',compact('payment'));
    }

    public function updatePayment(Request $request){
        Payment::where('id', $request->payment_id)->update([
            'type' => $request->type,
            'number' => $request->number,
            'description' => $request->ck_description,
            'updated_at' => Carbon::now(),
        ]);
        Toastr::success('Payment Gateway has been Updated', 'Success');
        return redirect('/payment/page');
    }
}
