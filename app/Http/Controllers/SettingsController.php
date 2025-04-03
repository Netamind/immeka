<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
class SettingsController extends Controller
{
    public function adminhomepagesettings(){
        return view('settings.admin.homepagesettings');

    }
    public function setadminhomepage(Request $request)
     {
    $data = array();
    $data['user'] = Auth::user()->id;
    $data['sector'] = $request->sector;
    $data['status'] = $request->status;

    $checkSettings = DB::table('adminhomepagesettings')
        ->where('user', Auth::user()->id)
        ->where('sector', $request->sector)
        ->count();
    if ($checkSettings > 0) {
        $updated = DB::table('adminhomepagesettings')
            ->where('user', Auth::user()->id)
            ->where('sector', $request->sector)
            ->update($data);

        if ($updated) {
            $notification=array(
                'message'=>'Settings updated successifully ',
                'alert-type'=>'success'
                );

              return Redirect()->back()->with($notification);

        } else {
            $notification=array(
                'message'=>'Failed to update settings. Please try again! ',
                'alert-type'=>'error'
                );

              return Redirect()->back()->with($notification);
        }
    } else {
        $inserted = DB::table('adminhomepagesettings')->insert($data);
        if ($inserted) {
            $notification=array(
                'message'=>'Settings updated successifully ',
                'alert-type'=>'success'
                );

              return Redirect()->back()->with($notification);
        } else {
            $notification=array(
                'message'=>'Failed to update settings. Please try again! ',
                'alert-type'=>'error'
                );

              return Redirect()->back()->with($notification);
        }
      }
   }



    
}
