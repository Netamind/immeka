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


    public function users(){

        return view('admin.users');

    }

    public function appdata(){

        return view('admin.appdata');

    }




     public function   employees(){

      return view('admin.employees');

     }

    
 
     public function     profile(){

      return view('admin.profile');

     }


     public function hero(){

        return view('admin.web.hero');
     }


     

     public function header(){

      return view('admin.web.header');
   }

   

   public function footer(){

    return view('admin.web.footer');
 }

 
 public function about(){

  return view('admin.web.about');
}




 
public function services(){
  return view('admin.web.services');
}

     

 
public function privacy(){
  return view('admin.web.privacy');
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



public function insertemployee(request $request){
    $data = array();
    $data['name']=$request->name;
    $data['dob']=$request->dob;
    $data['phone']=$request->phone;
    $data['email']=$request->email;  
    $data['idtype']=$request->idtype;
    $data['idnumber']=$request->idnumber;
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
  ];
  
  $validator = $request->validate([
      'name' => 'required',
      'dob' => 'required',
      'email' => 'required|email|unique:employees,email',
      'phone' => 'required|unique:employees,phone',
      'idtype' => 'required',
      'idnumber' => 'required',
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
    ];
  
    $validator = $request->validate([
        'name' => 'required',
        'dob' => 'required',
        'email' => 'required|email|unique:employees,email,'.$request->id,
        'phone' => 'required|unique:employees,phone,'.$request->id,
        'idtype' => 'required',
        'idnumber' => 'required',
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
        'role.required' => 'Role is required.',
    ];
  
    $validator = $request->validate([
        'employeeid' => 'required|unique:users,employeeid',
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

         return response()->json(['success' => 'Data submitted successfully','status'=>201]);
        } 
        else {
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
   
  
    $updateRole = DB::table('users')->where('id',$request->id)->update($data);
    
  
  
    if(  $updateRole ){

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
  
  public function adminupdateheromessage(Request $request)
{
    $updated = DB::table('hero')->updateOrInsert([], [
        'motto' => $request->motto,
        'title' => $request->title,
        'message' => $request->message,
    ]);

    if ($updated){

      return response()->json(['success' => 'Data updated successfully','status'=>201]);
    }
    else{
      return response()->json(['error' => 'An error occured no data change detected, refreshb the page and try again','status'=>422]);
    }
    
    
}







public function  adminupdateherobackgroundimage(request $request){
  $response = 0;
  $data = array();
  $countRow = DB::table('hero')->count();
  $mimeType = $request->mimeType;
  $extension = explode('/', $mimeType)[1];
   $messages = [
    'backgroundimage.required' => 'Image is required',
    'backgroundimage.image' => 'You should upload an image',
    'backgroundimage.max' => 'Size of the image shold not be more than 2mb',
];
$validator = $request->validate([
    'backgroundimage' =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
], $messages);

   if($validator){

    $bgImageToDelete = DB::table('hero')->value('backgroundimage');

    $deletePath = public_path('/web/images/'.  $bgImageToDelete );

    File::delete($deletePath );

      $bgImage = $request->file('backgroundimage');
      $imageName = time() .'backgroundimage'.'.'. $extension;
      $path = public_path('/web/images/');
      $bgImage->move($path, $imageName);
      if($countRow>0){
        $data['backgroundimage']= $imageName;
        $success = DB::table('hero')->update($data);
          if ($success) {
              return response()->json(['success' => 'Image uploaded successfully','status'=>201]);
            } else {
              return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }else{

        $data['backgroundimage']= $imageName;
        $success = DB::table('hero')->insert($data);
          if ($success) {
            return response()->json(['success' => 'Image submitted successfully','status'=>201]);
            } else {
              return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }
    }
  else{
    return back()->withErrors($validator)->withInput();
  }
      
} 



public function adminupdateherodisplayimage(Request $request){
  $response = 0;
  $data = array();
  $countRow = DB::table('hero')->count();
  $mimeType = $request->mimeType;
  $extension = explode('/', $mimeType)[1];
  $messages = [
      'displayimage.required' => 'Image is required',
      'displayimage.image' => 'You should upload an image',
      'displayimage.max' => 'Size of the image should not be more than 2mb',
  ];
  $validator = $request->validate([
      'displayimage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
  ], $messages);
  if($validator){
      $displayImageToDelete = DB::table('hero')->value('displayimage');
      $deletePath = public_path('/web/images/'. $displayImageToDelete );
      File::delete($deletePath );
      $displayImage = $request->file('displayimage');
      $imageName = time() .'displayimage'.'.'. $extension;
      $path = public_path('/web/images/');
      $displayImage->move($path, $imageName);
      if($countRow>0){
          $data['displayimage']= $imageName;
          $success = DB::table('hero')->update($data);
          if ($success) {
              return response()->json(['success' => 'Image uploaded successfully','status'=>201]);
          } else {
              return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }else{
          $data['displayimage']= $imageName;
          $success = DB::table('hero')->insert($data);
          if ($success) {
              return response()->json(['success' => 'Image submitted successfully','status'=>201]);
          } else {
              return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }
  } else{
      return back()->withErrors($validator)->withInput();
  }
}





public function adminupdateheaderinfo(Request $request)
{
    $updated = DB::table('header')->updateOrInsert([], [
        'email' => $request->email,
        'contact' => $request->contact,
        'address' => $request->address,
    ]);

    if ($updated){

      return response()->json(['success' => 'Data updated successfully','status'=>201]);
    }
    else{
      return response()->json(['error' => 'An error occured no data change detected, refreshb the page and try again','status'=>422]);
    }
    
    
}


public function adminupdatenavbarlogo(Request $request){
  $response = 0;
  $data = array();
  $countRow = DB::table('header')->count();
  $mimeType = $request->mimeType;
  $extension = explode('/', $mimeType)[1];
  $messages = [
      'logo.required' => 'Image is required',
      'logo.image' => 'You should upload an image',
      'logo.max' => 'Size of the image should not be more than 2mb',
  ];
  $validator = $request->validate([
      'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
  ], $messages);
  if($validator){
      $bgImageToDelete = DB::table('header')->value('logo');
      $deletePath = public_path('/web/images/'. $bgImageToDelete );
      File::delete($deletePath );
      $bgImage = $request->file('logo');
      $imageName = time() .'navbarlogo'.'.'. $extension;
      $path = public_path('/web/images/');
      $bgImage->move($path, $imageName);
      if($countRow>0){
          $data['logo']= $imageName;
          $success = DB::table('header')->update($data);
          if ($success) {
              return response()->json(['success' => 'Image uploaded successfully','status'=>201]);
          } else {
              return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }else{
          $data['logo']= $imageName;
          $success = DB::table('header')->insert($data);
          if ($success) {
              return response()->json(['success' => 'Image submitted successfully','status'=>201]);
          } else {
              return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }
  } else{
      return back()->withErrors($validator)->withInput();
  }
}


public function adminupdatewebsiteicon(Request $request){
  $response = 0;
  $data = array();
  $countRow = DB::table('header')->count();
  $mimeType = $request->mimeType;
  $extension = explode('/', $mimeType)[1];
  $messages = [
      'icon.required' => 'Image is required',
      'icon.image' => 'You should upload an image',
      'icon.max' => 'Size of the image should not be more than 2mb',
  ];
  $validator = $request->validate([
      'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
  ], $messages);
  if($validator){
      $bgImageToDelete = DB::table('header')->value('icon');
      $deletePath = public_path('/web/images/'. $bgImageToDelete );
      File::delete($deletePath );
      $bgImage = $request->file('icon');
      $imageName = time() .'websiteicon'.'.'. $extension;
      $path = public_path('/web/images/');
      $bgImage->move($path, $imageName);
      if($countRow>0){
          $data['icon']= $imageName;
          $success = DB::table('header')->update($data);
          if ($success) {
              return response()->json(['success' => 'Image uploaded successfully','status'=>201]);
          } else {
              return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }else{
          $data['icon']= $imageName;
          $success = DB::table('header')->insert($data);
          if ($success) {
              return response()->json(['success' => 'Image submitted successfully','status'=>201]);
          } else {
              return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }
  } else{
      return back()->withErrors($validator)->withInput();
  }
}

public function adminupdatefooterinfo(Request $request)
{
    $updated = DB::table('footer')->updateOrInsert([], [
        'message' => $request->message,
        'email' => $request->email,
        'contact' => $request->contact,
        'address' => $request->address,
    ]);

    if ($updated){

      return response()->json(['success' => 'Data updated successfully','status'=>201]);
    }
    else{
      return response()->json(['error' => 'An error occured no data change detected, refreshb the page and try again','status'=>422]);
    }
    
    
}


public function adminupdatefooterlogo(Request $request){
  $response = 0;
  $data = array();
  $countRow = DB::table('footer')->count();
  $mimeType = $request->mimeType;
  $extension = explode('/', $mimeType)[1];
  $messages = [
      'logo.required' => 'Image is required',
      'logo.image' => 'You should upload an image',
      'logo.max' => 'Size of the image should not be more than 2mb',
  ];
  $validator = Validator::make($request->all(), [
      'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
  ], $messages);

  if ($validator->fails()) {
      return response()->json(['error' => $validator->messages()], 422);
  }

  $bgImageToDelete = DB::table('footer')->value('logo');
  if ($bgImageToDelete) {
      $deletePath = public_path('/web/images/'.$bgImageToDelete);
      File::delete($deletePath);
  }
  $bgImage = $request->file('logo');
  $imageName = time().'footerlogo'.'.'.$extension;
  $path = public_path('/web/images/');
  $bgImage->move($path, $imageName);

  if($countRow>0){
      $data['logo']= $imageName;
      $success = DB::table('footer')->update($data);
      if ($success) {
          return response()->json(['success' => 'Image uploaded successfully','status'=>201]);
      } else {
          return response()->json(['error' => 'An error occured try again later','status'=>422]);
      }
  }else{
      $data['logo']= $imageName;
      $success = DB::table('footer')->insert($data);
      if ($success) {
          return response()->json(['success' => 'Image submitted successfully','status'=>201]);
      } else {
          return response()->json(['error' => 'An error occured try again later','status'=>422]);
      }
  }
}



public function adminupdateaboutinfo(Request $request)
{
    $updated = DB::table('about')->updateOrInsert([], [
        'title' => $request->title,
        'subtitle' => $request->subtitle,
        'paragraph1' => $request->paragraph1,
        'paragraph2' => $request->paragraph2,
        'paragraph3' => $request->paragraph3,
    ]);
    if ($updated){
      return response()->json(['success' => 'Data updated successfully','status'=>201]);
    }
    else{
      return response()->json(['error' => 'An error occured no data change detected, refreshb the page and try again','status'=>422]);
    }
    
    
}



public function adminupdateaboutimage(Request $request){
  $response = 0;
  $data = array();
  $countRow = DB::table('about')->count();
  $mimeType = $request->mimeType;
  $extension = explode('/', $mimeType)[1];
  $messages = [
      'displayimage.required' => 'Image is required',
      'displayimage.image' => 'You should upload an image',
      'displayimage.max' => 'Size of the image should not be more than 2mb',
  ];
  $validator = $request->validate([
      'displayimage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
  ], $messages);
  if($validator){
      $displayImageToDelete = DB::table('about')->value('displayimage');
      $deletePath = public_path('/web/images/'. $displayImageToDelete );
      File::delete($deletePath );
      $displayImage = $request->file('displayimage');
      $imageName = time() .'aboutdisplayimage'.'.'. $extension;
      $path = public_path('/web/images/');
      $displayImage->move($path, $imageName);
      if($countRow>0){
          $data['displayimage']= $imageName;
          $success = DB::table('about')->update($data);
          if ($success) {
              return response()->json(['success' => 'Image uploaded successfully','status'=>201]);
          } else {
              return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }else{
          $data['displayimage']= $imageName;
          $success = DB::table('about')->insert($data);
          if ($success) {
              return response()->json(['success' => 'Image submitted successfully','status'=>201]);
          } else {
              return response()->json(['error' => 'An error occured try again later','status'=>422]);
          }
      }
  } else{
      return back()->withErrors($validator)->withInput();
  }
}




public function admininsertservice(request $request){
  $data = array();
  $data['service']=$request->service;
  $data['description']=$request->description;

  $messages = [
    'service.required' => 'Service name is required.',
    'service.unique' => 'Service must be unique.',
    'description.required' => 'Description is required.',
];

$validator = $request->validate([
    'service' => 'required|unique:services,service',
    'description' => 'required',
  
], $messages);
if($validator){
  $insertData = DB::table('services')->insert($data);
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



public function admindeleteservice(request $request){
  $id = $request->id;
  $deleteData = DB::table('services')->where('id',$id)->delete();  
  if($deleteData){
    return response()->json(['success' => 'Data deleted succesifully','status'=>201]);
  }else{
    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }
}


public function adminupdateservice(Request $request)
{
  $data = array();
  $data['service']=$request->service;
  $data['description']=$request->description;


  $messages = [
    'service.required' => 'Service name is required.',
    'service.unique' => 'Service must be unique.',
    'description.required' => 'Description is required.',
];

  $validator = $request->validate([
      'service' => 'required|unique:services,service,'.$request->id,
      'description' => 'required',
  ], $messages);

    if ($validator) {
        $updateData = DB::table('services')->where('id', $request->id)->update($data);
        if ($updateData) {
            return response()->json(['success' => 'Data updated succesfully', 'status' => 201]);
        } else {
            return response()->json(['error' => 'An error occured no data change detected', 'status' => 422]);
        }
    } else {
        return back()->withErrors($validator)->withInput();
    }
}



public function adminupdateservicestitle(Request $request)
{
    $updated = DB::table('servicestitle')->updateOrInsert([], [
        'title' => $request->title,
        'description' => $request->description,
    ]);
    if ($updated){
      return response()->json(['success' => 'Data updated successfully','status'=>201]);
    }
    else{
      return response()->json(['error' => 'An error occured no data change detected, refreshb the page and try again','status'=>422]);
    }
}



public function adminupdateprivacytitle(Request $request)
{
    $updated = DB::table('privacytitle')->updateOrInsert([], [
        'title' => $request->title,
        'description' => $request->description,
    ]);
    if ($updated){
      return response()->json(['success' => 'Data updated successfully','status'=>201]);
    }
    else{
      return response()->json(['error' => 'An error occured no data change detected, refreshb the page and try again','status'=>422]);
    }
}





public function admininsertprivacy(request $request){
  $data = array();
  $data['policy']=$request->policy;
  $data['description']=$request->description;

  $messages = [
    'policy.required' => 'Privacy policy is required.',
    'policy.unique' => 'privacy policy must be unique.',
    'description.required' => 'Description is required.',
];

$validator = $request->validate([
    'policy' => 'required|unique:privacy,policy',
    'description' => 'required',
  
], $messages);
if($validator){
  $insertData = DB::table('privacy')->insert($data);
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



public function admindeleteprivacy(request $request){
  $id = $request->id;
  $deleteData = DB::table('privacy')->where('id',$id)->delete();  
  if($deleteData){
    return response()->json(['success' => 'Data deleted succesifully','status'=>201]);
  }else{
    return response()->json(['error' => 'An error occured try again later','status'=>422]);
  }
}


public function adminupdateprivacy(Request $request)
{
  $data = array();
  $data['policy']=$request->policy;
  $data['description']=$request->description;


  $messages = [
    'policy.required' => 'policy name is required.',
    'policy.unique' => 'policy must be unique.',
    'description.required' => 'Description is required.',
];

  $validator = $request->validate([
      'policy' => 'required|unique:privacy,policy,'.$request->id,
      'description' => 'required',
  ], $messages);

    if ($validator) {
        $updateData = DB::table('privacy')->where('id', $request->id)->update($data);
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
