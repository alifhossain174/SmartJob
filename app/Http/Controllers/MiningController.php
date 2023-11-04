<?php

namespace App\Http\Controllers;

use App\Jobs\MiningProcess;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Earnings;
use Illuminate\Support\Facades\DB;

class MiningController extends Controller
{
    public function startMining(){

        dispatch(new MiningProcess);

        // also run php artisan queue:listen to send the job in queue

        // dd('Mining Strated !!');

    }

    public function startMiningNew(){

        ini_set('max_execution_time', '7200');
        ini_set('memory_limit', '4096M');

        User::chunk(100, function ($users) {
            foreach($users as $user){

                if( $user->last_mining_started_at != NULL && date("Y-m-d", strtotime($user->last_mining_started_at)) == date("Y-m-d") ){
                    $packageInfo = DB::table('package_requests')
                                    ->join('users', 'package_requests.user_id', '=', 'users.id')
                                    ->where('users.id', $user->id)
                                    ->where('package_requests.status', 1)
                                    ->orderBy('package_requests.id', 'desc')
                                    ->first();

                    $pointsPerHour = $packageInfo ? $packageInfo->package_ponts_per_hour : 16.666;

                    Earnings::insert([
                        'user_id' => $user->id,
                        'title' => "Mining",
                        'earning_from' => 4, //daily mining
                        'points' => $pointsPerHour,
                        'created_at' => Carbon::now(),
                    ]);

                    User::where('id', $user->id)->increment('balance', $pointsPerHour);

                    if($user->ref_refferal_code != NULL){
                        $refferedUser = User::where('refferal_code', $user->ref_refferal_code)->first();
                        if($refferedUser){
                            User::where('refferal_code', $user->ref_refferal_code)->increment('balance', round(($pointsPerHour*10)/100));
                            Earnings::insert([
                                'user_id' => $refferedUser->id,
                                'title' => "Referral",
                                'earning_from' => 1, //from refferal
                                'points' => (($pointsPerHour*10)/100),
                                'created_at' => Carbon::now(),
                            ]);
                        }
                    }
                }

            }
        });

    }

}
