@extends('admin.dashboard')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<!--start page wrapper -->
<div class="page-wrapper">
<div class="page-content">


		
	  <div class="card">
			<div class="card-body">
				<ul class="nav nav-pills mb-3" role="tablist">

							
		<li class="nav-item" role="presentation"> 
			<a class="nav-link active" data-bs-toggle="pill" href="#primary-pills-profile" role="tab" aria-selected="true"> 
				<div class="d-flex align-items-center"> 
					<div class="tab-icon"><i class='bx bx-user font-18 me-1'></i> </div> 
					<div class="tab-title">Profile</div> 
				</div> 
			</a> 
		</li>
		<li class="nav-item" role="presentation"> 
			<a class="nav-link" data-bs-toggle="pill" href="#primary-pills-leaves" role="tab" aria-selected="false"> 
				<div class="d-flex align-items-center"> 
					<div class="tab-icon"><i class='bx bx-calendar font-18 me-1'></i> </div> 
					<div class="tab-title">Leaves</div> 
				</div> 
			</a> 
		</li>
		<li class="nav-item" role="presentation"> 
			<a class="nav-link" data-bs-toggle="pill" href="#primary-pills-finances" role="tab" aria-selected="false"> 
				<div class="d-flex align-items-center"> 
					<div class="tab-icon"><i class='bx bx-wallet font-18 me-1'></i> </div> 
					<div class="tab-title">Finances</div> 
				</div> 
			</a> 
		</li>
		<li class="nav-item" role="presentation"> 
			<a class="nav-link" data-bs-toggle="pill" href="#primary-pills-password" role="tab" aria-selected="false"> 
				<div class="d-flex align-items-center"> 
					<div class="tab-icon"><i class='bx bx-lock font-18 me-1'></i> </div> 
					<div class="tab-title">Change Password</div> 
				</div> 
			</a> 
		</li>

				</ul>
	<div class="tab-content" id="pills-tabContent">
	<div class="tab-pane fade show active" id="primary-pills-profile" role="tabpanel">
		<?php
		$employeeId = Auth::user()->employeeid;
		$name = ""

		?>
		@if( $employeeId > 0)
		<?php
		$name = DB::table('employees')->where('id',$employeeId)->value('name');
		$phone = DB::table('employees')->where('id',$employeeId)->value('phone');
		$email = DB::table('employees')->where('id',$employeeId)->value('email');
		$dob = DB::table('employees')->where('id',$employeeId)->value('dob');
		$idtype = DB::table('employees')->where('id',$employeeId)->value('idtype');
		$idnumber = DB::table('employees')->where('id',$employeeId)->value('idnumber');
		$status = DB::table('employees')->where('id',$employeeId)->value('status');

		$startedon = DB::table('employees')->where('id',$employeeId)->value('started_on');
		$registeredon = DB::table('employees')->where('id',$employeeId)->value('registered_on');

		?>
		<p style="font-size:16px">Contact system administrator if some information is not correct</p>


		<div class="row">
		<div class="col-md-6">
		<table class="table">
		<tbody>
		<tr>
		<th scope="row">
		Name</th>
		<td>{{$name}}
		</td>
		</tr>
		<tr>
		<th scope="row">
		Phone Number</th>
		<td>{{$phone}}</td>
		</tr>
		<tr>
		<th scope="row">
		Email</th>
		<td>{{$email}}</td>
		</tr>
		<tr>

		<th scope="row">
		Date of birth</th>
		<td>{{$dob}}</td>
		</tr>


		<th scope="row">
		ID Type</th>
		<td>{{$idtype}}</td>
		</tr>


		<th scope="row">
		ID Number</th>
		<td>{{$idnumber}}</td>
		</tr>


		</tbody>
		</table>

		</div>

		<div class="col-md-6">


		<div class="table-responsive">
		<table class="table">
		<tbody>

		<tr>
		<th scope="row">
		Date started working</th>
		<td>{{$startedon}}
		</td>
		</tr>


		<tr>
		<th scope="row">
		Position</th>
		<td>NA
		</td>
		</tr>



		<tr>
		<th scope="row">
		Status </th>
		<td>
		@if($status==1)
		Active
		@else
		Inactve
		@endif
		</td>
		</tr>


		<tr>
		<th scope="row">
		Branch</th>
		<td>
		<?php
		$branchid = DB::table('userbranch')->where('employeeid',$employeeId)->value('branchid');
		$branchName = DB::table('branches')->where('id',$branchid)->value('branch')
		?>
		@if($branchid)
		{{$branchName}}
		@else
		Not set
		@endif
		</td>
		</tr>



		<tr>
		<th scope="row">
		Role</th>
		<td>
		<?php 
		$role = Auth::user()->role;
		?>
		@if($role)
		{{$role}}
		@else
		Not set
		@endif

		</td>
		</tr>

		</tbody>
		</table>
		</div>
		</div>

		@else
		<p style="font-size:16px">To view more details you have to be added as employee</p>
		@endif
</div>

<div class="tab-pane fade" id="primary-pills-leaves" role="tabpanel">
	<p>Leaves</p>
</div>
<div class="tab-pane fade" id="primary-pills-finances" role="tabpanel">
	<p>Finances</p>
</div>

<div class="tab-pane fade" id="primary-pills-password" role="tabpanel">


<form action="change-password" method="post" id="changePasswordForm">
	@csrf
<div class="row">
<div class="col-md-4">
      <div class="form-group ">
        <label for="">Current password</label>
        <input type="password" name="currentpassword" class="form-control" placeholder="Enter current password">
      </div>
  </div>


  <div class="col-md-4">
      <div class="form-group ">
        <label for="">New password</label>
        <input type="password" name="newpassword" class="form-control" placeholder="Enter new password" >
      </div>
  </div>

  
<div class="col-md-4">
      <div class="form-group ">
        <label for="">Comfirm password</label>
        <input type="password" name="comfirmpassword" class="form-control" placeholder="Comfirm password">
      </div>

<button class="btn btn-primary" style="float:right;margin-top:20px" id="submitDataBtn">Submit</button>

  </div>

  </div>
  </form>



</div>



		</div>
	</div>
	</div>
	</div>



</div>
</div>
<!--end page wrapper -->



<!-- jQuery -->
<script src="Admin320/plugins/jquery/jquery.min.js"></script>
<script src="Admin320/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="Admin320/plugins/toastr/toastr.min.js"></script>
<script>
 var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 12000
    });
$(document).ready(function() {

  $('#submitDataBtn').click(function(e) {
      var self = $(this);
      $(this).prop("disabled", true);
      var form = document.getElementById("changePasswordForm");
      e.preventDefault(); 
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        type:"post",
         url: '/change-password',
         data: $(form).serialize(),
          timeout: 60000,
          beforeSend: function() {
            $('#loading-status').css('display', 'block');
          },
          complete: function() {
            $('#loading-status').css('display', 'none');
            self.prop("disabled", false);
           },
          success: function(data) {
            if(data.status===201){
              toastr.success(data.success,'Success',{ timeOut : 5000 ,	progressBar: true});
              form.reset();
            }else if(data.status===422){
              toastr.error(data.error,'Error',{ timeOut : 5000 , 	progressBar: true})  
            }else{
              toastr.info('Success!','Success',{ timeOut : 5000 , 	progressBar: true}); 
              form.reset();
            }
          },
        error: function(xhr, status, error) {
        if (xhr.status === 0 && xhr.readyState === 0) {
            toastr.error('Timeout check your internet connect and try again','Timeout Error',{ timeOut : 5000 , 	progressBar: true})  
          } else if (xhr.status === 422) {
              var errorPassage = '';
              var errors = xhr.responseJSON.errors;
              $.each(errors, function(key, value) { errorPassage += value + '\n'});
              toastr.error(errorPassage, 'Validation Errors', {timeOut: 5000, 	progressBar: true});
          } else if (xhr.status === 500) {
              var errorMessage = xhr.responseText;
              toastr.error('Internal server error occured try again later', 'Server Error', {timeOut: 5000 , 	progressBar: true});
          } else {
          toastr.error('Unspecified error occured try again later', 'Unspecified Error',{timeOut: 5000 ,	progressBar: true});
        }
          }  
        });
      });

})

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