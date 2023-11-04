<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use DataTables;

class PackageController extends Controller
{
    public function packagePage(){
        $packages = Package::orderBy('id', 'desc')->paginate(10);
        return view('backend.package',compact('packages'));
    }

    public function addNewPackage(Request $request){

        Package::insert([
            'title' => $request->title,
            'ponts_per_day' => $request->ponts_per_day,
            'ponts_per_hour' => $request->ponts_per_hour,
            'amount_bdt' => $request->amount_bdt,
            'amount_usd' => $request->amount_usd,
            'validity' => $request->validity,
            'created_at' => Carbon::now()
        ]);

        Toastr::success('Package has been Added', 'Success');
        return back();

    }

    public function deletePackage($id){
        Package::where('id',$id)->delete();
        Toastr::error('Package has been deleted', 'Deleted');
        return back();
    }

    public function editPackage($id){
        $data = Package::where('id',$id)->first();
        return view('backend.edit_package',compact('data'));
    }

    public function updatePackage(Request $request){

        Package::where('id',$request->package_id)->update([
            'title' => $request->title,
            'ponts_per_day' => $request->ponts_per_day,
            'ponts_per_hour' => $request->ponts_per_hour,
            'amount_bdt' => $request->amount_bdt,
            'amount_usd' => $request->amount_usd,
            'validity' => $request->validity,
            'updated_at' => Carbon::now()
        ]);

        Toastr::success('Package has been Updated', 'Success');
        return redirect('/package/page');

    }

    public function managePakcage(Request $request){

        if ($request->ajax()) {

            $data = DB::table('package_requests')
                        ->join('packages', 'packages.id', '=', 'package_requests.package_id')
                        ->join('users','users.id','=','package_requests.user_id')
                        ->select('package_requests.*', 'users.name as user_name', 'users.email')
                        ->orderBy('id','desc')
                        ->get();

            return Datatables::of($data)
                    ->editColumn('status', function($data) {
                        if($data->status == 0)
                            return 'Pending';
                        elseif($data->status == 1)
                            return 'Approved';
                        else
                            return 'Denied';
                    })
                    ->editColumn('user_name', function($data) {
                        return $data->user_name." (".$data->email.")";
                    })
                    ->editColumn('package_ponts_per_day', function($data) {
                        return $data->package_ponts_per_day."/day &".$data->package_ponts_per_hour."/hour";
                    })
                    ->editColumn('package_amount_bdt', function($data) {
                        return "BDT".$data->package_amount_bdt." & USD".$data->package_amount_usd;
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function($data){
                        $btn = '<a href="'.url('/delete/package/request')."/".$data->id.'" class="mb-1 btn btn-danger btn-sm rounded"><i class="far fa-trash-alt"></i></a>';
                        if($data->status == 0){
                            $btn .= ' <a href="'.url('/approve/package/request')."/".$data->id.'" class="mb-1 btn btn-success btn-sm rounded">Approve</a>';
                            $btn .= ' <a href="'.url('/deny/package/request')."/".$data->id.'" class="mb-1 btn btn-danger btn-sm rounded">Deny</a>';
                        }
                        return $btn;
                    })
                    ->make(true);
        }

        return view('backend.package_requests');
    }

    public function deletePackageRequest($id){
        PackageRequest::where('id',$id)->delete();
        Toastr::error('Package Request has been deleted', 'Deleted');
        return back();
    }

    public function denyPackageRequest($id){

        PackageRequest::where('id',$id)->update([
            'status' => 2,
            'updated_at' => Carbon::now()
        ]);

        Toastr::warning('Package Request has been Denied', 'Denied');
        return back();
    }

    public function approvePackageRequest($id){
        PackageRequest::where('id',$id)->update([
            'status' => 1,
            'updated_at' => Carbon::now()
        ]);
        Toastr::success('Package Request has been Apprved', 'Denied');
        return back();
    }
}
