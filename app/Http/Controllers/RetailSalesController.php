<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;
use Auth;
class RetailSalesController extends Controller
{
   
  public function __construct()
  {
      $this->middleware('auth');

  }


    public function retailsalesdashboard(){
        return view('retail.sales.dashboard');
    }
    public function retailsalesprofile(){
        return view('retail.sales.profile');
    }

    public function retailsalesterminal1(){
     
      return view('retail.sales.terminal1');

    }

    
public function changepassword(request $request){

    $messages = [
      'currentpassword.required' => 'Current password is required.',
      'newpassword.required' => 'New password is required.',
      'comfirmpassword.required' => 'Comfirming new password is mandatory.',
      'comfirmpassword.same' => 'New password and confirm password do not match.',
      'newpassword.min' => 'New password must be at least 4 characters'
  ];
  
  $validator = $request->validate([
      'currentpassword' => 'required',
      'newpassword' => 'required|min:4',
      'comfirmpassword' => 'required|same:newpassword',
  ], $messages);
  
  if($validator){
    $hashedPassword=DB::table('users')->where('id',Auth::user()->id)->value('password');
  
    if(Hash::check($request->currentpassword, $hashedPassword)) {
  
      $data =array();
      $data['password'] = Hash::make($request->newpassword);
      $updatePassword = DB::table('users')->where('id',Auth::user()->id)->update($data);
      if( $updatePassword){
        return response()->json(['success' => 'Password changed successfully','status'=>201]);
      }
      else{
        return response()->json(['error' => 'An unexpected error occurred while updating your password','status'=>422]);
      }
  
    }else{
      return response()->json(['error' => 'The current password you entered is incorrect. Please try again','status'=>422]);
    }
  
  }
  else {
    return back()->withErrors($validator)->withInput();
  }
  
  
  }
  

public function submitsolddata(request $request){

  $sales = json_decode($request->data, true);

   $chunks = array_chunk($sales,50);

   $insertArray =  array_values($chunks[0]);

    unset($chunks[0]);

      for($i=0;$i<count($insertArray);$i++){

          $productid = $insertArray[$i]['Productid'];
          $product = $insertArray[$i]['Product'];
          $unit = $insertArray[$i]['Unit'];
          $price = $insertArray[$i]['Price'];
          $quantity = $insertArray[$i]['Quantity'];
          $user= $insertArray[$i]['User'];
          $branch= $insertArray[$i]['Branch'];
          $date= $insertArray[$i]['Date'];
          $time = $insertArray[$i]['Time'];
          $transid = $insertArray[$i]['Transid'];
          $slot = 0;	
          $oldqty = DB::table('retailbranchproducts')->where('id',$productid)->value('quantity');

          $newqty = $oldqty-$quantity;

          if($newqty<0){

           $newqty  = 0;

          }
          $newdata['quantity']=$newqty;


          $retailSales['productid']=$productid;
          $retailSales['product']=$product;
          $retailSales['unit']=$unit;
          $retailSales['price']=$price;
          $retailSales['quantity']=$quantity;
          $retailSales['rquantity']=$newqty;
          $retailSales['user']=$user;
          $retailSales['branch']=$branch;
          $retailSales['date']=$date;
          $retailSales['time']=$time;
          $retailSales['slot']=$slot;
          $retailSales['transid']=$transid;

          $insertSales =  DB::table('retailsales')->insertOrIgnore($retailSales);
         
          if($insertSales){

              DB::table('retailbranchproducts')->where('id',$productid)->update($newdata);	

          }
         

           
      }  
      

      $mergeArray=array_merge($chunks);

     return   $mergeArray;
}
 
public function insertintervalsales(request $request){

  $data = array();
  $data2 = array();
  $data3 = array();
  $data4 = array();

  $data['branch']=$request->branch;
  $data['user']=$request->user;
  $data['date']=$request->date;
  $data['slot']=$request->slot;
  $data['sales']=$request->sales;


  $data2['date']=$request->date;
  $data2['branch']=$request->branch;
  $data2['sales']=$request->sales;


 
  $data4['slot']=$request->slot;






$insertInterval =   DB::table('intervalsales')->insertOrIgnore($data);


if($insertInterval){
  
  DB::table('retailsales')->where('branch',$request->branch)->where('date',$request->date)->where('slot','0')->update($data4);
  $checkmanualsales = DB::table('retailmanualsales')->where('date', $request->date)->where('branch', $request->branch)->count();
  if($checkmanualsales > 0 ){
  $oldsales = DB::table('retailmanualsales')->where('date', $request->date)->where('branch', $request->branch)->value('sales');
  $newsales = $oldsales + $request->sales; 
  $data3['sales'] = $newsales;
  DB::table('retailmanualsales')->where('date', $request->date)->where('branch', $request->branch)->update($data3);
  }
  else{
  DB::table('retailmanualsales')->insertOrIgnore($data2);
  }
  return json_encode(2);
}
  else{

      
      return json_encode(1);
 
 
  }

}

public function editintervalsales(request $request){

  $data = array();
  $data2 = array();


  $oldsales = $request->oldsales;
  $sales = $request->sales;
  $data['sales']= $sales;
  $updatedata = DB::table('intervalsales')->where('id',$request->id)->update($data);
  if($updatedata){
      $oldvalue = DB::table('retailmanualsales')->where('branch',$request->branch)->where('date',$request->date)->value('sales');
      $newvalue = $oldvalue+$sales-$oldsales;
       $data2['sales']= $newvalue;
       DB::table('retailmanualsales')->where('branch',$request->branch)->where('date',$request->date)->update($data2);
      return json_encode(2);
  }
  else{

      return json_encode(1);
  }



}


  


}
