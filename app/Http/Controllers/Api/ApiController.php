<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebsiteResource;
use App\Http\Resources\AccountSubmissionResource;
use App\Http\Resources\EarningResource;
use App\Http\Resources\WithdrawResource;
use App\Http\Resources\UserResource;
use App\Models\Package;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\UserForgetPasswordMail;
use App\Models\AccountStatusSubmit;
use App\Models\Earnings;
use App\Models\PackageRequest;
use App\Models\FbAdNetwork;
use App\Models\PaymentType;
use App\Models\Website;
use App\Models\UserDevice;
use DateTime;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Support\Facades\Mail;

class ApiController extends Controller
{
    const AUTHORIZATION_TOKEN = 'INGHHJEGHJEUDFGHYSBW7583546837NUDD75465546';

    public function userLogin(Request $request){

        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

                if((Auth::user()->banned_day != null && Auth::user()->banned_day < date("Y-m-d")) || Auth::user()->banned_day == null){

                    $id = Auth::user()->id;
                    $name = Auth::user()->name;
                    $email = Auth::user()->email;
                    $contact = Auth::user()->contact;
                    $refferal_code = Auth::user()->refferal_code;
                    $balance = Auth::user()->balance;
                    $country = Auth::user()->country;


                    $packageInfo = DB::table('package_requests')
                                    ->join('users', 'package_requests.user_id', '=', 'users.id')
                                    ->where('users.id', $id)
                                    ->where('package_requests.status', 1)
                                    ->orderBy('package_requests.id', 'desc')
                                    ->first();


                    $data = array(
                        'id' => $id,
                        'name' => $name,
                        'email' => $email,
                        'contact' => $contact,
                        'refferal_code' => $refferal_code,
                        'balance' => $balance,
                        'country' => $country,
                        'package_name' => $packageInfo ? $packageInfo->package_title : 'Check In',
                        'package_ponts_per_day' => $packageInfo ? $packageInfo->package_ponts_per_day : 0.05,
                    );

                    return response()->json([
                        'success' =>true,
                        'message' => 'Successfully Logged In',
                        'data' => $data
                    ]);

                } else {
                    return response()->json([
                        'success' => false,
                        'message' => Auth::user()->banned_remarks,
                        'data' => NULL
                    ])->setStatusCode(200);
                }

            }
            else{
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Login Credential',
                    'data' => NULL
                ])->setStatusCode(200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid",
                'data' => NULL
            ], 422);
        }
    }

    public function userProfileInfo(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            date_default_timezone_set('America/New_York');
            $currentServerTime = date("Y-m-d H:i:s");

            $userInfo = User::where('id', $request->user_id)->first();
            $name = $userInfo->name;
            $email = $userInfo->email;
            $contact = $userInfo->contact;
            $balance = $userInfo->balance;
            $country = $userInfo->country;

            /*$packageInfo = DB::table('package_requests')
                            ->join('users', 'package_requests.user_id', '=', 'users.id')
                            ->where('users.id', $request->user_id)
                            ->where('package_requests.status', 1)
                            ->orderBy('package_requests.id', 'desc')
                            ->first();*/

            $packageInfo = DB::table('package_requests')
                            ->where('user_id', $request->user_id)
                            ->where('package_requests.status', 1)
                            ->orderBy('package_requests.id', 'desc')
                            ->first();

            $validityCheck = 1;
            if($packageInfo){
                $packageApproveDate = date('Y-m-d', strtotime($packageInfo->updated_at));
                $lastValididty = date('Y-m-d', strtotime('+'.$packageInfo->package_validity.'days', strtotime($packageApproveDate)));
                if(date('Y-m-d') > $lastValididty){
                    $validityCheck = 0;
                }
            }

            $data = array(
                'name' => $name,
                'email' => $email,
                'contact' => $contact,
                'balance' => $balance,
                'deposite_balance' => $userInfo->deposite_balance,
                'refferal_balance' => $userInfo->refferal_balance,
                'spin_count' => $userInfo->spin_count,
                'country' => $country,
                'account_status' => $userInfo->account_status,

                'package_name' => $packageInfo && $validityCheck == 1 ? $packageInfo->package_title : 'Check In',
                'package_ponts_per_day' => $packageInfo && $validityCheck == 1 ? $packageInfo->package_ponts_per_day : (($userInfo->deposite_balance*2)/100)+0.05,
                'amount_bdt' => $packageInfo && $validityCheck == 1 ? $packageInfo->package_amount_bdt : '',
                'amount_usd' => $packageInfo && $validityCheck == 1 ? $packageInfo->package_amount_usd : '',
                'validity' => $packageInfo && $validityCheck == 1 ? $packageInfo->package_validity : '',

             /*   'todays' => date('Y-m-d'),
                'package_updated' => $packageApproveDate,
                'lastValididty' => $lastValididty,*/


                'my_refferal_count' => User::where('ref_refferal_code', $userInfo->refferal_code)->count(),
                'last_mining_started_at' => $userInfo->last_mining_started_at,
                'last_mining_started_at_timestamp' => strtotime($userInfo->last_mining_started_at),
                'current_server_datetime' => $currentServerTime,
                'current_server_datetime_timestamp' => strtotime($currentServerTime),
            );

            return response()->json(['success' =>true, 'message' => "Data Found", 'data' => $data]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid",
                'data' => NULL
            ], 422);
        }
    }

    public function userRegistration(Request $request){

        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {
            return response()->json([
                    'success' => false,
                    'message' => 'Update Your Apps From Playstore!',
                    'data' => NULL
                ]);


            /*$data = array();
            $data['name'] = $request->name;
            $data['contact'] = $request->contact;
            $data['email'] = $request->email;
            $data['password'] = Hash::make($request->password);
            $data['ref_refferal_code'] = $request->ref_refferal_code;
            $data['country'] = $request->country;
            $data['last_mining_started_at'] = date("Y-m-d H:i:s");
            $data['created_at'] = date("Y-m-d H:i:s");

            $email_check = User::where('email', $request->email)->first();
            if($email_check){
                return response()->json([
                    'success'=> false,
                    'message'=> 'Email already used ! Please use another Email',
                    'data' => NULL
                ]);
            } else {

                $data['refferal_code'] = str::random(5) . time();
                $id = DB::table('users')->insertGetId($data);

                $user_details = DB::table('users')->where('id',$id)->first();

                $packageInfo = DB::table('package_requests')
                                ->join('users', 'package_requests.user_id', '=', 'users.id')
                                ->where('users.id', $id)
                                ->where('package_requests.status', 1)
                                ->orderBy('package_requests.id', 'desc')
                                ->first();

                $data = array(
                    'id' => (int) $user_details->id,
                    'name' => $user_details->name,
                    'email' => $user_details->email,
                    'contact' => $user_details->contact,
                    'refferal_code' => $user_details->refferal_code,
                    'balance' => $user_details->balance,
                    'country' => $user_details->country,
                    'package_name' => $packageInfo ? $packageInfo->package_title : 'Check In',
                    'package_ponts_per_day' => $packageInfo ? $packageInfo->package_ponts_per_day : 0.05,
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Successfully Registered',
                    'data' => $data,
                ]);

            }*/
        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid",
                'data' => NULL
            ], 422);
        }

    }


    public function userNewRegistration(Request $request){

        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {
               /* return response()->json([
                    'success' => false,
                    'message' => 'May You Are Using Bot',
                    'data' => NULL
                ]);*/
            $data = array();
            $data['name'] = $request->name;
            $data['contact'] = $request->contact;
            $data['email'] = $request->email;
            $data['password'] = Hash::make($request->password);
            $data['ref_refferal_code'] = $request->ref_refferal_code;
            $data['country'] = $request->country;
            $data['last_mining_started_at'] = date("Y-m-d H:i:s");
            $data['created_at'] = date("Y-m-d H:i:s");

            $email_check = User::where('email', $request->email)->first();
            if($email_check){
                return response()->json([
                    'success'=> false,
                    'message'=> 'Email already used ! Please use another Email',
                    'data' => NULL
                ]);
            } else {

                $data['refferal_code'] = str::random(5) . time();
                $id = DB::table('users')->insertGetId($data);

                $user_details = DB::table('users')->where('id',$id)->first();

                $packageInfo = DB::table('package_requests')
                                ->join('users', 'package_requests.user_id', '=', 'users.id')
                                ->where('users.id', $id)
                                ->where('package_requests.status', 1)
                                ->orderBy('package_requests.id', 'desc')
                                ->first();

                $data = array(
                    'id' => (int) $user_details->id,
                    'name' => $user_details->name,
                    'email' => $user_details->email,
                    'contact' => $user_details->contact,
                    'refferal_code' => $user_details->refferal_code,
                    'balance' => $user_details->balance,
                    'country' => $user_details->country,
                    'package_name' => $packageInfo ? $packageInfo->package_title : 'Check In',
                    'package_ponts_per_day' => $packageInfo ? $packageInfo->package_ponts_per_day : 0.05,
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Successfully Registered',
                    'data' => $data,
                ]);

            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid",
                'data' => NULL
            ], 422);
        }

    }

    public function userForgetPassword(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {
            $code = $request->code;
            $email = $request->email;
            $user = User::where('email', $email)->first();
            if($user){
                $msg = "Your Sky Mining account verification code is ".$code;
                $msg = wordwrap($msg, 70);
                mail($email,"Forgot Password",$msg);


               // $mailData = array();
                //$mailData['code'] = $code;
                //Mail::to(trim($email))->send(new UserForgetPasswordMail($mailData));


                $data = array(
                    'id' => $user->id,
                );

                return response()->json([
                    'success'=> true,
                    'data'=> $data
                ]);
            } else {
                return response()->json([
                    'success'=> false,
                    'message'=> 'No Email Found',
                    'data' => NULL
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid",
                'data' => NULL
            ], 422);
        }
    }

    public function userChangePassword(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $user_id = $request->user_id;
            $password = $request->password;

            User::where('id', $user_id)->update([
                'password' => Hash::make($password),
            ]);

            return response()->json([
                'success'=> true,
                'message'=> 'Password Changed Successfully'
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function userProfileUpdate(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $user_id = $request->user_id;
            $name = $request->name;
            $contact = $request->contact;
            $country = $request->country;

            User::where('id', $user_id)->update([
                'name' => $name,
                'contact' => $contact,
                'country' => $country,
            ]);

            return response()->json([
                'success'=> true,
                'message'=> 'Profile Updated Successfully'
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function miningPackages(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $data = Package::orderBy('id', 'desc')->get();
            return response()->json([
                'success'=> true,
                'data'=> $data
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function getPaymentTypes(Request $request)
    {
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {
            $data = PaymentType::orderBy('id', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function getPaymentInfo(Request $request)
    {
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $data = DB::table('payments')
                ->select('payments.*', 'payment_types.type as payment_through')
                ->join('payment_types', 'payments.type', '=', 'payment_types.id')
                ->where('payments.type', $request->type)
                ->orderBy('payments.id', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function subscribePackage(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $user_id = $request->user_id;
            $package_id = $request->package_id;
            $payment_method = $request->payment_method;
            $payment_info = $request->payment_info;
            $transaction_id = $request->transaction_id;

            $packageInfo = Package::where('id', $package_id)->first();

            PackageRequest::insert([
                'user_id' => $user_id,
                'package_id' => $package_id,
                'package_title' => $packageInfo->title,
                'package_ponts_per_day' => $packageInfo->ponts_per_day,
                'package_ponts_per_hour' => $packageInfo->ponts_per_hour,
                'package_amount_bdt' => $packageInfo->amount_bdt,
                'package_amount_usd' => $packageInfo->amount_usd,
                'package_validity' => $packageInfo->validity,
                'payment_method' => $payment_method,
                'payment_info' => $payment_info,
                'transaction_id' => $transaction_id,
                'created_at' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Package Request Sent',
            ]);


        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function userSubscribePackages(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $data = DB::table('package_requests')
                ->where('user_id', $request->user_id)
                ->orderBy('id', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function userEarningHistory(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $data = Earnings::where('user_id', $request->user_id)->where('earning_from', '!=', 1)->orderBy('id', 'desc')->paginate(15);

            return response()->json([
                'success' => true,
                'data' => EarningResource::collection($data)->resource
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function userRefferalEarning(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $data = Earnings::where('user_id', $request->user_id)->where('earning_from', 1)->orderBy('id', 'desc')->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function userExtraEarningSubmit(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            if(($request->website_id != '' || $request->website_id != NULL) && $request->website_id > 0 && $request->earning_from == 5){
                $todaysCount = Earnings::where('user_id', $request->user_id)->where('website_id', $request->website_id)->where("earning_from", 5)->where('created_at', 'LIKE', date("Y-m-d").'%')->count();

                if($request->points > 0.004){

                    $NewDate = date('Y-m-d', strtotime('+365 days'));
                    User::where('id',$request->user_id)->update([
                        'banned' => 1,
                        'banned_day' => $NewDate,
                        'banned_remarks' => $request->reason,
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => "Detect Fake Working Procedure. Your Account Permanently Suspend",
                    ]);
                }

                $points = 0.004; //$request->points,

                if($todaysCount!=1){

                  	// checking time difference with previous submission for Bot submit prevenetion start

                  	$currentDateTime = Carbon::now();

                    $previousData = Earnings::where('user_id', $request->user_id)->where('earning_from', $request->earning_from)->orderBy('id', 'desc')->first();
                    $start = new DateTime($previousData->created_at);
                    $end = new DateTime($currentDateTime);
                    $diff = $start->diff($end);
                    $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
                    $hoursInSecs = $diff->h * 60 * 60;
                    $minsInSecs = $diff->i * 60;
                    $seconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;

                  	// blocked for bot submission
                    if($seconds <= 13){
                      $NewDate = date('Y-m-d', strtotime('+365 days'));
                      User::where('id',$request->user_id)->update([
                          'banned' => 1,
                          'banned_day' => $NewDate,
                          'banned_remarks' => 'Illigal Activities Detected',
                      ]);

                      return response()->json([
                          'success' => false,
                          'message' => "Illigal Activities Detected",
                      ]);
                    }

                  	// checking time difference with previous submission for Bot submit prevenetion end

                    Earnings::insert([
                        'user_id' => $request->user_id,
                        'website_id' => $request->website_id,
                        'title' => $request->title,
                        'earning_from' => $request->earning_from,
                        'points' => $points,//$request->points,
                        'created_at' => $currentDateTime,
                    ]);

                    User::where('id', $request->user_id)->increment('balance', $points);
                    User::where('id', $request->user_id)->increment('fixed_balance', $points);
                    $userInfo = User::where('id', $request->user_id)->first();
                    if($userInfo->ref_refferal_code != NULL){
                        $refferedUser = User::where('refferal_code', $userInfo->ref_refferal_code)->first();
                        if($refferedUser){
                            User::where('refferal_code', $userInfo->ref_refferal_code)->increment('balance', (($points*5)/100));
                            Earnings::insert([
                                'user_id' => $refferedUser->id,
                                'title' => $request->title,
                                'earning_from' => 1, //from refferal
                                'points' => (($points*5)/100),
                                'created_at' => Carbon::now(),
                            ]);
                        }
                    }

                    return response()->json([
                        'success' => true,
                        'message' => "Data Submitted",
                    ]);
                } else {

                    return response()->json([
                      'success' => false,
                      'message' => "Already Viewed this Website",
                    ]);

                }


            } else {

                if($request->points > 0.3){

                    $NewDate = date('Y-m-d', strtotime('+365 days'));
                    User::where('id',$request->user_id)->update([
                        'banned' => 1,
                        'banned_day' => $NewDate,
                        'banned_remarks' => $request->reason,
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => "Detect Fake Working Procedure. Your Account Permanently Suspend",
                    ]);
                }

                $todaysWebCount = Earnings::where('user_id', $request->user_id)->where("earning_from", $request->earning_from)->where('created_at', 'LIKE', date("Y-m-d").'%')->count();
                if($todaysWebCount <= 0){
                    Earnings::insert([
                        'user_id' => $request->user_id,
                        'website_id' => $request->website_id,
                        'title' => $request->title,
                        'earning_from' => $request->earning_from,
                        'points' => $request->points,
                        'created_at' => Carbon::now(),
                    ]);

                    User::where('id', $request->user_id)->increment('balance', $request->points);
                    User::where('id', $request->user_id)->increment('fixed_balance', $request->points);
                    $userInfo = User::where('id', $request->user_id)->first();
                    if($userInfo->ref_refferal_code != NULL){
                        $refferedUser = User::where('refferal_code', $userInfo->ref_refferal_code)->first();
                        if($refferedUser){
                            User::where('refferal_code', $userInfo->ref_refferal_code)->increment('balance', (($request->points*5)/100));
                            Earnings::insert([
                                'user_id' => $refferedUser->id,
                                'title' => $request->title,
                                'earning_from' => 1, //from refferal
                                'points' => (($request->points*5)/100),
                                'created_at' => Carbon::now(),
                            ]);
                        }
                    }

                    return response()->json([
                        'success' => true,
                        'message' => "Data Submitted",
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "Today's Limit is Over, Try Later",
                    ]);
                }

            }



        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function quizQuestions(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $data = Quiz::all();
            if(count($data) < 10){
                return response()->json([
                    'success' => true,
                    'data' => $data,
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'data' => $data->random(10),
                ]);
            }

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function fbAdConfig(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $data = FbAdNetwork::where('id', 1)->first();
            $data = array(
                'hash_key' => $data->hash_key,
                'fb_ad_id' => $data->fb_ad_id,
                'banner_ad_id' => $data->banner_ad_id,
                'native_ad_id' => $data->native_ad_id,
                'interstitial_ad_id' => $data->interstitial_ad_id,
                'rewardedVideoAdID' => $data->rewardedVideoAdID,
                'show_status' => strval($data->show_status),
                'banner_show_status' => strval($data->banner_show_status),
                'native_show_status' => strval($data->native_show_status),
                'interstitial_show_status' => strval($data->interstitial_show_status),
            );

            return response()->json([
                'success' =>true,
                'data' => $data
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function startMining(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $userInfo = User::where('id', $request->user_id)->first();
            if(($userInfo->banned_day != null && $userInfo->banned_day < date("Y-m-d")) || $userInfo->banned_day == null){
                User::where('id', $request->user_id)->update([
                    'last_mining_started_at' => Carbon::now()
                ]);

                return response()->json([
                    'success' =>true,
                    'message' => 'Check In Done'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $userInfo->banned_remarks
                ]);
            }

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function miningStatus(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $userInfo = User::where('id', $request->user_id)->first();
            $lastMiningStarted = date("Y-m-d", strtotime($userInfo->last_mining_started_at));
            $today = date("Y-m-d");

            if($userInfo->last_mining_started_at == null || $userInfo->last_mining_started_at == ''){
                return response()->json([
                    'success' => false,
                    'message' => 'Mining is not Started'
                ]);
            } elseif($lastMiningStarted == $today){
                return response()->json([
                    'success' => true,
                    'message' => 'Check In has already been done.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Check In not Done'
                ]);
            }

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

        public function submitWithdraw(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $userInfo = User::where('id', $request->user_id)->first();
            $settings = Settings::where('id', 1)->first();


            if(($userInfo->banned_day != null && $userInfo->banned_day < date("Y-m-d")) || $userInfo->banned_day == null){

                if($settings->minimum_withdraw_points_limit <= $request->trx){
                    if($userInfo->balance >= $request->trx){

                        $alreadyWithrawnToday = DB::table('with_draws')->where('user_id', $request->user_id)->where('created_at', 'like', date("Y-m-d").'%')->first();
                        if($alreadyWithrawnToday){
                            return response()->json([
                                'success' => false,
                                'message' => 'Already Submitted a Withdarw Request Today'
                            ]);
                        }

                        DB::table('with_draws')->insert([
                            'user_id' => $request->user_id,
                            'trx' => $request->trx,
                            'payment_title' => $request->payment_title,
                            'user_wallet_address' => $request->user_wallet_address,
                            'status' => 0,
                            'created_at' => date("Y-m-d")."T".date("h:i:s").".000Z",
                        ]);

                        User::where('id', $request->user_id)->decrement('balance', $request->trx);

                        return response()->json([
                            'success' => true,
                            'message' => 'Withdraw Request Submitted'
                        ]);


                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Not Enough Balance'
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Minimum Withdraw Point is '.$settings->minimum_withdraw_points_limit
                    ]);
                }

            } else {
                return response()->json([
                    'success' => false,
                    'message' => $userInfo->banned_remarks
                ]);
            }


        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function withdrawHistory(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $data = DB::table('with_draws')->where('user_id', $request->user_id)->paginate(15);

            return response()->json([
                'success' => true,
                'data' => WithdrawResource::collection($data)->resource
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function settings(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $data = Settings::where('id', 1)->first();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }


    public function spinBuy(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $user_id = $request->user_id;
            $trx = $request->trx;
            $spin = $request->spin;

            $userInfo = User::where('id', $user_id)->first();
            if($userInfo->balance >= $trx){
                $userInfo->balance = $userInfo->balance - $trx;
                $userInfo->spin_count = $userInfo->spin_count + $spin;
                $userInfo->save();

                return response()->json([
                    'success' =>true,
                    'message' => "Spin Purchased Successfully"
                ]);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Not Enough Balance"
                ], 422);
            }

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function submitPurchaseSpin(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $user_id = $request->user_id;
            $trx = $request->points;

            $userInfo = User::where('id', $user_id)->first();

            if($userInfo->spin_count > 0){
                $userInfo->balance = $userInfo->balance + $trx;
                $userInfo->fixed_balance = $userInfo->fixed_balance + $trx;
                $userInfo->spin_count = $userInfo->spin_count - 1;
                $userInfo->save();

                Earnings::insert([
                    'user_id' => $user_id,
                    'earning_from' => 3,
                    'title' => "Spin The Wheel",
                    'points' => $trx,
                    'created_at' => Carbon::now()
                ]);

                User::where('refferal_code', $userInfo->ref_refferal_code)->increment('balance', (($trx*10)/100));
                User::where('refferal_code', $userInfo->ref_refferal_code)->increment('fixed_balance', (($trx*10)/100));
                User::where('refferal_code', $userInfo->ref_refferal_code)->increment('refferal_balance', (($trx*10)/100));

                return response()->json([
                    'success' => true,
                    'message' => "Balance Added Successfully"
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Failed to submit try again"
                ]);
            }



        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function miningClaim(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $todaysWebCount = Earnings::where('user_id', $request->user_id)->where("earning_from", 4)->where('created_at', 'LIKE', date("Y-m-d").'%')->count();
            if($todaysWebCount <= 0){
                $user_id = $request->user_id;
                $userInfo = User::where('id', $user_id)->first();
                date_default_timezone_set('America/New_York');

                // check user package
                $defaultPoint = 0.05 + (($userInfo->deposite_balance*2)/100);
                $packageInfo = DB::table('package_requests')->where('user_id', $userInfo->id)->where('status', 1)->orderBy('id', 'desc')->first();
                if($packageInfo){
                    $packageApproveDate = date("Y-m-d", strtotime($packageInfo->updated_at));
                    $packageValidity = $packageInfo->package_validity; //in days
                    $packageLastDate = date('Y-m-d', strtotime($packageApproveDate. " + $packageValidity days"));
                    $today = date("Y-m-d");
                    if($today <= $packageLastDate){
                        $defaultPoint = $packageInfo->package_ponts_per_day;
                    }
                }


                $userInfo->balance = $userInfo->balance + $defaultPoint;
                $userInfo->fixed_balance = $userInfo->balance + $defaultPoint;
                $userInfo->last_mining_started_at = date("Y-m-d H:i:s");
                $userInfo->save();

                Earnings::insert([
                    'user_id' => $user_id,
                    'earning_from' => 4,
                    'title' => "Check In",
                    'points' => $defaultPoint,
                    'created_at' => Carbon::now()
                ]);

                User::where('refferal_code', $userInfo->ref_refferal_code)->increment('balance', (($defaultPoint*5)/100));
                User::where('refferal_code', $userInfo->ref_refferal_code)->increment('fixed_balance', (($defaultPoint*5)/100));
                User::where('refferal_code', $userInfo->ref_refferal_code)->increment('refferal_balance', (($defaultPoint*5)/100));

                return response()->json([
                    'success' => true,
                    'message' => "Check In Done"
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Today's Limit is Over, Try Later",
                ]);
            }


        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function getWebsiteLink(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $user_id = $request->user_id;
            $data = Website::all();
            $earningData = Earnings::where('user_id', $user_id)->where('created_at', 'LIKE', date("Y-m-d").'%')->get();


            $websites = array();

            $i = 0;
            foreach($data as $item){

                $visiting_status = 0;
                foreach($earningData as $earning){
                    if($earning->website_id == $item->id){
                        $visiting_status = 1;
                        break;
                    }
                }

              	if($visiting_status == 0){
                    $websites[$i]['id'] = $item->id;
                    $websites[$i]['title'] = $item->title;
                    $websites[$i]['logo'] = $item->logo;
                    $websites[$i]['description'] = $item->description;
                    $websites[$i]['link'] = $item->link;
                    $websites[$i]['visiting_seconds'] = $item->visiting_seconds;
                    $websites[$i]['visiting_status'] = $visiting_status;
                    $websites[$i]['created_at'] = $item->created_at;
                    $websites[$i]['updated_at'] = $item->updated_at;
                    $i++;
                }

            }

            return response()->json([
                'success' => true,
                'data' => $websites
                // 'data' => WebsiteResource::collection($data->random(10))
            ]);


        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function getWebsiteLinkNew(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $user_id = $request->user_id;
            //$data = Website::all();
          $data = Website::paginate(10);
          $count = Website::all()->count();
            $earningData = Earnings::where('user_id', $user_id)->where('created_at', 'LIKE', date("Y-m-d").'%')->get();


            $websites = array();

            $i = 0;
            foreach($data as $item){

                $visiting_status = 0;
                foreach($earningData as $earning){
                    if($earning->website_id == $item->id){
                        $visiting_status = 1;
                        break;
                    }
                }

              	// if($visiting_status == 0){
                    $websites[$i]['id'] = $item->id;
                    $websites[$i]['title'] = $item->title;
                    $websites[$i]['logo'] = $item->logo;
                    $websites[$i]['description'] = $item->description;
                    $websites[$i]['link'] = $item->link;
                    $websites[$i]['visiting_seconds'] = $item->visiting_seconds;
                    $websites[$i]['visiting_status'] = $visiting_status;
                    $websites[$i]['created_at'] = $item->created_at;
                    $websites[$i]['updated_at'] = $item->updated_at;
                    $i++;
                // }

            }

            return response()->json([
                'success' => true,
                //'data' => $this->paginate($websites)
              'data' => $websites,
              'count'=> $count
                // 'data' => WebsiteResource::collection($data->random(10))
            ]);


        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function getRefferalIncome(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $user_id = $request->user_id;
            $userInfo = User::where('id', $user_id)->first();
            $refferalCode = $userInfo->refferal_code;

            $data = User::where('ref_refferal_code', $refferalCode)->paginate(15);

            return response()->json([
                'success' => true,
                'date' => UserResource::collection($data)->resource
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function topRefferalList(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $data = array();
            $days = (int) $request->day == '' ? 1 : $request->day;
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

                    if(count($data) >= 10){
                        break;
                    }

                    $bonus = DB::table('user_refferal_bonuses')
                                ->where('created_at', '>=', $timeLimit)
                                ->where('user_id', $userInfo->id)
                                ->sum('bonus');

                    $data[$i]['user_id'] = $userInfo->id;
                    $data[$i]['name'] = $userInfo->name;
                    $data[$i]['email'] = $userInfo->email;
                    $data[$i]['refferal_code'] = $userInfo->refferal_code;
                    $data[$i]['refferal_count'] = $mostUsedRefferal->refferal_count;
                    $data[$i]['trx'] = $bonus;
                    $i++;
                }
            }

            return response()->json([
                'success' => true,
                'date' => $data
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }


    public function accountStatusSubmit(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $requestStatus=0;
            $accountStatus=1;

            if(strlen($request->transaction_hash)<61){
                $requestStatus=2;
                $accountStatus=3;
            }else{
                $requestStatus=0;
                $accountStatus=1;
            }

             AccountStatusSubmit::insert([
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'wallet_address_admin' => $request->wallet_address_admin,
                'transaction_hash' => $request->transaction_hash,
                'status' => $requestStatus,
                'created_at' => Carbon::now(),
            ]);

            User::where('id', $request->user_id)->update([
                'account_status' => $accountStatus
            ]);

            return response()->json([
                'success' => true,
                'message' => "Submitted Successfully"
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function accountSubmissionHistory(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $data = AccountStatusSubmit::where('user_id', $request->user_id)->orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => AccountSubmissionResource::collection($data)
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }


    public function accountAutoDenied(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $data = AccountStatusSubmit::where('transaction_hash', $request->transaction_hash)->where('status', '0')->orderBy('id', 'desc')->get();

            DB::table('account_status_submits')->where('transaction_hash', $request->transaction_hash)->update([
                        'status' => 2,
                        'updated_at' => Carbon::now()
                    ]);
            for($i=0; $i<sizeof($data); $i++)
            {

                User::where('id', $data[$i]['user_id'])->update([
                        'account_status' => 3
                    ]);
            }


            return response()->json([
                'success' => true,
                'delete' => sizeof($data),
                'data' => AccountSubmissionResource::collection($data)
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }


    public function accountStatusSubmitUpdated(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $requestStatus=0;
            $accountStatus=1;

            if(strlen($request->transaction_hash)<61){
                $requestStatus=2;
                $accountStatus=3;
            }else{
                $requestStatus=0;
                $accountStatus=1;
            }

             AccountStatusSubmit::insert([
                        'user_id' => $request->user_id,
                        'amount' => $request->amount,
                        'wallet_address_admin' => $request->wallet_address_admin,
                        'transaction_hash' => $request->transaction_hash,
                        'status' => $requestStatus,
                        'created_at' => Carbon::now(),
                    ]);

            User::where('id', $request->user_id)->update([
                'account_status' => $accountStatus
            ]);

            return response()->json([
                'success' => true,
                'message' => "Submitted Successfully"
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function updateDeviceToken(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $user_id = $request->user_id;
            $device_id = $request->device_id;
            $token = $request->token;
            $login_status = $request->login_status;

            $userDeviceInfo = UserDevice::where('user_id', $user_id)->where('device_id', $device_id)->first();
            if($userDeviceInfo){

                UserDevice::where('user_id', $user_id)->where('device_id', $device_id)->update([
                    'token' => $token,
                    'login_status' => $login_status,
                    'updated_at' => Carbon::now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "Successfully Updated",
                ]);

            } else {

                UserDevice::insert([
                    'user_id' => $user_id,
                    'device_id' => $device_id,
                    'token' => $token,
                    'login_status' => $login_status,
                    'created_at' => Carbon::now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "Successfully Inserted",
                ]);
            }

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }

    public function transferDepositeBalance(Request $request){
        if ($request->header('Authorization') == ApiController::AUTHORIZATION_TOKEN) {

            $user_id = $request->user_id;
            $amount = $request->amount;
            $userInfo = User::where('id', $user_id)->first();

            if($userInfo->deposite_balance < $amount){
                return response()->json([
                    'success' => false,
                    'message' => "Not Enough Deposite Balance"
                ]);
            } else {
                $userInfo->deposite_balance = $userInfo->deposite_balance - $amount;
                $userInfo->balance = $userInfo->balance + $amount;
                $userInfo->save();

                Earnings::insert([
                    'user_id' => $user_id,
                    'website_id' => null,
                    'title' => 'Transfer Deposite into Balance',
                    'earning_from' => 9,
                    'points' => $amount,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "Transfered Deposites Into Balance"
                ]);
            }

        } else {
            return response()->json([
                'success' => false,
                'message' => "Authorization Token is Invalid"
            ], 422);
        }
    }



}
