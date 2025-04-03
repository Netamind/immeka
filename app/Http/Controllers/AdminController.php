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
class AdminController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isadmin');

    }

    
    public function admindashboard(){

        return view('admin.dashboard');

    }


    public function businesscategories(){
    
      return view('admin.businesscategories');

    }

    public function suppliers(){
    
      return view('admin.suppliers');

    }

    public function users(){

        return view('admin.users');

    }

    public function categories(){

        return view('admin.categories');

    }

    public function appdata(){

        return view('admin.appdata');

    }

    public function   vatstatuses(){

      return view('admin.vatstatuses');

  }



    public function  businesscategory(){

      return view('admin.businesscategory');

     }


     public function   employees(){

      return view('admin.employees');

     }


     public function    userroles(){

      return view('admin.userroles');

     }



     public function    branches(){

      return view('admin.branches');

     }
    

     public function   businesssector(){

      return view('admin.businesssectors');

     }
    
 
     public function     profile(){

      return view('admin.profile');

     }
    
 
    
  
    
   



    public function updateappdatageneral(request $request){

        $data = array();
        $response ="";

        $data['appname']=$request->appname;
        $data['applink']=$request->applink;
        $data['apptitle']=$request->apptitle;

        $checkRow = DB::table('appdata')->count();

        if($checkRow>0){

          $updateData =  DB::table('appdata')->update($data);

          if($updateData){

             $response= 2;
             return json_encode($response);
          }
          else{
            $response= 1;
            return json_encode($response);

          }


        }

        else{

          $insertData = DB::table('appdata')->insert($data);
          if($insertData ){
            $response= 4;
            return json_encode($response);
         }
         else{
           $response= 3;
           return json_encode($response);

         }

        }


    }


    public function updateappdatacontact(request $request){

        $data = array();
        $response ="";

        $data['appaddress']=$request->appaddress;
        $data['appcontact']=$request->appcontact;
        $data['appemail']=$request->appemail;

        $checkRow = DB::table('appdata')->count();

        if($checkRow>0){

          $updateData =  DB::table('appdata')->update($data);

          if($updateData){

             $response= 2;
             return json_encode($response);
          }
          else{
            $response= 1;
            return json_encode($response);

          }


        }

        else{

          $insertData = DB::table('appdata')->insert($data);
          if($insertData ){
            $response= 4;
            return json_encode($response);
         }
         else{
           $response= 3;
           return json_encode($response);

         }

        }


    }

public function  updateappdatalogo(request $request){
  $response = 0;
  $data = array();
  $countRow = DB::table('appdata')->count();
  $mimeType = $request->mimeType;
  $extension = explode('/', $mimeType)[1];
   $messages = [
    'logoimage.required' => 'Image is required',
    'logoimage.image' => 'You should upload an image for a logo',
    'logoimage.max' => 'Size of the image shold not be more than 2mb',
];
$validator = $request->validate([
    'logoimage' =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
], $messages);

   if($validator){


    $logoToDelete = DB::table('appdata')->value('applogo');

    $deletePath = public_path('/appdata/images/'. $logoToDelete);

    File::delete($deletePath );


      $logoImage = $request->file('logoimage');
      $imageName = time() .'logo'.'.'. $extension;
      $path = public_path('/appdata/images/');
      $logoImage->move($path, $imageName);
      if($countRow>0){
        $data['applogo']= $imageName;
        $success = DB::table('appdata')->update($data);
          if ($success) {
              return response()->json(['success' => 'Logo uploaded successfully','status'=>201]);
            } else {
              return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }else{

        $data['applogo']= $imageName;
        $success = DB::table('appdata')->insert($data);
          if ($success) {
            return response()->json(['success' => 'Logo submitted successfully','status'=>201]);
            } else {
              return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }
    }
  else{
    return back()->withErrors($validator)->withInput();
  }
      
} 



public function  updateappdataletterhead(request $request){
  $response = 0;
  $data = array();
  $countRow = DB::table('appdata')->count();
  $mimeType = $request->mimeType;
  $extension = explode('/', $mimeType)[1];
   $messages = [
    'letterheadimage.required' => 'Image is required',
    'letterheadimage.image' => 'You should upload an image for a letterhead',
    'letterheadimage.max' => 'Size of the image shold not be more than 2mb',
];
$validator = $request->validate([
    'letterheadimage' =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
], $messages);

   if($validator){


    $letterheadToDelete = DB::table('appdata')->value('appletterhead');

    $deletePath = public_path('/appdata/images/'. $letterheadToDelete);

    File::delete($deletePath );


      $letterheadImage = $request->file('letterheadimage');
      $imageName = time() .'letterhead'.'.'. $extension;
      $path = public_path('/appdata/images/');
      $letterheadImage->move($path, $imageName);
      if($countRow>0){
        $data['appletterhead']= $imageName;
        $success = DB::table('appdata')->update($data);
          if ($success) {
              return response()->json(['success' => 'Letterhead uploaded successfully','status'=>201]);
            } else {
              return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }else{

        $data['appletterhead']= $imageName;
        $success = DB::table('appdata')->insert($data);
          if ($success) {
            return response()->json(['success' => 'Letterhead submitted successfully','status'=>201]);
            } else {
              return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }
    }
  else{
    return back()->withErrors($validator)->withInput();
  }
      
} 


public function  updateappdataterms(request $request){
  $termsPdf= $request->file('termspdf');
  $response = 0;
  $data = array();
  $termsPdf= $request->file('termspdf');
  $countRow = DB::table('appdata')->count();
   $messages = [
    'termspdf.required' => 'Pdf file is required',
    'termspdf.mimes' => 'Only PDF files are allowed. Please upload a valid PDF file.',
    'termspdf.max' => 'Size of the pdf shold not be more than 8mb',
  ];

$validator = $request->validate([
  'termspdf' => 'required|mimes:pdf|max:16384',
], $messages);


 if($validator){
    $termsToDelete = DB::table('appdata')->value('appterms');
    $deletePath = public_path('/appdata/files/'. $termsToDelete);

      if (File::exists($deletePath)) {
        File::delete($deletePath);
       
      }

      $fileName = time() .'terms'.'.'.$termsPdf->getClientOriginalExtension();
      $path = public_path('/appdata/files/');
      $termsPdf->move($path, $fileName);

      if($countRow>0){
        $data['appterms']= $fileName;
        $success = DB::table('appdata')->update($data);
          if ($success) {
             return response()->json(['success' => 'File uploaded successfully','status'=>201]);
            } else {
            return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }else{
        $data['appterms']= $fileName;
        $success = DB::table('appdata')->insert($data);
          if ($success) {
           return response()->json(['success' => 'File uploaded successfully','status'=>201]);
            } else {
             return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }
    }
  else{
  return back()->withErrors($validator)->withInput();
  }

} 




public function  insertbusinesscartigory(request $request){
  $data = array();
  $data['business'] = $request->business;
  $data['description'] = $request->description;
  $messages = [
    'business.unique' => 'Business name must be unique.',
  ];
$validator = $request->validate([
  'business' => 'required|unique:businesses,business',
], $messages);

if($validator){
$insertData =  DB::table('businesses')->insert($data);
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

public function deletebusinesscartigory(request $request){
  $id = $request->id;
  $fileName = DB::table('businesses')->where('id',$id)->value('avator');
  $path =   $deletePath = public_path('/appdata/business/'. $fileName);
  $deleteData = DB::table('businesses')->where('id',$id)->delete();  
  if($deleteData){
    if (File::exists($path)) {
      File::delete($path);
    }
    return response()->json(['success' => 'Data deleted succesifully','status'=>201]);
  }else{
    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }
}

public function editbusinesscartigory(request $request){

  $data = array();
  $data['business'] = $request->business;
  $data['description'] = $request->description;
  $messages = [
    'business.unique' => 'Business name already taken.',
  ];
$validator = $request->validate([
  'business' => 'required|unique:businesses,business,'.$request->id,
], $messages);

if($validator){
  $updateData =DB::table('businesses')->where('id',$request->id)->update($data);
  if($updateData){
    return response()->json(['success' => 'Data updated succesifully','status'=>201]);
  }else{
    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }
}
else{

  return back()->withErrors($validator)->withInput();
}
}

public function insertemployee(request $request){
  $data = array();
  $data['name']=$request->name;
  $data['dob']=$request->dob;
  $data['phone']=$request->phone;
  $data['email']=$request->email;  
  $data['idtype']=$request->idtype;
  $data['idnumber']=$request->idnumber;
  $data['started_on']=$request->started_on;
  $messages = [
    'name.required' => 'Name is required.',
    'dob.required' => 'Date of Birth is required.',
    'email.unique' => 'The email address is already taken.',
    'email.required' => 'Email  is required.',
    'email.email' => 'Email must be valid.',
    'phone.unique' => 'The phone number is already taken.',
    'phone.required' => 'Phone numberis required.',
    'idtype.required' => 'ID type is required.',
    'idnumber.required' => 'ID number is required.',
    'started_on.required' => 'Date started working  is required.', 
];

$validator = $request->validate([
    'name' => 'required',
    'dob' => 'required',
    'email' => 'required|email|unique:employees,email',
    'phone' => 'required|unique:employees,phone',
    'idtype' => 'required',
    'idnumber' => 'required',
    'started_on' => 'required',
], $messages);
if($validator){
  $insertData = DB::table('employees')->insert($data);
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



public function deleteemployee(request $request){
  $id = $request->id;
  $deleteData = DB::table('employees')->where('id',$id)->delete();  
  if($deleteData){
    DB::table('users')->where('employeeid',$request->id)->delete();
    DB::table('userbranch')->where('employeeid',$request->id)->delete();
    return response()->json(['success' => 'Data deleted succesifully','status'=>201]);
  }else{
    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }
}

public function editemployee(request $request){
  $data = array();
  $data['name']=$request->name;
  $data['dob']=$request->dob;
  $data['phone']=$request->phone;
  $data['email']=$request->email;
  $data['idtype']=$request->idtype;
  $data['idnumber']=$request->idnumber;
  $data['started_on']=$request->started_on;

  $messages = [
      'name.required' => 'Name is required.',
      'dob.required' => 'Date of Birth is required.',
      'email.unique' => 'The email address is already taken.',
      'email.required' => 'Email is required.',
      'email.email' => 'Email must be valid.',
      'phone.unique' => 'The phone number is already taken.',
      'phone.required' => 'Phone number is required.',
      'idtype.required' => 'ID type is required.',
      'idnumber.required' => 'ID number is required.',
      'started_on.required' => 'Date started working is required.',
  ];

  $validator = $request->validate([
      'name' => 'required',
      'dob' => 'required',
      'email' => 'required|email|unique:employees,email,'.$request->id,
      'phone' => 'required|unique:employees,phone,'.$request->id,
      'idtype' => 'required',
      'idnumber' => 'required',
      'started_on' => 'required',
  ], $messages);

  if($validator){
      $updateData =DB::table('employees')->where('id',$request->id)->update($data);
      if($updateData ){
          return response()->json(['success' => 'Data updated succesfully','status'=>201]);
      }else{
          return response()->json(['error' => 'An error occured no data change detected','status'=>422]);
      }
  }else{
      return back()->withErrors($validator)->withInput();
  }
}




public function deletebranch(request $request){
  $id = $request->id;
  $deleteData = DB::table('branches')->where('id',$id)->delete();  
  if($deleteData){
    return response()->json(['success' => 'Data deleted succesifully','status'=>201]);
  }else{
    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }
}




public function insertuser(Request $request){
  $data = array();
  $manager = Auth::user()->username;
  $username = DB::table('employees')->where('id',$request->employeeid)->value('name');
  $role = $request->role;
  $email = DB::table('employees')->where('id',$request->employeeid)->value('email');
  $password = Hash::make(1234);

  $messages = [
      'employeeid.required' => 'Employee is required.',
      'employeeid.unique' => 'The selected employee is already added.',
      'branchid.required' => 'Branch is required.',
      'role.required' => 'Role is required.',
  ];

  $validator = $request->validate([
      'employeeid' => 'required|unique:users,employeeid',
      'branchid' => 'required',
      'role' => 'required',
  ], $messages);

  if($validator){
      $insertData = DB::table('users')->insert([
          'employeeid' => $request->employeeid,
          'username' => $username,
          'email' => $email,
          'role' => $role,
          'password' => $password,
      ]);

      if($insertData){
          $countuserbranch = DB::table('userbranch')->where('employeeid',$request->employeeid)->count();

          if($countuserbranch > 0){
              $assignedBranchId = DB::table('userbranch')->where('employeeid',$request->employeeid)->value('branchid');
              $assignedBranchName = DB::table('branches')->where('id', $assignedBranchId)->value('branch');
              return response()->json(['info' => 'User inserted successfully, but is already assigned to the '.$assignedBranchName.' branch by '.$manager.'. You can use the edit button to reassign a different branch if needed.','status'=>200]);
          } else {
              DB::table('userbranch')->insert([
                  'employeeid' => $request->employeeid,
                  'branchid' => $request->branchid,
                  'manager' => $manager,
              ]);
              return response()->json(['success' => 'Data submitted successfully','status'=>201]);
          }
      } else {
          return response()->json(['error' => 'An error occurred. Try again later','status'=>422]);
      }
  } else {
      return back()->withErrors($validator)->withInput();
  }
}


public function deleteuser(request $request){
  $id = $request->id;
  $user = Auth::user()->id;

  if($id == $user){

    return response()->json(['error' => 'You can not delete yourself','status'=>423]);

  }
  else{

    $deleteData = DB::table('users')->where('id',$id)->delete();  

    if($deleteData){
      return response()->json(['success' => 'Data deleted succesifully','status'=>201]);
    }else{
      return response()->json(['error' => 'An error occured try again later','status'=>422]);
    }

  }

 
}





public function  edituser(request $request){
  $data = array();
  $data['role'] = $request->role;
  $branch = array();
  $branch['branchid']=$request->branchid;

  $updateRole = DB::table('users')->where('id',$request->id)->update($data);
  
  $updateBranch = DB::table('userbranch')->where('employeeid',$request->employeeid)->update($branch);

  if(  $updateRole ||   $updateBranch){
    return response()->json(['success' => 'Data updated successfully','status'=>201]);

  }
  else{

    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }
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




public function  insertbranch(request $request){
  $data = array();
  $data['branch'] = $request->branch;
  $data['sector'] = $request->sector;
  $data['address'] = $request->address;
  $data['contact'] = $request->contact;
  $data['email'] = $request->email;
  $data['category'] = $request->category;
  $messages = [
    'branch.unique' => 'Branch name must be unique.',
    'sector.required' => 'Sector is required.',
    'address.required' => 'Address is required.',
    'contact.required' => 'Contact is required.',
    'email.required' => 'Email is required.',
    'category.required' => 'Category is required.',
  ];
$validator = $request->validate([
  'branch' => 'required|unique:branches,branch',
  'sector' => 'required',
  'address' => 'required',
  'contact' => 'required',
  'email' => 'email',
  'category' => 'required',
], $messages);

if($validator){
$insertData =  DB::table('branches')->insert($data);
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






public function  editbranch(request $request){
  $data = array();
  $data['branch'] = $request->branch;
  $data['sector'] = $request->sector;
  $data['address'] = $request->address;
  $data['contact'] = $request->contact;
  $data['email'] = $request->email;
  $data['category'] = $request->category;
  $messages = [
    'branch.unique' => 'Branch name must be unique.',
    'sector.required' => 'Sector is required.',
    'address.required' => 'Address is required.',
    'contact.required' => 'Contact is required.',
    'email.required' => 'Email is required.',
    'category.required' => 'Category is required.',
  ];
$validator = $request->validate([
  'branch' => 'required|unique:branches,branch,'.$request->id,
  'sector' => 'required',
  'address' => 'required',
  'contact' => 'required',
  'email' => 'email',
  'category' => 'required',
], $messages);

if($validator){

  $updateData =  DB::table('branches')->where('id',$request->id)->update($data);
  if($updateData){
   return response()->json(['success' => 'Data submitted successfully','status'=>201]);
  }else{
    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }

}
else{
  return back()->withErrors($validator)->withInput();
}
} 







public function  insertbusinesscategory(request $request){
  $data = array();
  $data['category'] = $request->category;
  $data['description'] = $request->description;
  $messages = [
    'category.unique' => 'Category name must be unique.',
    'category.required' => 'Category is required.',
  ];
$validator = $request->validate([
  'category' => 'required|unique:businesscategories,category',
], $messages);

if($validator){
$insertData =  DB::table('businesscategories')->insert($data);
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





public function deletebusinesscategory(request $request){
  $id = $request->id;
  $deleteData = DB::table('businesscategories')->where('id',$id)->delete();  
  if($deleteData){
    return response()->json(['success' => 'Data deleted succesifully','status'=>201]);
  }else{
    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }
}




public function  editbusinesscategory(request $request){
  $data = array();
  $data['category'] = $request->category;
  $data['description'] = $request->description;
  $messages = [
    'category.unique' => 'Category name must be unique.',
    'category.required' => 'Category is required.',
  ];
$validator = $request->validate([
  'category' => 'required|unique:businesscategories,category,'.$request->id,
], $messages);

if($validator){

  $updateData =  DB::table('businesscategories')->where('id',$request->id)->update($data);
  if($updateData){
   return response()->json(['success' => 'Data submitted successfully','status'=>201]);
  }else{
    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }
  


}
else{
  return back()->withErrors($validator)->withInput();
}
} 



    
public function   insertsupplier(request $request){
  $data = array();
  $data['supplier'] = $request->supplier;
  $data['sector'] = $request->sector;
  $data['category'] = $request->category;
  $data['address'] = $request->address;
  $data['contact'] = $request->contact;
  $data['email'] = $request->email;
  $messages = [
    'supplier.unique' => 'Supplier name must be unique.',
    'sector.required' => 'Sector is required.',
    'category.required' => 'Category is required.',
    'address.required' => 'Address is required.',
    'contact.required' => 'Contact is required.',
    'email.required' => 'Email is required.',
  ];
$validator = $request->validate([
  'supplier' => 'required|unique:suppliers,supplier',
  'sector' => 'required',
  'category' => 'required',
  'address' => 'required',
  'contact' => 'required',
  'email' => 'email',
], $messages);

if($validator){
$insertData =  DB::table('suppliers')->insert($data);
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


public function   editsupplier(request $request){
  $data = array();
  $data['supplier'] = $request->supplier;
  $data['sector'] = $request->sector;
  $data['category'] = $request->category;
  $data['address'] = $request->address;
  $data['contact'] = $request->contact;
  $data['email'] = $request->email;
  $messages = [
    'supplier.unique' => 'Supplier name must be unique.',
    'sector.required' => 'Sector is required.',
    'category.required' => 'Category is required.',
    'address.required' => 'Address is required.',
    'contact.required' => 'Contact is required.',
    'email.required' => 'Email is required.',
  ];
$validator = $request->validate([
  'supplier' => 'required|unique:suppliers,supplier,'.$request->id,
  'sector' => 'required',
  'address' => 'required',
  'contact' => 'required',
  'email' => 'email',
], $messages);

if($validator){

  $updateData =  DB::table('suppliers')->where('id',$request->id)->update($data);
  if($updateData){
   return response()->json(['success' => 'Data submitted successfully','status'=>201]);
  }else{
    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }

}
else{
  return back()->withErrors($validator)->withInput();
}
} 






public function deletesupplier(request $request){
  $id = $request->id;
  $deleteData = DB::table('suppliers')->where('id',$id)->delete();  
  if($deleteData){
    return response()->json(['success' => 'Data deleted succesifully','status'=>201]);
  }else{
    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }
}






}