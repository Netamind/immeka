<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use DB;
use Auth;

class SessionController extends Controller
{
   
public function selectCategory(Request $request) {
    $user = Auth::user()->id;
    $checkuser = DB::table('session')->where('user',$user)->count();
    if($checkuser){
    $updateSupplier = DB::table('session')->where('user',$user)->update(["category"=>$request->category]);
    if($updateSupplier){
     Cookie::queue(Cookie::forget('supplier'));
    } 
    return Redirect()->back();
    }
    else{
    $insertSupplier=DB::table('session')->insert(["category"=>$request->category ,"user"=>$user]);
     if($insertSupplier){
        Cookie::queue(Cookie::forget('supplier'));
        }
     return Redirect()->back();
    }
}
public function selectSupplier(Request $request) 
{ 
    $sup = $request->supplier;
    Cookie::queue('supplier', $sup, 144000); // expires in 100 days ( converted to minutes )
    return Redirect()->back();
}

public function selectBranch(Request $request) 
{ 
    $branch = $request->branch;
    Cookie::queue('branch', $branch, 144000); 
    return Redirect()->back();
}


public function changedateinterval(Request $request)
{
    $startdate = Carbon::parse($request->startdate);
    $enddate = Carbon::parse($request->enddate);

    if ($startdate->isBefore($enddate) && $enddate->diffInDays($startdate) <= 124) {
        Cookie::queue('startdate', $startdate->format('Y-m-d'), 144000);
        Cookie::queue('enddate', $enddate->format('Y-m-d'), 144000);
        return Redirect()->back();
    } else {

        $notification=array(
            'message'=>'Error!. Startdate must be less than enddate and the range should be not more than 124 days ',
            'alert-type'=>'error'
            );
          return Redirect()->back()->with($notification);
     
    }
}




public function selectproduct(Request $request) 
{ 
    $product = $request->product;
    Cookie::queue('product', $product, 144000); 
    return Redirect()->back();
}



public function selectwdate(Request $request)
{
  $wdate = Carbon::parse($request->date);
  $now = Carbon::now();
  $diffInDays = $now->diffInDays($wdate);

  if ($diffInDays <= 124) {
    Cookie::queue('wdate', $wdate->format('Y-m-d'), 144000);
    return Redirect()->back();
  } else {
    $notification = array(
      'message' => 'Error!. The selected date should be within the last 124 days',
      'alert-type' => 'error'
    );
    return Redirect()->back()->with($notification);
  }
}
/*retail */

public function selectrCategory(Request $request) {
  $user = Auth::user()->id;
  $checkuser = DB::table('session')->where('user',$user)->count();
  if($checkuser){
  $updateSupplier = DB::table('session')->where('user',$user)->update(["rcategory"=>$request->category]);
  if($updateSupplier){
   Cookie::queue(Cookie::forget('rsupplier'));
  } 
  return Redirect()->back();
  }
  else{
  $insertSupplier=DB::table('session')->insert(["rcategory"=>$request->category ,"user"=>$user]);
   if($insertSupplier){
      Cookie::queue(Cookie::forget('rsupplier'));
      }
   return Redirect()->back();
  }
}
public function selectrSupplier(Request $request) 
{ 
  $sup = $request->supplier;
  Cookie::queue('rsupplier', $sup, 144000); // expires in 100 days ( converted to minutes )
  return Redirect()->back();
}

public function selectrBranch(Request $request) 
{ 
  $branch = $request->branch;
  Cookie::queue('rbranch', $branch, 144000); 
  return Redirect()->back();
}


public function selectrproduct(Request $request) 
{ 
    $product = $request->product;
    Cookie::queue('rproduct', $product, 144000); 
    return Redirect()->back();
}



public function selectrdate(Request $request)
{
  $wdate = Carbon::parse($request->date);
  $now = Carbon::now();
  $diffInDays = $now->diffInDays($wdate);

  if ($diffInDays <= 124) {
    Cookie::queue('rdate', $wdate->format('Y-m-d'), 144000);
    return Redirect()->back();
  } else {
    $notification = array(
      'message' => 'Error!. The selected date should be within the last 124 days',
      'alert-type' => 'error'
    );
    return Redirect()->back()->with($notification);
  }
}



}
