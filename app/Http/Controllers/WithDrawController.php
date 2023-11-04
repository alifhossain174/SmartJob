<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserDevice;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;

class WithDrawController extends Controller
{
    public function withDrawAmountPage(Request $request){;

        if ($request->ajax()) {

            ini_set('memory_limit', '4096M');
            ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

            $data = DB::table('with_draws')
                    ->join('users','users.id','=','with_draws.user_id')
                    ->select('with_draws.*', 'users.name as user_name', 'users.email as user_email')
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
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Approve" class="mb-1 edit btn btn-warning btn-sm rounded approveWithdraw">Approve</a>';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Deny" class="mb-1 edit btn btn-danger btn-sm rounded denyWithdraw">Deny</a>';
                            // $btn .= "<a href=".url('/deny/withdraw')."/".$data->id."/".$data->user_id." class='btn btn-warning btn-sm rounded mb-1'>Deny</a>";
                        }
                        return $btn;
                    })
                    ->make(true);
        }

        return view('backend.withdraw');
    }

    public function getDataForModalApproveWithDraw($id){
        $product = DB::table('with_draws')->where('id', $id)->first();
        return response()->json([
            'data' => $product
        ]);
    }

    public function saveTransactionId($id){

        DB::table('with_draws')->where('id', $id)->update([
            'admin_wallet_address' => null,
            'transaction_id' => time(),
            'status' => 1,
            'updated_at' => Carbon::now()
        ]);

        try {

            //push notification
            $serverkey = 'AAAASUe7EXs:APA91bGVFwk4Jo3wIQhmwvzy4Ry59M0rC9vO06Rwfai_Ymrqn9VcFjyH8KyuGhCmt6EOrDDOqepqCPNnobYMC09JR3kHjcZxBFc5I5JlAJV3p4rwjCPNl0mG8RayFZGIivYh_rG_ScSH';
            $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

            $withDrawInfo = DB::table('with_draws')->where('id', $id)->first();
            if($withDrawInfo){

                $devices = UserDevice::where('user_id', $withDrawInfo->user_id)->get();
                foreach($devices as $deviceInfo){
                    if($deviceInfo && $deviceInfo->login_status == 1){
                        $token = $deviceInfo->token;

                        $notification = [
                            'title' => "Withdraw Approved",
                            'body' => "Withdraw Request has been Accepted",
                        ];

                        $fcmNotification = [
                            //'registration_ids' => $tokenList, //multple token array
                            'to' => $token, //single token
                            'notification' => $notification,
                        ];

                        $headers = [
                            'Authorization: key=' . $serverkey,
                            'Content-Type: application/json',
                        ];

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                        $result = curl_exec($ch);
                        curl_close($ch);
                    }
                }

            }

        } catch (\Exception $e) {

        }

        return response()->json(['success'=>'Data saved successfully.']);
    }

    public function denyWithDraw($id){
        DB::table('with_draws')->where('id', $id)->update([
            'status' => 2,
            'updated_at' => Carbon::now()
        ]);
        $data = DB::table('with_draws')->where('id',$id)->first();
        User::where('id', $data->user_id)->increment('balance', $data->trx);


        try {

            //push notification
            $serverkey = 'AAAASUe7EXs:APA91bGVFwk4Jo3wIQhmwvzy4Ry59M0rC9vO06Rwfai_Ymrqn9VcFjyH8KyuGhCmt6EOrDDOqepqCPNnobYMC09JR3kHjcZxBFc5I5JlAJV3p4rwjCPNl0mG8RayFZGIivYh_rG_ScSH';
            $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

            $withDrawInfo = DB::table('with_draws')->where('id', $id)->first();
            if($withDrawInfo){

                $devices = UserDevice::where('user_id', $withDrawInfo->user_id)->get();
                foreach($devices as $deviceInfo){
                    if($deviceInfo && $deviceInfo->login_status == 1){
                        $token = $deviceInfo->token;

                        $notification = [
                            'title' => "Withdraw Deny",
                            'body' => "Withdraw Request has been Denied",
                        ];

                        $fcmNotification = [
                            //'registration_ids' => $tokenList, //multple token array
                            'to' => $token, //single token
                            'notification' => $notification,
                        ];

                        $headers = [
                            'Authorization: key=' . $serverkey,
                            'Content-Type: application/json',
                        ];

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                        $result = curl_exec($ch);
                        curl_close($ch);
                    }
                }

            }

        } catch (\Exception $e) {

        }

        Toastr::warning('With Draw has been Denied', 'Denied');
        return back();
    }

}
