<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use Carbon\Carbon;
use App\Models\Earnings;
use Illuminate\Support\Facades\DB;

class MiningProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('max_execution_time', '7200');
        ini_set('memory_limit', '4096M');

        $users = User::where('last_mining_started_at', '!=', NULL)->where('last_mining_started_at', 'LIKE', date("Y-m-d").'%')->orderBy('id', 'desc')->get();
        foreach($users as $user){

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
}
