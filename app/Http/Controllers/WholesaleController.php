<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use DB;
use Auth;

class WholesaleController extends Controller
{
    public function adminwholesalebaseproducts(){
        return view('wholesale.admin.wholesalebaseproducts');
    }

    public function adminwholesalebranchproducts(){
      return view('wholesale.admin.wholesalebranchproducts');
  }

  public function  adminwholesaleproducttracker(){
    return view('wholesale.admin.wholesaleproducttracker');
}


public function  adminwholesaleproductsupplies(){
  return view('wholesale.admin.wholesaleproductsupplies');
}


public function  adminwholesaleclients(){
  return view('wholesale.admin.wholesaleclients');
}


  public function  insertwholesalebaseproduct(request $request){
    $data = array();
    $data['product'] = $request->product;
    $data['supplier'] = $request->supplier;
    $data['unit'] = $request->unit;
    $data['orderprice'] = $request->orderprice;
    $data['sellingprice'] = $request->sellingprice;
    $data['vat'] = $request->vat;

    $messages = [
      'product.unique' => 'Product name must be unique (You can separate by brands).',
      'proudct.required' => 'product name is required.',
      'supplier.required' => 'Supplier is required.',
      'unit.required' => 'Unit is required.',
      'orderprice.required' => 'Order price  is required',
      'sellingprice.required' => 'Selling price  is required',
    ];
  $validator = $request->validate([
    'product' => 'required|unique:wholesalebaseproducts,product',
    'supplier' => 'required',
    'unit' => 'required',
    'orderprice' => 'required',
    'sellingprice' => 'required',
  ], $messages);
  
  if($validator){
  $insertData =  DB::table('wholesalebaseproducts')->insert($data);
  if($insertData){
   return response()->json(['success' => 'Data submitted successfully','status'=>201]);
  }else{
    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }
  }
  else{
    return back()->withErrors($validator)->withInput();
  }
  } 



  public function  editwholesalebaseproduct(request $request){
    $data = array();
    $data['product'] = $request->product;
    $data['supplier'] = $request->supplier;
    $data['unit'] = $request->unit;
    $data['orderprice'] = $request->orderprice;
    $data['sellingprice'] = $request->sellingprice;
    $data['vat'] = $request->vat;

    $messages = [
      'product.unique' => 'Product name must be unique (You can separate by brands).',
      'proudct.required' => 'product name is required.',
      'supplier.required' => 'Supplier is required.',
      'unit.required' => 'Unit is required.',
      'orderprice.required' => 'Order price  is required',
      'sellingprice.required' => 'Selling price  is required',
    ];
  $validator = $request->validate([
    'product' => 'required|unique:wholesalebaseproducts,product,'.$request->id,
    'supplier' => 'required',
    'unit' => 'required',
    'orderprice' => 'required',
    'sellingprice' => 'required',
  ], $messages);
  
  if($validator){

    $updateData =DB::table('wholesalebaseproducts')->where('id',$request->id)->update($data);

    if($updateData ){
        return response()->json(['success' => 'Data updated succesfully','status'=>201]);
    }else{
        return response()->json(['error' => 'An error occured no data change detected','status'=>422]);
    }

  }
  else{
    return back()->withErrors($validator)->withInput();
  }
  } 

  


public function deletewholesalebaseproduct(request $request){
  $id = $request->id;
  $deleteData = DB::table('wholesalebaseproducts')->where('id',$id)->delete();  
  if($deleteData){
    return response()->json(['success' => 'Data deleted succesifully','status'=>201]);
  }else{
    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }
}


public function uploadWholesaleBaseProductsCsvFile(Request $request)
{
    $csvData = json_decode($request->data, true);
    $supplier = Cookie::get('supplier') ?? "NA";
    $vat = "EX";
    $chunkSize = 50;
    $chunks = array_chunk($csvData, $chunkSize);
    $imported = 0;
    $errors = [];

    foreach ($chunks as $chunk) {
        foreach ($chunk as $row) {
            if (!empty($row)) {
                $values = array_values($row);
                if (!empty($values[0])) {
                    $orderPrice = $this->extractNumber($values[2]);
                    $sellingPrice = $this->extractNumber($values[3]);

                    if (!is_numeric($orderPrice)) {
                        $orderPrice = 0;
                    }

                    if (!is_numeric($sellingPrice)) {
                        $sellingPrice = 0;
                    }

                    $baseProduct = [
                        'product' => $values[0] ?? null,
                        'unit' => $values[1] ?: "Each",
                        'orderprice' => $orderPrice,
                        'sellingprice' => $sellingPrice,
                        'vat' => $vat,
                        'supplier' => $supplier,
                    ];

                    try {
                        $importData = DB::table('wholesalebaseproducts')->insertOrIgnore($baseProduct);
                        if ($importData) {
                            $imported++;
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Error importing record: " . $e->getMessage();
                    }
                } else {
                    // Log or handle the case where the product name is empty
                }
            }
        }
    }

    return response()->json([
        'message' => 'Processing complete',
        'success' => count($errors) == 0,
        'imported' => $imported,
        'errors' => $errors,
    ]);
}




private function extractNumber($value)
{
    $value = str_replace(',', '', $value); // Remove commas
    preg_match_all('/\d+/', $value, $matches);
    return implode('', $matches[0]) ?? 0;
}





public function insertwholesalebranchproduct(Request $request) {
  $data = array();
  $data['product'] = $request->productid;
  $data['branch'] = $request->branch;
  $data['quantity'] = $request->quantity;

  $dnote = array();
  $dnote['branchid'] = $request->branch;
  $dnote['productid'] = $request->productid;
  $dnote['date'] = Carbon::today()->toDateString();
  $dnote['quantity'] = $request->quantity;
  $dnote['productname'] = DB::table('wholesalebaseproducts')->where('id', $request->productid)->value('product');
  $dnote['unit'] = DB::table('wholesalebaseproducts')->where('id', $request->productid)->value('unit') ?? "each";
  $dnote['price'] = DB::table('wholesalebaseproducts')->where('id', $request->productid)->value('sellingprice') ?? 0;
  $dnote['added_to_branch'] = "Yes";

  $history = array();
  $time = Carbon::now()->toTimeString();
  $devicedetails = "User Agent: " . $request->header('User-Agent');
                  
        $messages = [
          'quantity.required' => 'Quantity is required.',
          'quantity.numeric' => 'Quantity must be a number.',
          'quantity.gt' => 'Quantity must be gretaer than 0.',
          'branch.gt' => 'Select a specific branch.',
      ];

      $validator = $request->validate([
          'quantity' => 'required|numeric|gt:0',
          'branch' => 'gt:0',
      ], $messages);

if($validator){
  
  $existingProduct = DB::table('wholesalebranchproducts')
      ->where('branch', $request->branch)
      ->where('product', $request->productid)
      ->first();

  if ($existingProduct) {
      $qtybefore = $existingProduct->quantity;
      $qtyafter = $qtybefore + $request->quantity;

      DB::transaction(function () use ($request, $existingProduct, $dnote, $history, $qtybefore, $qtyafter, $devicedetails, $time) {
          DB::table('wholesalebranchproducts')
              ->where('branch', $request->branch)
              ->where('product', $request->productid)
              ->update(['quantity' => $qtyafter]);
          DB::table('wholesaledeliverynotes')->insert($dnote);
          $history['date'] = Carbon::today()->toDateString();
          $history['branchid'] = $request->branch;
          $history['productid'] = $request->productid;
          $history['qtyadded'] = $request->quantity;
          $history['username'] = Auth::user()->username;
          $history['devicedetails'] = $devicedetails;
          $history['qtybefore'] = $qtybefore;
          $history['qtyafter'] = $qtyafter;
          $history['description'] = "Directly added (update)";
          $history['time'] = $time;
          DB::table('wholesaleproducthistory')->insert($history);
      });
  } else {
      DB::transaction(function () use ($data, $dnote, $history, $request, $devicedetails,$time) {
          DB::table('wholesalebranchproducts')->insert($data);
          DB::table('wholesaledeliverynotes')->insert($dnote);

          $history['date'] = Carbon::today()->toDateString();
          $history['branchid'] = $data['branch'];
          $history['productid'] = $data['product'];
          $history['qtyadded'] = $data['quantity'];
          $history['username'] = Auth::user()->username;
          $history['devicedetails'] = $devicedetails;
          $history['qtybefore'] = 0;
          $history['qtyafter'] = $data['quantity'];
          $history['description'] = "Directly added (insert)";
          $history['time'] = $time;

          DB::table('wholesaleproducthistory')->insert($history);
      });
  }

  return response()->json(['success' => 'Data submitted successfully', 'status' => 201]);

}

else{

  return back()->withErrors($validator)->withInput();

}


}

public function deletewholesalebranchproduct(Request $request)
{
    $history = array();
    $baseproductid = DB::table('wholesalebranchproducts')->where('id', $request->id)->value('product');
    $branchid = DB::table('wholesalebranchproducts')->where('id', $request->id)->value('branch');
    $qtyafter = 0;
    $qtybefore = DB::table('wholesalebranchproducts')->where('id', $request->id)->value('quantity');
    $description = "Deleted by " . Auth::user()->username;
    $date = Carbon::today()->toDateString();
    $devicedetails = "User Agent: " . $request->header('User-Agent');
    $time = Carbon::now()->toTimeString();

    $history['date'] = Carbon::today()->toDateString();
    $history['branchid'] = $branchid;
    $history['productid'] = $baseproductid;
    $history['qtyadded'] = -$qtybefore;
    $history['username'] = Auth::user()->username;
    $history['devicedetails'] = $devicedetails;
    $history['qtybefore'] = $qtybefore;
    $history['qtyafter'] = $qtyafter;
    $history['description'] = $description;
    $history['time'] = $time;
    try {
      DB::transaction(function () use ($history, $request) {
          DB::table('wholesaleproducthistory')->insert($history);
          DB::table('wholesalebranchproducts')->where('id', $request->id)->delete();
      });
      return response()->json(['success' => 'Data deleted successfully', 'status' => 201]);
  } catch (\Exception $e) {
      return response()->json(['error' => 'An error occurred, try again later', 'status' => 422]);
  }



}

public function updatewholesalebranchproduct(Request $request){
  $data = array();
  $data['quantity'] = $request->quantity;
  $data['batchnumber'] = $request->batchnumber;
  $data['expirydate'] = $request->expirydate;
  $data['status'] = $request->status;
  $data['snumber'] = $request->shelfnumber;

  $baseproductid = DB::table('wholesalebranchproducts')->where('id', $request->id)->value('product');
  $branchid = DB::table('wholesalebranchproducts')->where('id', $request->id)->value('branch');
  $qtybefore = DB::table('wholesalebranchproducts')->where('id', $request->id)->value('quantity');

  $messages = [
      'quantity.gte:0' => 'Quantity must be greater than or equal to 0',
  ];

  $validator = $request->validate([
      'quantity' => 'required|gte:0',
  ], $messages);

  if($validator){
      try {
          DB::transaction(function () use ($request, $data, $qtybefore, $baseproductid, $branchid) {
              DB::table('wholesalebranchproducts')->where('id', $request->id)->update($data);

              if($qtybefore != $request->quantity){
                  $history = array();
                  $qtyafter = $request->quantity;
                  $qtyadded = $qtyafter-$qtybefore;
                  $description = $request->description;
                  $date = Carbon::today()->toDateString();
                  $devicedetails = "User Agent: " . $request->header('User-Agent');
                  $time = Carbon::now()->toTimeString();

                  $history['date'] = Carbon::today()->toDateString();
                  $history['branchid'] = $branchid;
                  $history['productid'] = $baseproductid;
                  $history['qtyadded'] = $qtyadded;
                  $history['username'] = Auth::user()->username;
                  $history['devicedetails'] = $devicedetails;
                  $history['qtybefore'] = $qtybefore;
                  $history['qtyafter'] = $qtyafter;
                  $history['description'] = $description;
                  $history['time'] = $time;

                  DB::table('wholesaleproducthistory')->insert($history);
              }
          });

          return response()->json(['success' => 'Data updated succesfully','status'=>201]);
      } catch (\Exception $e) {
          return response()->json(['error' => 'An error occurred, try again later', 'status' => 422]);
      }
  } else{
      return back()->withErrors($validator)->withInput();
  }
}



public function insertwholesaleclient(request $request){
  $data = array();
  $data['client']=$request->client;
  $data['address']=$request->address;
  $data['contact']=$request->contact;
  $data['email']=$request->email;  
  $data['date']=Carbon::today()->toDateString();
  $messages = [
    'client.required' => 'Client name is required.',
    'client.unique' => 'Client name must be unique.',
    'email.unique' => 'Email must be unique.',
    'email.required' => 'Email  is required.',
    'email.email' => 'Email must be valid.',
    'contact.required' => 'Contact is required.',
    'address.required' => 'Address is required.',
];

$validator = $request->validate([
    'client' => 'required|unique:wholesaleclients,client',
    'email' => 'required|email|unique:wholesaleclients,email',
    'contact' => 'required|unique:wholesaleclients,contact',
    'address' => 'required',
  
], $messages);
if($validator){
  $insertData = DB::table('wholesaleclients')->insert($data);
  if($insertData){
    return response()->json(['success' => 'Data submitted succesifully','status'=>201]);
  }
  else{

    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  } 
}else{
  return  back()->withErrors($validator)->withInput();
}

}



public function deletewholesaleclient(request $request){
  $id = $request->id;
  $deleteData = DB::table('wholesaleclients')->where('id',$id)->delete();  
  if($deleteData){
    return response()->json(['success' => 'Data deleted succesifully','status'=>201]);
  }else{
    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }
}


public function updatewholesaleclient(Request $request)
{
    $data = array();
    $data['client'] = $request->client;
    $data['address'] = $request->address;
    $data['contact'] = $request->contact;
    $data['email'] = $request->email;
    $data['date'] = Carbon::today()->toDateString();

    $messages = [
        'client.required' => 'Client name is required.',
        'email.required' => 'Email is required.',
        'email.email' => 'Email must be valid.',
        'contact.required' => 'Contact is required.',
        'address.required' => 'Address is required.',
    ];

    $validator = $request->validate([
        'client' => 'required|unique:wholesaleclients,client,' . $request->id,
        'email' => 'required|email|unique:wholesaleclients,email,' . $request->id,
        'contact' => 'required|unique:wholesaleclients,contact,' . $request->id,
        'address' => 'required',
    ], $messages);

    if ($validator) {
        $updateData = DB::table('wholesaleclients')->where('id', $request->id)->update($data);
        if ($updateData) {
            return response()->json(['success' => 'Data updated succesfully', 'status' => 201]);
        } else {
            return response()->json(['error' => 'An error occured no data change detected', 'status' => 422]);
        }
    } else {
        return back()->withErrors($validator)->withInput();
    }
}







}
