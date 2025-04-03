<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use PDF;
use DB;
use Auth;
class RetailController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function retaildashboardview(){
        $level = Auth::user()->role;
        if($level=="RSales"){
          return view('retail.retaildashboard');
        }else{
           return redirect('/');
        }
        
        
    }
    public function retailprofileview(){
        $level = Auth::user()->role;
        if($level=="RSales"){
            return view('retail.profile');
        }else{
           return redirect('/');
        }
      
    }

    public function productlistretailview(){
        $level = Auth::user()->role;
        if($level=="RSales"){
            return view('retail.productlist');
        }else{
           return redirect('/');
        }

        
      
    }

    public function retailposmobileview(){
        $level = Auth::user()->role;
        if($level=="RSales"){
            return view('retail.posmobile');
        }else{
           return redirect('/');
        }


        
    }

    public function  retaildesktopview(){
        $level = Auth::user()->role;
        if($level=="RSales"){
            return view('retail.posdesktop');
        }else{
           return redirect('/');
        }

        
    }

    public function     branchinfo(){
        $level = Auth::user()->role;
        if($level=="RSales"){
            return view('retail.branchinfo');
        }else{
           return redirect('/');
        }

       
    }

    
    public function   rbsystemsales(){
        $level = Auth::user()->role;
        if($level=="RSales"){
            return view('retail.rbsystemsales');
        }else{
           return redirect('/');
        }

       
    }

     
    public function     rmanualsales(){

        $level = Auth::user()->role;
        if($level=="RSales"){
            return view('retail.rmanualsales');
        }else{
           return redirect('/');
        }

       

    }

       
    public function   retailtransactionsview(){

        $level = Auth::user()->role;
        if($level=="RSales"){
            return view('retail.retailtransactions');
        }else{
           return redirect('/');
        }

       

    }


    
public function retailregularorder(){

    return view('retail.retailregularorder');
}


public function retailemergencyorder(){

    return view('retail.retailemergencyorder');
}

public function retailsidedeliverynote(){

    return view('retail.retailsidedeliverynote');
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
            $oldqty = DB::table('retailproducts')->where('id',$productid)->value('quantity');

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

                DB::table('retailproducts')->where('id',$productid)->update($newdata);	

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
  
    $checkmanualsales = DB::table('manualsales')->where('date', $request->date)->where('branch', $request->branch)->count();
    if($checkmanualsales > 0 ){
    $oldsales = DB::table('manualsales')->where('date', $request->date)->where('branch', $request->branch)->value('sales');
    $newsales = $oldsales + $request->sales; 
    $data3['sales'] = $newsales;
    DB::table('manualsales')->where('date', $request->date)->where('branch', $request->branch)->update($data3);
    }
    else{
    DB::table('manualsales')->insertOrIgnore($data2);
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
        $oldvalue = DB::table('manualsales')->where('branch',$request->branch)->where('date',$request->date)->value('sales');
        $newvalue = $oldvalue+$sales-$oldsales;
         $data2['sales']= $newvalue;
         DB::table('manualsales')->where('branch',$request->branch)->where('date',$request->date)->update($data2);
        return json_encode(2);
    }
    else{

        return json_encode(1);
    }



}


public function updatebranchinfo(request $request){

    $data = array();
    $data['address']=$request->address;
    $data['contact']=$request->contact;

    $updatedata = DB::table('rbranchinfo')->where('branch',$request->branch)->update($data);
if( $updatedata){
    $notification=array(
        'message'=>'Data updated successifully',
        'alert-type'=>'success'
        );
      return Redirect()->back()->with($notification);

}else{

    $notification=array(
        'message'=>'An error occured, no data change detected',
        'alert-type'=>'error'
        );
      return Redirect()->back()->with($notification);

}

}


public function insertretailtransaction(request $request){

    
    $data = array();
    $data['date']=$request->date;
    $data['branch']=$request->branch;
    $data['productid']=$request->productid;
    $data['category']=$request->category;
    $data['product']=$request->product;
    $data['unit']=$request->unit;
    $data['quantity']=$request->quantity;
    $data['price']=$request->price;
    $data['user']=$request->user;
    $data['description']=$request->description;

    $insertData = DB::table('retailtransactions')->insert($data);

    if($insertData){
        return json_encode(2);
    }else{
        return json_encode(1);


    }


}

    
   
}
