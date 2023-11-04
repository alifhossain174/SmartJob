<?php

namespace App\Http\Controllers;

use App\Models\FbAdNetwork;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class FacebookAdConfigController extends Controller
{
    public function facebookAdConfigPage(){
        $data = FbAdNetwork::where('id', 1)->first();
        return view('backend.fb_ad_config', compact('data'));
    }

    public function updateAdInfo(Request $request){
        FbAdNetwork::where('id', 1)->update([
            'hash_key' => $request->hash_key,
            'fb_ad_id' => $request->fb_ad_id,
            'banner_ad_id' => $request->banner_ad_id,
            'native_ad_id' => $request->native_ad_id,
            'interstitial_ad_id' => $request->interstitial_ad_id,
            'rewardedVideoAdID' => $request->rewardedVideoAdID,
            'show_status' => $request->show_status,
            'interstitial_show_status' => $request->interstitial_show_status,
            'native_show_status' => $request->native_show_status,
            'banner_show_status' => $request->banner_show_status,
        ]);
        Toastr::success('Information Updated', 'Success');
        return back();
    }
}
