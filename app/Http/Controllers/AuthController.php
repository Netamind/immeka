<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Hash;
use DB; 
use Carbon\Carbon; 
use App\Models\User; 
use Mail; 
use Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
 
    public function userlogin( Request $request){

        $hashedPassword=DB::table('users')->where('email',$request->email)->value('password');

        $level=DB::table('users')->where('email',$request->email)->value('role');
        
        $userid=DB::table('users')->where('email',$request->email)->value('id');

        $employeeId = DB::table('users')->where('email',$request->email)->value('employeeid');

        $userbranch=DB::table('userbranch')->where('employeeid',$employeeId)->value('branchid');

        $userbranchsector = DB::table('branches')->where('id',  $userbranch)->value('sector');

        if(Hash::check($request->password, $hashedPassword)) {
            
        if($level == 'Admin' ){

            Auth::loginUsingId($userid);

            return  redirect('admin-dashboard');
        } 

        elseif($level=='Sales'){

            if($userbranchsector=="Retail" ){
           
                Auth::loginUsingId($userid);
                return  redirect('retail-sales-dashboard');

            }
            elseif($userbranchsector=="Wholesale"){

                Auth::loginUsingId($userid);
                return  redirect('retail-sales-dashboard');

            }
            else{

                $notification=array(
                    'message'=>'Dashboard for your role is not available ',
                    'alert-type'=>'info'
                    );
                  return Redirect()->back()->with($notification);

            }
          
           
        
        }
    else{

        $notification=array(
            'message'=>'Your role is not defined in the system ',
            'alert-type'=>'info'
            );
          return Redirect()->back()->with($notification);
     
    }      
        }
        else{

            $notification=array(
                'message'=>'Wrong login credentials ',
                'alert-type'=>'error'
                );

              return Redirect()->back()->with($notification);
           
        }

    
         }

            
        public function signout()
        {
            Auth::logout();
            return redirect('/');
        }
    



}
