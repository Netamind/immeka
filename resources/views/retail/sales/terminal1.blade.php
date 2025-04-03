@extends('retail.sales.dashboard')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>


.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  border-top: 4px solid #f35800; /* orange color */
  border-radius: 50%;
  width: 20px;
  height: 20px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
.loading-status {
  top: 0;
  left: 0;
  width: 110%;
  height: 8px;
  background-color: #f7f7f7;
  z-index: 1000;
  overflow: hidden;
}
.waves {
  position: absolute;
  top: 0;
  left: -10%;
  width: 110%;
  height: 7px;
  background-image: repeating-linear-gradient(120deg, #007bff, #007bff 10px, #000 10px, #000 20px);
  border-radius: 0;
  animation: move 2.5s linear infinite;
}

@keyframes move {
  0% {
    background-position: 0 0;
  }
  100% {
    background-position: 40px 0;
  }
}

.no-focus-outline {
  outline: none;
}

input[type="updatecart"]:focus { 
    outline: 1px solid blue;
}



input[type="updatecart2"]:focus { 
    outline: 1px solid blue;
}


input[type="submitdnoteerrorinput"]:focus { 
    outline: none
}

input[type="inputbox"]
{
    background: transparent;

}


input[type="inputbox"]:focus
{
    outline:1px none;
    background: transparent;

}




input[type="carttime"]
{
    background: transparent;
    border: none;
}

#tablediv{

  background-color:#cccccc;

  /*background-color:#e6c300*/

}


#tablediv2{

background-color:#e6c300;



}

.submit-data-input2{
  background:none;
  border: 1px ridge  #b3b3b3;
}



.submit-data-input{
  background:none;
  border: 1px ridge  #b3b3b3;
}

.trow{
  color:black;

}

tr.trow td {
  
  /*border-width: 1px; border-color:  #a6a6a6*/
   /*border-width: 1px; border-color:  #a6a6a6*/
   border-bottom: 1px solid  #a6a6a6;
  border-top: 1px solid  #a6a6a6;

}

.tableFixHead {
        overflow-y: auto; /* make the table scrollable if height is more than 200 px  */
        height:70vh; /* gives an initial height of 200px to the table */
      }
      .tableFixHead thead th {
        position: sticky; /* make the table heads sticky */
        top: 0px; /* table head will be placed from the top of the table and sticks to it */
      }



.calculator {
  width: 100%;
  height: auto;
  /*margin: 70px auto 0;*/
  overflow: hidden;
  box-shadow: 4px 4px rgba(0, 0, 0, 0.2);
}

.calculator span {
  -moz-user-select: none;
  user-select: none;
}

.top {
  position: relative;
  height: 150px;
  background-color: blue;

}

.top .unit {
  text-transform: uppercase;
  position: absolute;
  top: 10px;
  left: 10px;
  font-weight: 700;
  color: #757575;
}

.top .screen {
  position: relative;
  width: 100%;
  top: 20%;
  height: 80%;
}

.screen > div  {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  width: 100%;
  padding: 5px;
  text-align: right;
}

.screen .input {
  color: #757575;
  height: 60%;
  font-size: 35px;
}

.screen .result {
  color: #9e9e9e;
  font-size: 20px;
  height: 40%;
}

.bottom {
  background-color: #2D2D2D;
  height: 300px;
  color: #fff;
  cursor: pointer;
}

.bottom section {
  position: relative;
  height: 100%;
  float: left;
  display: flex;
}

.keys {
  width: 80%;
}


.keys .column {
  display: flex;
  flex-grow: 1;
}

.keys .column, .operators {
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.keys .column span, .operators span {
  position: relative;
  overflow: hidden;
  flex-grow: 1;
  width: 100%;
  line-height: 1;
  padding: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: background-color 0.5s;
}

.keys .column span {
  font-size: 25px;
}

.keys .column span:hover, .operators span:hover {
  background-color: rgba(0, 0, 0, 0.2);
}

.operators {
  width: 20%;
  font-size: 18px;
  background-color: #434343;
}

.delete {
  font-size: 16px;
  text-transform: uppercase;
}

.credit {
  display: block;
  text-align: center;
  width: 100%;
  position: absolute;
  bottom: 20px;
  margin: 0 auto;
}

.credit a, .error {
  color: #C2185B;
}

.cart-input:focus {
    background-color: transparent;
    box-shadow: none;
    outline: none;
}
</style>
</head>
<body  onload="onLoadFunctions()"  >
    <?php
    use Carbon\Carbon;
    $date = Carbon::today()->toDateString();
    $userbranch = DB::table('userbranch')->where('employeeid',Auth::user()->employeeid)->value('branchid');
    $user = Auth::user()->id;
    $disaplaydate = Carbon::createFromFormat('Y-m-d',$date)->format('d F Y');
    $datestring = preg_replace('/-/', '',  $date); 
     function getTransid($n) {
      $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $randomString = '';
      for ($i = 0; $i < $n; $i++) {
          $index = rand(0, strlen($characters) - 1);
          $randomString .= $characters[$index];
      }
      return $randomString;
      }
     $transid =  $datestring.getTransid(5);
     $customersToday = DB::table('retailsales')->where('branch',$userbranch)->where('date', $date)->distinct('transid')->count(); 
    ?>


<!--start page wrapper -->
<div class="page-wrapper">
<div class="page-content">


<div class="loading-status" id="loading-status" style="display:none">
  <div class="waves"></div>
</div>




<!-- Main content -->
<section>

<div class="row" style="margin-top:-15px">

<div id="transDiv">
<form action="#" id="transidForm">
  <input type="hidden" id="transidInput" value="{{$transid}}">
</form>
</div>




<div class="col-md-12 " style="background-color:silver" >


<a href="retail-sales-terminal1" title="" style="font-size:14px;font-weight:bold;color:#666666;">
  <i class="fa fa-calendar" style="margin-top:10px"></i>  {{$disaplaydate}}
</a>


<a href="#" class="btn " id="addsalesinterval" style="float:right;color:#666666;font-weight:bold;padding:3px;" >       
<i class="fa fa-plus-circle" style="font-size:14px"></i>
</a>

<a href="#" class="btn " id="salesbtn" style="float:right;color:#666666;font-weight:bold;padding:3px;" >       
<i class="fa fa-eye" style="font-size:15px"></i>
</a>


<a href="#" id="recentsalesbtn" class="btn" style="float:right;color:#666666;font-weight:bold;padding:3px;">
<i class="fa fa-list"  style="font-size:13px"></i>
</a>



<a href="#" class="btn "  id="unuploadeddatabtn" style="float:right;color:#666666;font-weight:bold;padding:3px;">      
<i class="fa fa-cloud" style="font-size:13px"></i>   
</a>




</div>
<div class="col-md-12">
  <div class="row">
    <div class="col-md-12  bg-primary"> 
         
    <a href="#" class="btn"   style="border: 2px solid silver;font-size:14px;margin-top:10px;margin-bottom:5px" onclick="uploadData()" id="uploaddatabtn">
    <i class="fa fa-cloud-upload" style="color:silver"></i><span style="color:silver;font-size:15px;font-weight:bold">|</span>
     <span style="font-weight:bold;color:silver;" id="cloudItems"></span>
    </a>


      
    <a href="#" class="btn"   style="border: 2px solid silver;font-size:14px;margin-top:10px;margin-bottom:5px;"  id="calculatorbtn" >
    <i class="fa fa-bell" style="color:silver;font-size:15px"></i>
    </a>
  
      
    <a href="#" class="btn"   style="border: 2px solid silver;font-size:14px;margin-top:10px;margin-bottom:5px;"  id="calculatorbtn" >
    <i class="fa fa-calculator" style="color:silver;font-size:15px"></i>
    </a>
    
    <a href="#" class="btn"   style="border: 2px solid silver;font-size:14px;margin-top:10px;margin-bottom:5px;margin-right:10px;"  id="calculatorbtn" >
    <i class="fa fa-exchange" style="color:silver;font-size:15px"></i>
    </a>


    <a href="retail-sales-terminal1" class="btn"   style="font-size:18px;margin-top:10px;margin-bottom:5px;float:right"   >
    <i class="fa fa-refresh" style="color:silver;font-size:16px"></i>
    </a>


      </div>
    </div>
</div>

<!--Left Column-->
<div class="col-md-5 border border-primary" style="height: 100%;margin-top:-3px;">

<div class="row">

<div class="col-md-12  bg-primary" style="height:50px">     
<div class="input-group border border-primary" style="margin-top:6px">      
<input type="text" class="form-control" id="mobile-search" style="background-color:silver;text-transform: uppercase;font-weight:bold;border:1px solid silver" placeholder="Search a product you want to sell" autocomplete="off">                
</div>
</div>


<!--producs table-->
<div class="col-md-12 border border-primary " id="tablediv" > 
<table class=" table table-sm table mobile-table table-striped" style="display:none;font-size:15px;"   id="mobile-table">

<thead style="border:0">
<tr style="border:0">
<th style="border:0"></th>
<th style="text-align:center;border:0"></th>
</tr>
</thead>

<tbody>

<?php
$products = DB::table('retailbranchproducts')->where('branch',$userbranch)->get()
?>
@foreach($products as  $product)
<tr class="trow">
<?php
  $productname = DB::table('retailbaseproducts')->where('id',$product->product)->value('product');
  $unit = DB::table('retailbaseproducts')->where('id',$product->product)->value('unit');
  $price1 = DB::table('retailbaseproducts')->where('id',$product->product)->value('sellingprice');
  
  $inputid = $product->id."input";
  $timeid = $product->id."time";
  $price = $product->rate*$price1;

  $stock = $product->quantity;
  $productid = $product->id;
    

?>
<td class="tcell" style="width: auto;word-wrap: break-word;">

  
<a href="#" style="color:black" class="sale-data-1"
      id1="{{$productid}}" 
      id2="{{$productname}}"                    
      id3 = "{{$unit}}"    
      id4="{{$price}}"               
      id5 = "{{$stock}}"
      id6="{{$transid}}"                  
      id7 = "{{$date}}"
      id8="{{$timeid}}"                 
      id9 =  "{{$user}}"
      id10 = "{{$userbranch}}"                      
      id11 = "{{$inputid}}"       
  >
  <span style="text-transform: uppercase;font-family:takoma;font-weight:bold;">
  {{$productname}}
  </span>
  <span style="color:gray;font-family:monospace">@convert($price)/{{$unit}}</span> <span style="color:gray;font-family:monospace">[{{$product->quantity}}]</span>
  </a>
</td>
<td style="width:20px"> 
  <form  action="#"  id="{{$product->id}}"  class="cart-forms">
  <div class="input-group" style="font-size:10px">
   
    <input  id="{{$inputid}}" class="form-control cart-input  sale-data submit-data-input" style="text-align:center;width:50%;border-radius: 5px;" name="quantity"  autocomplete="off" min="0" 
      id1="{{$productid}}" 
      id2="{{$productname}}"                    
      id3 = "{{$unit}}"    
      id4="{{$price}}"               
      id5 = "{{$stock}}"
      id6="{{$transid}}"                  
      id7 = "{{$date}}"
      id8="{{$timeid}}"                 
      id9 =  "{{$user}}"
      id10 = "{{$userbranch}}"                      
      id11 = "{{$inputid}}"           
    >
    
    <input type="hidden" id="{{$timeid}}" class="form-control carttime" style="width:50%;text-align:center;">
    
    
</div>


</form>
</td>
</tr>
@endforeach
</tbody>

</table>
</div>
<!--/products table-->


</div>
</div>
<!--/Left Column-->


<!--right column-->
<div class="col-md-7 card   border border-primary" style="margin-top:-5px;height:100%">

<div class="row">

<div class="col-md-12 bg-primary">
<div style="margin-top:5px;margin-bottom:10px"> 

<a href="#" class="btn"   style="border: 2px solid silver;font-weight:bold;color:silver">
<i class="fa fa-shopping-cart"></i> | MWK<span  id="cartTotal1" style="color:#f2f2f2;font-weight:bold;font-size:17px"></span>
<input type="hidden"  id="cartTotal2"  >
</a>

<a href="#" id="submitcartbtn" onclick="clearCartData()" style="float:right;margin-left:5px;border:2px solid silver;font-weight:bold;color:silver;width:80px" class="btn">
 <i class="fa fa-angle-right" style="color: silver;font-weight:bold"></i>  
</a>


</div>
</div>


<div class="col-md-12" style="background-color:silver;margin-top:0px">
<table class="table table-sm" id="cartTable" style="font-size:11px">
<thead  style="color: #3d5c5c">
  <tr >
    <th style="border-bottom:2px solid #a6a6a6;border-top:1px solid #a6a6a6;padding-left:0px">Item</th>
    <th style="text-align:center;border-bottom:2px solid #a6a6a6;border-top:1px solid #a6a6a6">Unit</th>
    <th style="text-align:center;border-bottom:2px solid #a6a6a6;border-top:1px solid #a6a6a6">Price</th>
    <th style="text-align:center;border-bottom:2px solid #a6a6a6;border-top:1px solid #a6a6a6">Qty</th>
    <th style="text-align:center;border-bottom:2px solid #a6a6a6;border-top:1px solid #a6a6a6">Total</th>
    <th style="text-align:center;border-bottom:2px solid #a6a6a6;border-top:1px solid #a6a6a6">Actn</th>
  </tr>
</thead>
<tbody id="cartbody">
</tbody>
</table>
</div>  








</div>
</div>
<!--/right column-->




</div>
</section>
<!-- /Main content -->
 </div>
 </div>



      
      
<section>
<!--Blank Modal-->
  <div class="modal" id="sales-modal" data-bs-backdrop="static">
        <div class="modal-dialog  ">
          <div class="modal-content">
            <div class="modal-body">
            <?php
            $branchName = DB::table('branches')->where('id',$userbranch)->value('branch');
            ?>
       
            <a href="#" style="color:black;font-weight:bold" >{{$branchName}} | {{$disaplaydate}} </a> 
            
            <button type="button" style="font-size:10px;float:right" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            <table class="table table-sm "  style="margin-top:13px">
              <thead class="" >
                <tr>
                  <th class="" style="border-bottom:2px solid #737373;border-top:2px solid #737373">Username</th>
                  <th class="text-center" style="border-bottom:2px solid #737373;border-top:2px solid #737373">Interval</th>
                  <th class="text-center" style="border-bottom:2px solid #737373;border-top:2px solid #737373">Sales</th>
                </tr>
              </thead>
              <tbody id="tbody2">
              <?php
              $grandTotal = DB::table('intervalsales')->where('branch',$userbranch)->where('date',$date)->sum('sales');
              $sales = DB::table('intervalsales')->where('branch',$userbranch)->where('date',$date)->orderBy('id','asc')->get();
              ?>
              @foreach( $sales as  $sale)
              <tr>
                <?php
                $userName = DB::table('users')->where('id',$sale->user)->value('username');
                ?>
                <td>{{$userName}}</td>
                <td style="text-align:center">{{$sale->slot}}</td>
                <td style="text-align:center">
                <a href="#" class="editsalesbtn" style="color:black"

                id1 = "{{$sale->id}}"
                id2 = "{{$sale->branch}}"
                id3 = "{{$sale->date}}"
                id4 = "{{$sale->sales}}"
                id5 = "{{$sale->user}}"
                id6 = "{{$userName}}"
                id7 = "{{$sale->slot}}"
                id8 = "{{$sale->sales}}"
                >@convert($sale->sales)</a>  
              </td>
              </tr>
              @endforeach
              <tr>
                <td  style="text-align:center;border-bottom:2px solid #737373;border-top:2px solid #737373"></td>
                <td  style="text-align:center;border-bottom:2px solid #737373;border-top:2px solid #737373;font-weight:bold">Grand total</td>
                <td style="text-align:center;border-bottom:2px solid #737373;border-top:2px solid #737373;font-weight:bold">@convert($grandTotal)</td>
              </tr>
              </tbody>
             </table>
           
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.blank modal -->
      </section>



      <section>
<!--Blank Modal-->
  <div class="modal fade card-info" id="salesinterval-modal" data-bs-backdrop="static">
        <div class="modal-dialog ">
          <div class="modal-content">

          <div class="modal-header">
            
          <span><i class="fa fa-plus-circle"></i> Interval sales</span>
       
          <button type="button" style="font-size:10px" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

          </div>


            <div class="modal-body " style="background-color:#e6e6e6k">

            <form action="insert-interval-sales" id="submitsalesform" method="post">
              @csrf

              <input type="hidden"  name="user" value="{{$user}}" >
              <input type="hidden"  name="branch" value="{{$userbranch}}" >
              <input type="hidden"  name="date" value="{{$date}}" >

             <div class="form-group">
              <label for="">Interval</label>

              <?php
                 $countIntervals = DB::table('intervalsales')->where('branch',$userbranch)->where('date',$date)->count();
            
                 if($countIntervals == 0){
                   $interval = "07:00AM-10:00AM"; 
                  }
                  if($countIntervals == 1){
                    $interval = "10:00AM-12:00PM"; 
                   }
                   if($countIntervals == 2){
                    $interval = "12:00PM-02:00PM"; 
                   }
                   if($countIntervals == 3){
                    $interval = "02:00PM-04:00PM"; 
                   }
                   if($countIntervals == 4){
                    $interval = "04:00PM-05:00PM"; 
                   }
  
                   if($countIntervals == 5){
                    $interval = "05:00PM-07:00PM"; 
                   }

                   if($countIntervals == 6){
                    $interval = "07:00PM-09:00PM"; 
                   }

                   if($countIntervals == 7){
                    $interval = "09:00PM-12:00AM"; 
                   }
                   if($countIntervals > 7){
                    $interval = "12:00AM-07:00AM"; 
                   } 
              ?>
              <input type="text" name="slot" class="form-control" placeholder="Enter Sales"  id="seleectslot" value="{{$interval}}" readonly>
            
             </div>

             <div class="form-group">
              <label for="">Sales</label>

              <input type="number" name="sales" class="form-control" placeholder="Enter Sales" id="entersales" autocomplete="off">
            
            </div>



            </form>
          </div>
          <div class="modal-footer">
          <a href="#" class="btn btn-primary" id="submitsalesbtn">Submit</a>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.blank modal -->
      </section>


      

      <section>
  <div class="modal fade card-info" id="editsales-modal"  data-bs-backdrop="static">
        <div class="modal-dialog">
          <div class="modal-content">

          <div class="modal-header">
            Edit sales
            <button type="button" style="font-size:10px" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
            <div class="modal-body " style="background-color:#e6e6e6k">
            <form action="edit-interval-sales" id="updatesalesform" method="post">
              @csrf

              <input type="hidden"  name="id" id="i1" >
              <input type="hidden"  name="branch" id="i2" >
              <input type="hidden"  name="date" id="i3" >

              <input type="hidden"  name="oldsales" id="i4" >

              <input type="hidden"  name="user" id="i5" >

              <div class="form-group">
              <label for="">User</label>
              <input type="text" class="form-control" id="i6" disabled >
             </div>


            
              <div class="form-group">
              <label for="">Interval</label>
              <input type="text" name="slot" class="form-control" id="i7" disabled>
             </div>


             <div class="form-group">
              <label for="">Sales</label>
              <input type="number" name="sales" class="form-control" id="i8" >
             </div>

            </form>
          </div>
          <div class="modal-footer">
          <a href="#" class="btn btn-primary" id="updatesalesbtn">Submit</a>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.blank modal -->
      </section>







      
<section>
<!--Blank Modal-->
  <div class="modal fade card-info" id="unuploadeddata-modal" data-bs-backdrop="static">
        <div class="modal-dialog  ">
          <div class="modal-content">

          <div class="modal-header">
            <span>CLOUD DATA  MWK <span id="cloudamount">></span></span>
          <button type="button" style="font-size:10px" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
            <div class="modal-body " style="background-color:#e6e6e6k">
      
            <div class="tableFixHead">
            <table class="table table-sm "  style="margin-top:-5px"  id="cloudTable">
              <thead class="" style="background-color:silver" >
                <tr>
                  <th class=" " style="background-color:silver">Transid</th>
                  <th class=" text-center" style="background-color:silver">Product</th>
                  <th class=" text-center" style="background-color:silver">Unit</th>
                  <th class=" text-center" style="background-color:silver">Price</th>
                  <th class=" text-center" style="background-color:silver">Qty</th>
                  <th class=" text-center" style="background-color:silver">Total</th>
                </tr>
              </thead>
              <tbody id="cloudbody">




              </tbody>
             </table>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.blank modal -->
      </section>




      
      
<section>
<!--Blank Modal-->
  <div class="modal fade card-info" id="recentsales-modal" data-bs-backdrop="static">
        <div class="modal-dialog  ">
          <div class="modal-content">

          <div class="modal-header">
            Recently sold itemsm [ Trans {{$customersToday}} ]
            <button type="button" style="font-size:10px" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>


            <div class="modal-body " style="">
              
            <div class="tableFixHead">
            <table class="table table-sm "  style="margin-top:-5px"  id="cloudTable">
              <thead class=""  style="background-color:silver" >
                <tr>
                  <th class=" " style="background-color:silver">Product</th>
                  <th class=" text-center" style="background-color:silver">Unit</th>
                  <th class=" text-center" style="background-color:silver">Price</th>
                  <th class=" text-center" style="background-color:silver">Qty</th>
                  <th class=" text-center" style="background-color:silver">Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $rsales = DB::table('retailsales')->where('branch',$userbranch)->where('date',$date)->orderBy('id','desc')->limit(30)->get();
                ?>
                @foreach($rsales as $r)
                 <tr>
                  <td>{{$r->product}}</td>
                  <td style="text-align:center">{{$r->unit}}</td>
                  <td style="text-align:center">@convert($r->price)</td>
                  <td style="text-align:center">@convert2($r->quantity)</td>
                  <td style="text-align:center">@convert($r->quantity*$r->price)</td>
                 </tr>
                 @endforeach
              </tbody>
             </table>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.blank modal -->
      </section>


<section>
<div class="modal" tabindex="-1" role="dialog" id="calculator-modal" >
  <div class="modal-dialog" role="document" >
    <div class="modal-content">

    <div class="modal-header">
  
        <a href="#" style="font-weight:bold">CALCULATOR</a>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      
    </div>
    
      <div class="modal-body calculator " style="background-color: 	 #e6e6e6">

      
        
<div class="top bg-primary">
<!--span class="unit">deg</span> -->
<section class="screen">
  <div class="input" style="color:#e6e6e6;font-size:40px"></div>
  <div class="result" style="color:#e6e6e6;font-size:30px"></div>
</section>

</div>

<div class="bottom">

<section class="keys">

  <div class="column">
    <span data-key="7">7</span>
    <span data-key="4">4</span>
    <span data-key="1">1</span>
    <span data-key=".">.</span>
  </div>

  <div class="column">
    <span data-key="8">8</span>
    <span data-key="5">5</span>
    <span data-key="2">2</span>
    <span data-key="0">0</span>
  </div>

  <div class="column">
    <span data-key="9">9</span>
    <span data-key="6">6</span>
    <span data-key="3">3</span>
    <span class="equals-to" data-key="=">=</span>
  </div>
</section>

<section class="operators">
  <span class="delete">del</span>
  <span data-key="÷">÷</span>
  <span data-key="x">x</span>
  <span data-key="-">-</span>
  <span data-key="+">+</span>
</section>

</div>




  
      </div>
     
    </div>
  </div>
</div>
</section>
























      
 






<!-- jQuery -->
<script src="Admin320/plugins/jquery/jquery.min.js"></script>
<script src="Admin320/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="Admin320/plugins/toastr/toastr.min.js"></script>


<script> 


function currentTime3() {
  let date = new Date(); 
  let hh = date.getHours();
  let mm = date.getMinutes();
  let ss = date.getSeconds();
  let session = "AM";

  if(hh == 0){
      hh = 12;
  }
  if(hh > 12){
      hh = hh - 12;
      session = "PM";
   }

   hh = (hh < 10) ? "0" + hh : hh;
   mm = (mm < 10) ? "0" + mm : mm;
   ss = (ss < 10) ? "0" + ss : ss;
    
   let time = hh + ":" + mm + ":" + ss + " " + session;

   var x = document.getElementsByClassName('carttime');

    for(i = 0; i < x.length; i++) {
      x[i].value = time;
    }


  let t = setTimeout(function(){ currentTime3() }, 1000);
}
currentTime3();


</script>



<script>
  var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 12000
    });
    


var  cartArray = [];
var receiptArray = [];
 
 


</script>


<script>

$('body').on('change', '.sale-data', function () {

var checkproduct = 0;



var timeid = $(this).attr('id8');
var inputid = $(this).attr('id11');
var productid = $(this).attr('id1');
var product   = $(this).attr('id2');
var unit   = $(this).attr('id3');
var price   = $(this).attr('id4');
var stock   = $(this).attr('id5');
var transid= document.getElementById("transidInput").value;
var date = $(this).attr('id7');
var time = document.getElementById(timeid).value;
var user = $(this).attr('id9');
var branch = $(this).attr('id10');
var quantity = document.getElementById(inputid).value;
var total = quantity*price;



var cartRow = {
  Productid:productid,
  Product:product,
  Unit:unit,
  Price:price,
  Stock:stock,
  Transid:transid, 
  Date:date,
  Time:time,
  User:user,
  Branch:branch,
  Quantity:quantity,
  Total:total,
}  



if((Number(quantity)>0) && (Number(quantity)<=Number(stock))){


if(localStorage.cartData){
      checkingData =JSON.parse(localStorage.cartData);
      for(var i=0;i<checkingData.length;i++){ 
      var checkp = checkingData[i].Product; 
      if(checkp==product){
          checkproduct = 1 + checkproduct; 
      }
  }

//<checkproduct>
if(checkproduct>0){

 toastr.error('An error occurred ' +product+ ' is already added to cart table' );
 document.getElementById(inputid).value=" ";

}
else{
    cartArray.push(cartRow);
    localStorage.cartData=JSON.stringify(cartArray)
    var btn = '<a href="#" style="color:red" onclick="removeCartItem('+productid+')">X</a>';
    var dtotal = numberToCurrency (total);
    var dprice = numberToCurrency (price);
    var qtyinput = '<input type="updatecart" style="color:blue;text-align:center;background:transparent;border:none;width:20px"  class="update-cart"  id1="'+productid+'"  id3="'+quantity+'"   id2="'+stock+'"   value="'+quantity+'">'
    prepareTableCell(product,unit,dprice,qtyinput,dtotal,btn,productid);
    document.getElementById(inputid).value=" ";
    $('#amountPaid').val("") 
    $('#change').val("") 
    cartTable =JSON.parse(localStorage.cartData);
    cartTotal=0; 
    for(var i=0;i<cartTable.length;i++){
        var price = cartTable[i].Price;
        var quantity = cartTable[i].Quantity;

        var cartTotal = price*quantity + cartTotal;

        var cartDisplayNum = numberToCurrency (cartTotal);

        document.getElementById("cartTotal1").innerHTML = cartDisplayNum;
        document.getElementById("cartTotal2").value = cartTotal;

      }  
      $('#mobile-table').hide();
      $('#mobile-search').val('');
      document.getElementById('mobile-search').focus();
}
//</checkproduct>


}
//<product exits>
else{
  cartArray.push(cartRow);
  localStorage.cartData=JSON.stringify(cartArray)
  var btn = '<a href="#" style="color:red" onclick="removeCartItem('+productid+')">X</a>';
  var dtotal = numberToCurrency (total);
  var dprice = numberToCurrency (price);
 var qtyinput = '<input type="updatecart" style="color:blue;text-align:center;background:transparent;border:none;width:20px"  class="update-cart" id3="'+quantity+'" id1="'+productid+'"    id2="'+stock+'"   value="'+quantity+'">'
 prepareTableCell(product,unit,dprice,qtyinput,dtotal,btn,productid);
 document.getElementById(inputid).value="";
  $('#amountPaid').val("") 
  $('#change').val("") 
  cartTable =JSON.parse(localStorage.cartData);
  cartTotal=0; 
  for(var i=0;i<cartTable.length;i++){
      var price = cartTable[i].Price;
      var quantity = cartTable[i].Quantity;

      var cartTotal = price*quantity + cartTotal;

      var cartDisplayNum = numberToCurrency (cartTotal);

      document.getElementById("cartTotal1").innerHTML = cartDisplayNum;
      document.getElementById("cartTotal2").value = cartTotal;
    }  
     $('#mobile-table').hide();
     $('#mobile-search').val('');
     document.getElementById('mobile-search').focus();
}
//</product exits>
}


else{
  toastr.error('An error occurred quantity for  ' +product+ ' Must be greater than 0 and must be less than or equal to '+ stock );
  document.getElementById(inputid).value=" ";
}

})
</script>




<script>

$('body').on('click', '.sale-data-1', function () {
var checkproduct = 0;
var timeid = $(this).attr('id8');
var inputid = $(this).attr('id11');
var productid = $(this).attr('id1');
var product   = $(this).attr('id2');
var unit   = $(this).attr('id3');
var price   = $(this).attr('id4');
var stock   = $(this).attr('id5');
var transid= document.getElementById("transidInput").value;
var date = $(this).attr('id7');
var time = document.getElementById(timeid).value;
var user = $(this).attr('id9');
var branch = $(this).attr('id10');
var quantity = 1;
var total = quantity*price;


var cartRow = {
  Productid:productid,
  Product:product,
  Unit:unit,
  Price:price,
  Stock:stock,
  Transid:transid, 
  Date:date,
  Time:time,
  User:user,
  Branch:branch,
  Quantity:quantity,
  Total:total,
}  



if((Number(quantity)>0) && (Number(quantity)<=Number(stock))){


if(localStorage.cartData){
      checkingData =JSON.parse(localStorage.cartData);
      for(var i=0;i<checkingData.length;i++){ 
      var checkp = checkingData[i].Product; 
      if(checkp==product){
          checkproduct = 1 + checkproduct; 
      }
  }

//<checkproduct>
if(checkproduct>0){

 toastr.error('An error occurred ' +product+ ' is already added to cart table' );
 document.getElementById(inputid).value=" ";

}
else{
    cartArray.push(cartRow);
    localStorage.cartData=JSON.stringify(cartArray)
    var btn = '<a href="#" style="color:red" onclick="removeCartItem('+productid+')">X</a>';
    var dtotal = numberToCurrency (total);
    var dprice = numberToCurrency (price);
    var qtyinput = '<input type="updatecart" style="color:blue;text-align:center;background:transparent;border:none;width:40px"  class="update-cart"  id1="'+productid+'"  id3="'+quantity+'"   id2="'+stock+'"   value="'+quantity+'">'
    prepareTableCell(product,unit,dprice,qtyinput,dtotal,btn,productid);
    document.getElementById(inputid).value=" ";
    $('#amountPaid').val("") 
    $('#change').val("") 
    cartTable =JSON.parse(localStorage.cartData);
    cartTotal=0; 
    for(var i=0;i<cartTable.length;i++){
        var price = cartTable[i].Price;
        var quantity = cartTable[i].Quantity;

        var cartTotal = price*quantity + cartTotal;

        var cartDisplayNum = numberToCurrency (cartTotal);

        document.getElementById("cartTotal1").innerHTML = cartDisplayNum;
        document.getElementById("cartTotal2").value = cartTotal;

      }  
      $('#mobile-table').hide();
      $('#mobile-search').val('');
      document.getElementById('mobile-search').focus();
}
//</checkproduct>


}
//<product exits>
else{
  cartArray.push(cartRow);
  localStorage.cartData=JSON.stringify(cartArray)
  var btn = '<a href="#" style="color:red" onclick="removeCartItem('+productid+')">X</a>';
  var dtotal = numberToCurrency (total);
  var dprice = numberToCurrency (price);
 var qtyinput = '<input type="updatecart" style="color:blue;text-align:center;background:transparent;border:none;width:40px"  class="update-cart" id3="'+quantity+'" id1="'+productid+'"    id2="'+stock+'"   value="'+quantity+'">'
 prepareTableCell(product,unit,dprice,qtyinput,dtotal,btn,productid);
 document.getElementById(inputid).value=" ";
  $('#amountPaid').val("") 
  $('#change').val("") 
  cartTable =JSON.parse(localStorage.cartData);
  cartTotal=0; 
  for(var i=0;i<cartTable.length;i++){
      var price = cartTable[i].Price;
      var quantity = cartTable[i].Quantity;

      var cartTotal = price*quantity + cartTotal;

      var cartDisplayNum = numberToCurrency (cartTotal);

      document.getElementById("cartTotal1").innerHTML = cartDisplayNum;
      document.getElementById("cartTotal2").value = cartTotal;
    }  
     $('#mobile-table').hide();
     $('#mobile-search').val('');
    document.getElementById('mobile-search').focus();
}
//</product exits>
}


else{
  toastr.error('An error occurred quantity for  ' +product+ ' Must be greater than 0 and must be less than or equal to '+ stock );
  document.getElementById(inputid).value=" ";
}

})
</script>














<script>
$(document).ready( function () {
 var table = $("#mobile-table").DataTable({
          "paging": false,
          "bInfo" : false,
          "ordering":false,
  dom: 'lrtip'
  })
  $('#mobile-table').hide();
  $('#mobile-search').keyup( function() {
    var value = document.getElementById('mobile-search').value;
    if (value.length<2) {
      $('#mobile-table').hide();
    }else{
      $('#mobile-table').show();
     table.search($(this).val()).draw();
    }
  } );
} );


$(document).ready(function () {
    $('#mobile-search').click(function (){
      $('#mobile-search').val('');
      $('#mobile-table').hide();
    });
})

var numberToCurrency = function (input_val, fixed = false, blur = false) {
    if(!input_val) {
        return "";
    }
    
    if(blur) {
        if (input_val === "" || input_val == 0) { return 0; }
    }

    if(input_val.length == 1) {
        return parseInt(input_val);
    }

    input_val = ''+input_val;
    
    let negative = '';
    if(input_val.substr(0, 1) == '-'){
        negative = '-';
    }
    if (input_val.indexOf(".") >= 0) {
        var decimal_pos = input_val.indexOf(".");
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);
        left_side = left_side.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        if(fixed && right_side.length > 3) {
            right_side = parseFloat(0+right_side).toFixed(2);
            right_side =  right_side.substr(1, right_side.length);
        }
        right_side = right_side.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        if(right_side.length > 2) {
            right_side = right_side.substring(0, 2);
        }
    
        if(blur && parseInt(right_side) == 0) {
            right_side = '';
        }

        if(blur && right_side.length < 1) {
            input_val = left_side;
        } else {
            input_val = left_side + "." + right_side;
        }
    } else {
        input_val = input_val.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    if(input_val.length > 1 && input_val.substr(0, 1) == '0' && input_val.substr(0, 2) != '0.' ) {
        input_val = input_val.substr(1, input_val.length);
    } else if(input_val.substr(0, 2) == '0,') {
        input_val = input_val.substr(2, input_val.length);
    }
    
    return negative+input_val;
};



function prepareTableCell(product,unit,price,quantity,total,btn,rownum){
var table = document.getElementById('cartTable').getElementsByTagName('tbody')[0];

var row  = table.insertRow(0);
cell1 = row.insertCell(0)
cell2 = row.insertCell(1)
cell3 = row.insertCell(2)
cell4 = row.insertCell(3)
cell5 = row.insertCell(4)
cell6 = row.insertCell(5)

cell1.innerHTML = product;
cell2.innerHTML = unit;
cell3.innerHTML = price;
cell4.innerHTML = quantity;
cell5.innerHTML = total;
cell6.innerHTML = btn;
row.id= "r"+rownum;
 
cell2.style.textAlign = "center";
cell3.style.textAlign = "center";
cell4.style.textAlign = "center";
cell5.style.textAlign = "center";
cell6.style.textAlign = "center";


for (var i = 0; i < 6; i++) {
    var cell = row.cells[i];
    cell.style.borderBottom = "1px solid  #b3b3b3";
}

}

function displayCartData(){
if(localStorage.cartData){

  cartArray =JSON.parse(localStorage.cartData);

  var cartTotal = 0;

  for(var i=0;i<cartArray.length;i++){

    var product = cartArray[i].Product;
    var unit = cartArray[i].Unit;
    var price = cartArray[i].Price;
    var total = cartArray[i].Total;
    var quantity = cartArray[i].Quantity;
    var productid = cartArray[i].Productid;
    var stock = cartArray[i].Stock;
    var qtyinput = '<input type="updatecart" style="color:blue;text-align:center;background:transparent;border:none;width:40px"  class="update-cart" id3="'+quantity+'"    id1="'+productid+'"    id2="'+stock+'"   value="'+quantity+'">'
    var btn = '<a href="#" style="color:red" onclick="removeCartItem('+productid+')">X</a>';

    var cartTotal = price*quantity + cartTotal;

      var cartDisplayNum = numberToCurrency (cartTotal)
      document.getElementById("cartTotal1").innerHTML = cartDisplayNum;
      document.getElementById("cartTotal2").value = cartTotal;
     var dtotal = numberToCurrency(total)
     var dprice = numberToCurrency(price)
     if(productid>0){
     prepareTableCell(product,unit,dprice,qtyinput,dtotal,btn,productid);
    }

  }

}

}


function removeCartItem(id){
  var rowid = "r"+id;
  var row = document.getElementById(rowid);
  var rowindex   =  row.rowIndex;
  var  table = document.getElementById("cartTable");
  table.deleteRow(rowindex);
  var productid =id;
  var cartTotal = 0;

  var newData = [];
  var oldData = JSON.parse(localStorage.cartData)


for (var i = 0; i <oldData.length; i++) {
if(id != oldData[i].Productid){ 
  newData.push(oldData[i]);
 }}


localStorage.cartData =  JSON.stringify(newData);

for (var i = 0; i < cartArray.length; i++) {
if(productid == cartArray[i].Productid ){ 

   cartArray[i].Productid=0
   cartArray[i].Product=0
   cartArray[i].Price=0
   cartArray[i].Quantity=0
   cartArray[i].Total=0

 }
}

 

cartTable =JSON.parse(localStorage.cartData)


if(cartTable.length>0){

for(var i=0;i<cartTable.length;i++){

var price = cartTable[i].Price;
var quantity = cartTable[i].Quantity;

cartTotal = price*quantity + cartTotal;
      
var cartDisplayNum = numberToCurrency (cartTotal);
document.getElementById("cartTotal1").innerHTML = cartDisplayNum;
document.getElementById("cartTotal2").value = cartTotal;
$('#amountPaid').val("") ;
$('#change').val("") ;
document.getElementById('mobile-search').focus();

}  

}
else{

  document.getElementById("cartTotal1").innerHTML = 0;
  document.getElementById("cartTotal2").value = 0;
  $('#amountPaid').val("") ;
  $('#change').val("");
  document.getElementById('mobile-search').focus();;
   }
}



function removeCommas(x){
return x.split(',').join('');
}
$("#amountPaid").on({
keyup: function() {
     var  amountPaid  = $(this).val();
     $(this).val(numberToCurrency(amountPaid));
     var cartTotal   = Number($('#cartTotal2').val());  
     var change = removeCommas(amountPaid)-cartTotal;
if(amountPaid == ""){

$('#change').val(" ");

 }       
else{
  if(change!=""){
    $('#change').val(numberToCurrency(change));
  }
  else{
    $('#change').val(" ");
  }
  }
},
blur: function() { }
});




$('body').on('change', '.update-cart', function () {
     var productid = $(this).attr('id1');
     var quantity  = $(this).attr('id2');
     var reversequantity  = $(this).attr('id3');
     var newqty = $(this).val();
     var rowId ="r"+productid;
     var  row  = document.getElementById(rowId);
     var cartTotal=0;
     var col1 = "cx"+productid;
     var col2 = "cy"+productid;
    var cartData = JSON.parse(localStorage.cartData);

     for (var i = 0; i < cartData.length; i++) {
       if(productid ==cartData[i].Productid){  
          if((Number(newqty)>0) && (Number(newqty)<=Number(quantity))){
            for (var y = 0; y < cartArray.length; y++) {
              if(productid == cartArray[y].Productid){
                 cartArray[y].Quantity=newqty;
                  cartArray[y].Total=newqty* cartArray[y].Price;
                }
            }
           cartData[i].Quantity=newqty;
           cartData[i].Total=newqty* cartData[i].Price;
           row.deleteCell(4);
           cell5 = row.insertCell(4)
           cell5.innerHTML =  numberToCurrency(newqty* cartData[i].Price);
           cell5.style.textAlign = "center";
           cell5.style.borderBottom = "1px solid  #b3b3b3";

            localStorage.cartData=JSON.stringify(cartData);
            $(this).attr('id3',newqty);
            $('#amountPaid').val("") ;
             $('#change').val("");
             document.getElementById('mobile-search').focus();
          
          
          
            }
          else{
             toastr.error('An error occurred quantity for '+cartData[i].Product+' must be greater than 0 and must be less than  or equal to '+quantity +' ');
             $(this).val(reversequantity );
            }
       }

     }     
       cartTable =JSON.parse(localStorage.cartData);
       for(var i=0;i<cartTable.length;i++){
           var price = cartTable[i].Price;
           var quantity = cartTable[i].Quantity;
           cartTotal = price*quantity + cartTotal; 
           var cartDisplayNum = numberToCurrency (cartTotal);
           document.getElementById("cartTotal1").innerHTML = cartDisplayNum;
          document.getElementById("cartTotal2").value = cartTotal;
         }  

   })

   function clearCartData(){


    var newclouddata  =  [];
    var  cloudArray =  [];
  


    if(localStorage.cloudData){

      cloudArray = JSON.parse(localStorage.cloudData);

     }

    if(localStorage.cartData){

     newclouddata = JSON.parse(localStorage.cartData);

    }

    for (var i = 0; i <newclouddata.length; i++) {

      if(newclouddata[i].Productid != 0)

      {
        cloudArray.push(newclouddata[i]);
        
        receiptArray.push(newclouddata[i]);

      }
     
    }

   
    
    localStorage.cloudData=JSON.stringify(cloudArray)
    localStorage.receiptData=JSON.stringify(receiptArray)

    document.getElementById("cloudItems").innerHTML = JSON.parse(localStorage.cloudData).length;


    window.localStorage.removeItem('cartData');
    
    cartArray.length=0;

    $("#cartTable").find("tr:gt(0)").remove();
    document.getElementById("cartTotal1").innerHTML = 0;
    document.getElementById("cartTotal2").value = 0;

    receiptItems  = JSON.parse(localStorage.receiptData)
    window.localStorage.removeItem('receiptData');
    receiptArray.length=0;
    document.getElementById('transidForm').reset();
    
    $("#transDiv").load(" #transDiv  > *",function(){});
   
    
    document.getElementById('mobile-search').focus();

  }

























function countCloudData(){

  
  if(localStorage.cloudData){

    document.getElementById("cloudItems").innerHTML = JSON.parse(localStorage.cloudData).length;

  }

 

}

function onLoadFunctions(){
  displayCartData();
  countCloudData();
  document.getElementById('mobile-search').focus();
}


async function uploadData(){
const url = '/';
var data1  = [];
document.getElementById("uploaddatabtn").disabled = true;
$('#loading-status').css('display', 'block');

if(localStorage.cloudData){
  data1 = JSON.parse(localStorage.cloudData);
}
data = JSON.stringify(data1);
var mergedArray = [];
await fetch(url,{mode:"no-cors"}).then(response => {
        $.ajax({
          headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
            type:"POST",
            url: '/upload-sales',
            data: {data:data},
            
            success:function(rdata) {
            if(rdata.length>0){
              rdata.forEach( row => {
                for(var i = 0; i < row.length; i++) {
                    mergedArray.push(row[i]);
                  }
               });
            window.localStorage.removeItem('cloudData');
            localStorage.cloudData=JSON.stringify(mergedArray);
            document.getElementById("cloudItems").innerHTML = JSON.parse(localStorage.cloudData).length;
            document.getElementById("uploaddatabtn").disabled = false;
            $('#loading-status').css('display', 'none');
          }else{
              window.localStorage.removeItem('cloudData');
              document.getElementById("cloudItems").innerHTML = "";
              document.getElementById('mobile-search').focus();
              toastr.success('All data uploaded successifully');
              document.getElementById("uploaddatabtn").disabled = false;
              $('#loading-status').css('display', 'none');

            }
            },

          error:function(jqXHR, textStatus, errorThrown) {
          if(textStatus === 'timeout')
            {   
                toastr.error('It is taking longer to submit the data check your internet connection and try again');
                document.getElementById("uploaddatabtn").disabled = false;
                $('#loading-status').css('display', 'none');
              
                form.reset();
            }
            else{
              toastr.error('No data available for uploading'); 
              document.getElementById("uploaddatabtn").disabled = false;
              $('#loading-status').css('display', 'none');
              form.reset();
            }
        },
       timeout: 180000 //3 minutes
          
        });

  }).catch(err => {
    toastr.error('An error occured request was not sent to the server, make sure you are connected to the internet');
    document.getElementById("uploaddatabtn").disabled = false;
    document.getElementById("cloudItems").innerHTML = JSON.parse(localStorage.cloudData).length;
    $('#loading-status').css('display', 'none');
    
  });
};





function prepareCloudTable(transid,product,unit,price,quantity,total){

var table = document.getElementById('cloudTable').getElementsByTagName('tbody')[0];

var row  = table.insertRow();
cell1 = row.insertCell(0)
cell2 = row.insertCell(1)
cell3 = row.insertCell(2)
cell4 = row.insertCell(3)
cell5 = row.insertCell(4)
cell6 = row.insertCell(5)


cell1.innerHTML = transid;
cell2.innerHTML = product;
cell3.innerHTML = unit;
cell4.innerHTML = price;
cell5.innerHTML = quantity;
cell6.innerHTML = total;


 
cell2.style.textAlign = "center";
cell3.style.textAlign = "center";
cell4.style.textAlign = "center";
cell5.style.textAlign = "center";
cell6.style.textAlign = "center";


}


function displayCloudTotal(){
  document.getElementById("cloudamount").innerHTML = "0";
  cdTotal =0;
  if(localStorage.cloudData){
   cdArray  =JSON.parse(localStorage.cloudData);
    for(var i=0;i<cdArray.length;i++){
        var price =  cdArray[i].Price;
        var quantity =   cdArray[i].Quantity;
        cdTotal = price*quantity + cdTotal; 
        var cartDisplayNum = numberToCurrency (cdTotal);
        document.getElementById("cloudamount").innerHTML = cartDisplayNum;
      }  
 }

}



function displayCloudData(){
$("#cloudbody").empty();
if(localStorage.cloudData){
const cloudArray1 =JSON.parse(localStorage.cloudData);
const cloudArray =[];

for(let i = cloudArray1.length - 1; i >= 0; i--) {
  const valueAtIndex = cloudArray1[i]
  cloudArray.push(valueAtIndex)
}
  for(var i=0;i<cloudArray.length;i++){
    var transid = cloudArray[i].Transid;
    var product = cloudArray[i].Product;
    var unit = cloudArray[i].Unit;
    var price = cloudArray[i].Price;
    var total = cloudArray[i].Total;
    var quantity = cloudArray[i].Quantity;
    var productid = cloudArray[i].Productid;
    var stock = cloudArray[i].Stock;
     var dtotal = numberToCurrency(total)
     var dprice = numberToCurrency(price)

     if(productid>0){
     prepareCloudTable(transid,product,unit,dprice,quantity,dtotal);
    }
  }

}

}



  $('body').on('click', '#actions', function () { $("#actions-modal").modal("show"); });

  

$('body').on('click', '.editsalesbtn', function () {
      $('#i1').val($(this).attr('id1'));
      $('#i2').val($(this).attr('id2'));
      $('#i3').val($(this).attr('id3'));
      $('#i4').val($(this).attr('id4'));
      $('#i5').val($(this).attr('id5'));
      $('#i6').val($(this).attr('id6'));
      $('#i7').val($(this).attr('id7'));
      $('#i8').val($(this).attr('id8'));

      $('#editsales-modal').modal('show');
   });
   

   $('body').on('click', '#calculatorbtn', function () { $("#calculator-modal").modal("show"); });
 
  

  $('body').on('click', '#salesbtn', function () { $("#sales-modal").modal("show"); });

  $('body').on('click', '#closesalesinterval', function () { $("#sales-modal").modal("hide"); });
  $('body').on('click', '#addsalesinterval', function () { $("#salesinterval-modal").modal("show"); });

  $('body').on('click', '#closesalesinterval2', function () { $("#salesinterval-modal").modal("hide"); });
  $('body').on('click', '#closesalesinterval3', function () { $("#editsales-modal").modal("hide"); });

  $('body').on('click', '#recentsalesbtn', function () { $("#recentsales-modal").modal("show"); })

 
  $('body').on('click', '#unuploadeddatabtn', function () {

      displayCloudData();
      displayCloudTotal();

     $("#unuploadeddata-modal").modal("show");
    
    });


    
$(document).on("click", "#submitsalesbtn", function(e) {
e.preventDefault(); 
$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });
       
var form = document.getElementById('submitsalesform');
var sales = document.getElementById('entersales').value;


if(sales != '' ){

  var cloud =0;
  if(localStorage.cloudData){
    cloud = JSON.parse(localStorage.cloudData).length
  }

if(cloud<1){
$.ajax({
    type:"post",
    url: '/insert-interval-sales',
    data: $(form).serialize(),

    
    success:function(data) {

      if(data==1){
       
      toastr.error('Data no subimited make sure that interval is unique');
        
      }

    
      if(data==3){
       
       toastr.error('Data no subimited sales should be greater than or equal to O');
         
       }
     

      if(data==2){

        toastr.success('Data submited successifully');

        $("#tbody2").load(" #tbody2  > *",function(){});

        
        $("#submitsalesform").load(" #submitsalesform  > *",function(){});
        document.getElementById('mobile-search').focus();

        $("#salesinterval-modal").modal("hide");

        form.reset();
      
         
       }
        
       
     

    },
    error:function() {
 
     
       toastr.error('An error occured make sure you are connected to the internet and you enter sales');
         
  
    }
})}

else{

  toastr.error('You should upload cloud data before entering interval sales');
}


}
else{
  toastr.error('Sales must be greater or equal to 0');

}

});





    
$(document).on("click", "#updatesalesbtn", function(e) {
e.preventDefault(); 
$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });
       
var form2 = document.getElementById('updatesalesform');
var sales2 = document.getElementById('i8').value;



if(sales2 >=0 ){
$.ajax({
    type:"post",
    url: '/edit-interval-sales',
    data: $(form2).serialize(),
    
    success:function(data) {

      if(data==1){
       
      toastr.error('An error occured no data change detected');
        
      }
     
    
      if(data==2){

        toastr.success('Data updateted successifully');

        $("#tbody2").load(" #tbody2  > *",function(){});

        $("#editsales-modal").modal("hide");
        document.getElementById('mobile-search').focus();

        form2.reset();
      
         
       }
        
       
     

    },
    error:function() {
 
     
       toastr.error('An error occured make sure you are connected to the internet');
         
  
    }
})}
else{
  toastr.error('Data not submited sales can not be less than 0');

}

});




</script>

<script>
  "use strict";

const input = document.querySelector(".input");
const result = document.querySelector(".result");
const deleteBtn = document.querySelector(".delete");
const keys = document.querySelectorAll(".bottom span");

let operation = "";
let answer;
let decimalAdded = false;

const operators = ["+", "-", "x", "÷"];

function handleKeyPress (e) {
  const key = e.target.dataset.key;
  const lastChar = operation[operation.length - 1];

  if (key === "=") {
    return;
  }

  if (key === "." && decimalAdded) {
    return;
  }

  if (operators.indexOf(key) !== -1) {
    decimalAdded = false;
  }

  if (operation.length === 0 && key === "-") {
    operation += key;
    input.innerHTML = operation;
    return;
  }

  if (operation.length === 0 && operators.indexOf(key) !== -1) {
    input.innerHTML = operation;
    return;
  }

  if (operators.indexOf(lastChar) !== -1 && operators.indexOf(key) !== -1) {
    operation = operation.replace(/.$/, key);
    input.innerHTML = operation;
    return;
  }

  if (key) {
    if (key === ".") decimalAdded = true;
    operation += key;
    input.innerHTML = operation;
    return;
  }

}

function evaluate(e) {
  const key = e.target.dataset.key;
  const lastChar = operation[operation.length - 1];

  if (key === "=" && operators.indexOf(lastChar) !== -1) {
    operation = operation.slice(0, -1);
  }

  if (operation.length === 0) {
    answer = "";
    result.innerHTML = answer;
    return;
  }

  try {

    if (operation[0] === "0" && operation[1] !== "." && operation.length > 1) {
      operation = operation.slice(1);
    }

    const final = operation.replace(/x/g, "*").replace(/÷/g, "/");
    answer = +(eval(final)).toFixed(5);

    if (key === "=") {
      decimalAdded = false;
      operation = `${answer}`;
      answer = "";
      input.innerHTML = operation;
      result.innerHTML = answer;
      return;
    }

    result.innerHTML = answer;

  } catch (e) {
    if (key === "=") {
      decimalAdded = false;
      input.innerHTML = `<span class="error">${operation}</span>`;
      result.innerHTML = `<span class="error">Bad Expression</span>`;
    }
    console.log(e);
  }

}

function clearInput (e) {

  if (e.ctrlKey) {
    operation = "";
    answer = "";
    input.innerHTML = operation;
    result.innerHTML = answer;
    return;
  }

  operation = operation.slice(0, -1);
  input.innerHTML = operation;

}

deleteBtn.addEventListener("click", clearInput);
keys.forEach(key => {
  key.addEventListener("click", handleKeyPress);
  key.addEventListener("click", evaluate);
});

</script>



</script>
<!--js toastr notification-->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
<script>
  @if(Session::has('message'))
    var type = "{{ Session::get('alert-type', 'info') }}";
    switch(type){
        case 'info':
            toastr.info("{{ Session::get('message') }}");
            break;

        case 'warning':
            toastr.warning("{{ Session::get('message') }}");
            break;

        case 'success':
            toastr.success("{{ Session::get('message') }}");
            break;

        case 'error':
            toastr.error("{{ Session::get('message') }}");
            break;
    }
  @endif
</script>
<!--js toastr notification--> 


</body>
</html>
@endsection
