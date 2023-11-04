<?php

namespace App\Http\Controllers;

use Image;
use App\Models\PaymentType;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function paymentTypePage(){
        $types = PaymentType::orderBy('id', 'desc')->paginate(15);
        return view('backend.payment_type', compact('types'));
    }

    public function addNewPaymentType(Request $request){
        $logo = null;

        if ($request->hasFile('logo')){
            $get_image = $request->file('logo');
            $image_name = str::random(5) . time() . '.' . $get_image->getClientOriginalExtension();
            $location = public_path('payment_type_images/');
            Image::make($get_image)->save($location . $image_name, 50);
            $logo = "payment_type_images/" . $image_name;
        }

        PaymentType::insert([
            'logo' => $logo,
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->ck_description,
            'created_at' => Carbon::now()
        ]);

        Toastr::success('Payment Channel has been Added', 'Success');
        return back();
    }

    public function deletePaymentType($id){
        $data = PaymentType::where('id',$id)->first();
        if($data->logo != null){
            // if(file_exists(public_path($data->logo))){
            //     unlink($data->logo);
            // }
        }
        PaymentType::where('id',$id)->delete();
        Toastr::error('Payment Channel has been Deleted', 'Deleted');
        return back();
    }

    public function editPaymentType($id){
        $type = PaymentType::where('id', $id)->first();
        return view('backend.edit_payment_type', compact('type'));
    }

    public function updatePaymentChannel(Request $request){
        $data = PaymentType::where('id',$request->payment_type_id)->first();
        $logo = $data->logo;

        if ($request->hasFile('logo')){
            if($data->logo != null){
                // if(file_exists(public_path($data->logo))){
                //     unlink($data->logo);
                // }
            }

            $get_image = $request->file('logo');
            $image_name = str::random(5) . time() . '.' . $get_image->getClientOriginalExtension();
            $location = public_path('payment_type_images/');
            Image::make($get_image)->save($location . $image_name, 50);
            $logo = "payment_type_images/" . $image_name;
        }

        PaymentType::where('id', $request->payment_type_id)->update([
            'logo' => $logo,
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->ck_description,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('Payment Channel has been Updated', 'Success');
        return redirect('/payment/type/page');
    }
}
