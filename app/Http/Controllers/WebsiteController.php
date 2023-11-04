<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Image;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;

class WebsiteController extends Controller
{
    public function addNewWebsite(){
        return view('backend.add_new_website');
    }

    public function saveNewWebsite(Request $request){

        $logo = null;
        if ($request->hasFile('logo')){
            $get_image = $request->file('logo');
            $image_name = str::random(5) . time() . '.' . $get_image->getClientOriginalExtension();
            // Image::make($get_image)->save('website_logo/' . $image_name, 50);
            $location = public_path('website_logo/');
            $get_image->move($location, $image_name);
            $logo = "website_logo/" . $image_name;
        }

        Website::insert([
            'title' => $request->title,
            'logo' => $logo,
            'description' => $request->description,
            'link' => $request->link,
            'visiting_seconds' => $request->visiting_seconds,
            'created_at' => Carbon::now()
        ]);

        Toastr::success('New Website Link has been Added', 'Success');
        return back();
    }

    public function viewAllWebsite(){
        $data = Website::orderBy('id', 'desc')->paginate(15);
        return view('backend.view_all_websites', compact('data'));
    }

    public function deleteWebsite($id){
        Website::where('id', $id)->delete();
        Toastr::error('Website has been Deleted', 'Deleted');
        return back();
    }

}
