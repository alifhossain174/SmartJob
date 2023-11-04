<?php

namespace App\Http\Controllers;

use App\Models\AccountStatusSubmit;
use App\Models\Earnings;
use App\Models\PackageRequest;
use App\Models\Settings;
use DataTables;
use App\Models\User;
use App\Models\UserRefferalBonus;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('backend.index');
    }

    public function usersList(Request $request){
        if ($request->ajax()) {
            ini_set('memory_limit', '4096M');
            ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
            $data = User::orderBy('id', 'desc')->get();

            return Datatables::of($data)
                    // ->editColumn('created_at', function($data) {
                    //     return date("Y-m-d", strtotime($data->created_at));
                    // })
                    ->addIndexColumn()
                    ->addColumn('action', function($data){
                        $btn = '';
                        if($data->banned == 1){
                            $btn = '<a href="'.url('/unban/user')."/".$data->id.'" class="btn btn-sm btn-info rounded"><i class="fas fa-check"></i></a>';
                        }

                        if($data->banned != 1){
                            $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-danger btn-sm rounded mt-1 editProduct"><i class="fas fa-times"></i></a>';
                        }
                         return $btn;
                    })
                    ->make(true);
        }

        return view('backend.users');
    }

    public function changePasswordPage(){
        return view('backend.change_password');
    }

    public function changeMyPassword(Request $request){

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required'],
        ]);

        User::where('id',Auth::user()->id)->update([
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);
        Toastr::success('Password has changed', 'Success');
        return redirect('/home');
    }

    public function bannedUsers(Request $request){
        $NewDate = date('Y-m-d', strtotime('+'.$request->ban_day.' days'));

        User::where('id',$request->user_id)->update([
            'banned' => 1,
            'banned_day' => $NewDate,
            'banned_remarks' => $request->reason,
        ]);

        return response()->json(['success'=>'Data saved successfully.']);
    }

    public function unbanUser($id){
        User::where('id',$id)->update([
            'banned' => 0,
            'banned_day' => null,
            'banned_remarks' => null,
        ]);
        Toastr::success('Unban User', 'Success');
        return redirect()->back();
    }

    public function configureSettings(){
        $settings = Settings::where('id', 1)->first();
        return view('backend.settings', compact('settings'));
    }

    public function updateSettings(Request $request){
        Settings::where('id', 1)->update([
            'minimum_withdraw_points_limit' => $request->minimum_withdraw_points_limit,
            'withdraw_date' => $request->withdraw_date
        ]);
        Toastr::success('Settings Updated', 'Success');
        return redirect()->back();
    }

    public function sendNotification(Request $request){

        //push notification
        $serverkey = 'AAAASUe7EXs:APA91bGVFwk4Jo3wIQhmwvzy4Ry59M0rC9vO06Rwfai_Ymrqn9VcFjyH8KyuGhCmt6EOrDDOqepqCPNnobYMC09JR3kHjcZxBFc5I5JlAJV3p4rwjCPNl0mG8RayFZGIivYh_rG_ScSH';
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => $request->title,
            'body' => $request->description,
            'image' => '',
        ];

        $fcmNotification = ['to' => "/topics/all",

            'notification' => $notification,
        ];

        $headers = [
            'Authorization: key=' . $serverkey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);

        Toastr::success('Notification Sent', 'Success');
        return redirect()->back();

    }

    public function userRefferalBonus(){
        $data = array();
        $selectedOption = '';
        return view('backend.refferal_bonus', compact('data', 'selectedOption'));
    }

    public function getEligibleUsers(Request $request){

        $data = array();
        $days = (int) $request->day == '' ? 1 : $request->day;
        $selectedOption = (int) $request->day == '' ? 1 : $request->day;
        $timeLimit = date("Y-m-d H:i:s", strtotime('-'.(24*$days).' hours', time()));

        $mostUsedRefferals = DB::table('users')
                            ->selectRaw('ref_refferal_code, COUNT(ref_refferal_code) as refferal_count')
                            ->where('created_at', '>=', $timeLimit)
                            ->groupBy('ref_refferal_code')
                            ->orderBy('refferal_count', 'desc')
                            ->get();

        $i = 0;
        foreach($mostUsedRefferals as $mostUsedRefferal){
            $userInfo = User::where('refferal_code', $mostUsedRefferal->ref_refferal_code)->first();
            if($userInfo){

                if(count($data) >= 20){
                    break;
                }

                $data[$i]['user_id'] = $userInfo->id;
                $data[$i]['name'] = $userInfo->name;
                $data[$i]['email'] = $userInfo->email;
                $data[$i]['refferal_code'] = $userInfo->refferal_code;
                $data[$i]['refferal_count'] = $mostUsedRefferal->refferal_count;
                $data[$i]['trx'] = 0;
                $i++;
            }
        }

        return view('backend.refferal_bonus', compact('data', 'selectedOption'));
    }

    public function saveRefferalBonus(Request $request){

        $index = 0;
        foreach($request->user_id as $user_id){

            if($request->bonus[$index] > 0){
                UserRefferalBonus::insert([
                    'user_id' => $user_id,
                    'user_name' => $request->user_name[$index],
                    'user_email' => $request->user_email[$index],
                    'refferal_code' => $request->refferal_code[$index],
                    'refferal_count' => $request->refferal_count[$index],
                    'bonus' => $request->bonus[$index],
                    'created_at' => Carbon::now()
                ]);

                Earnings::insert([
                    'user_id' => $user_id,
                    'website_id' => null,
                    'title' => "Referral Offer",
                    'earning_from' => 8,
                    'points' => $request->bonus[$index],
                    'created_at' => Carbon::now(),
                ]);

                User::where('id', $user_id)->increment('balance', $request->bonus[$index]);
                User::where('id', $user_id)->increment('fixed_balance', $request->bonus[$index]);
                User::where('id', $user_id)->increment('refferal_balance', $request->bonus[$index]);
            }
            $index++;
        }

        Toastr::success('Bonus Added', 'Success');
        return redirect('user/refferal/bonus');

    }


    public function viewAccountSubmissionPage(Request $request){
        // $data = DB::table('account_status_submits')
        //             ->join('users','users.id','=','account_status_submits.user_id')
        //             ->select('account_status_submits.*', 'users.name as user_name', 'users.email as user_email')
        //             ->orderBy('id','desc')
        //             //->paginate(15);
        //             ->paginate(500);

        if ($request->ajax()) {

            ini_set('memory_limit', '4096M');
            ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

            $data = DB::table('account_status_submits')
                    ->join('users','users.id','=','account_status_submits.user_id')
                    ->select('account_status_submits.*', 'users.name as user_name', 'users.email as user_email')
                    ->orderBy('id','desc')
                    ->get();

            return Datatables::of($data)
                    ->editColumn('status', function($data) {
                        if($data->status == 0)
                            return 'Pending';
                        if($data->status == 1)
                            return 'Approved';
                        if($data->status == 2)
                            return 'Denied';
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function($data){
                        $btn = '';
                        if($data->status == 0){
                            $btn .= "<a href=".url('/approve/account/submission')."/".$data->id."/".$data->user_id." class='btn btn-success btn-sm rounded mb-1'>Approve</a>";
                            $btn .= "<a href=".url('/deny/account/submission')."/".$data->id."/".$data->user_id." class='btn btn-warning btn-sm rounded mb-1'>Deny</a>";
                        }
                        return $btn;
                    })
                    ->make(true);
        }

        return view('backend.account_submission');
    }

    public function approveAccountSubmission($id, $user_id){
        // DB::table('account_status_submits')->where('id', $id)->update([
        //     'status' => 1,
        //     'updated_at' => Carbon::now()
        // ]);

        $model = AccountStatusSubmit::where('id', $id)->first();
        $model->status = 1;
        $model->updated_at = Carbon::now();
        $model->save();

        // $userModel = User::where('id', $user_id)->update([
        //     'account_status' => 2
        // ]);

        $userModel = User::where('id', $user_id)->first();
        $userModel->account_status = 2;
        $userModel->deposite_balance = $userModel->deposite_balance + $model->amount;
        $userModel->save();

        Toastr::success('Request has been Approved', 'Denied');
        return back();
    }

    public function denyAccountSubmission($id, $user_id){
        DB::table('account_status_submits')->where('id', $id)->update([
            'status' => 2,
            'updated_at' => Carbon::now()
        ]);

        User::where('id', $user_id)->update([
            'account_status' => 3
        ]);

        Toastr::warning('Request has been Denied', 'Denied');
        return back();
    }

    public function countWebsiteVisit(Request $request){
        $startDate = $request->start_date." 00:00:00";
        $endDate = $request->end_date." 23:59:59";

        $data = DB::table('earnings')->whereBetween('created_at', [$startDate, $endDate])->where('website_id', '>', 0)->count();
        return response()->json(['data' => $data]);
    }

}
