@extends('retail.sales.dashboard')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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


.sweet-modal-container .sweet-modal .modal-content {
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  background-color: #fff;
  transform: scale(0.5);
  opacity: 0;
  transition: transform 0.3s, opacity 0.3s;
}

.sweet-modal-container .sweet-modal .modal-header {
  border-bottom: none;
  padding: 1.5rem;
}

.sweet-modal-container .sweet-modal .modal-title {
  font-weight: 600;
  font-size: 1.25rem;
}

.sweet-modal-container .sweet-modal .modal-body {
  padding: 1.5rem;
}

.sweet-modal-container .sweet-modal .modal-footer {
  border-top: none;
  padding: 1.5rem;
  justify-content: flex-end;
}

.sweet-modal-container .sweet-modal .modal-footer .btn {
  padding: 0.5rem 1rem;
  font-size: 1rem;
  border-radius: 5px;
}

.sweet-modal-container .sweet-modal .modal-footer .btn-secondary {
  background-color: #f7f7f7;
  color: #666;
  border: 1px solid #ddd;
}

.sweet-modal-container .sweet-modal .modal-footer .btn-primary {
  background-color: #4CAF50;
  color: #fff;
  border: none;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.sweet-modal-container .sweet-modal.show {
  opacity: 1;
}

.sweet-modal-container .sweet-modal.show .modal-content {
  transform: scale(1);
  opacity: 1;
}

.sweet-modal-container .sweet-modal.show .modal-content {
  animation: bounce 0.5s;
}

@keyframes bounce {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
}

.table-wrapper {
  overflow-x: auto;
}


.table-fixed-first-column {
  border-collapse: collapse;
  width: 100%;
  
}

.table-fixed-first-column th:first-child {
  position: sticky !important;
  top: 0 !important;
  left: 0 !important;
  z-index: 2 !important;

  box-shadow: 2px 0px 2px rgba(0, 0, 0, 0.1); 
}


.table-fixed-first-column td:first-child {
  position: sticky !important;
  left: 0 !important;
  z-index: 1 !important;
  background-color:white;
  box-shadow: 2px 0px 2px rgba(0, 0, 0, 0.1); 
}




.table-striped-column td:nth-child(1) { /* Change 2 to the column number you want to stripe */
  background-color:#F7F7F7;
}

.table-striped-column tr:nth-child(odd) td:nth-child(1) {
  background-color: #e6e6e6;
}


.dataTables_wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
   
}

.dataTables_filter, .dt-buttons {
    margin-bottom: 10px;
    overflow-x: hidden;
    position: sticky;
    left:0;
    right: 0;
    
}

@media (max-width: 767px) {
    .dataTables_wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }
}
	</style>

 
 
</head>
<body>

<!--start page wrapper -->
<div class="page-wrapper">
<div class="page-content">

<div class="loading-status" id="loading-status" style="display:none">
  <div class="waves"></div>
</div>


		
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
    <?php $employeeId = Auth::user()->employeeid; $name = "" ?>
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
                            <th scope="row"> Name</th>
                            <td>{{$name}} </td>
                        </tr>
                        <tr>
                            <th scope="row"> Phone Number</th>
                            <td>{{$phone}}</td>
                        </tr>
                        <tr>
                            <th scope="row"> Email</th>
                            <td>{{$email}}</td>
                        </tr>
                        <tr>
                            <th scope="row"> Date of birth</th>
                            <td>{{$dob}}</td>
                        </tr>
                        <th scope="row"> ID Type</th>
                        <td>{{$idtype}}</td>
                        </tr>
                        <th scope="row"> ID Number</th>
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
                                <th scope="row"> Date started working</th>
                                <td>{{$startedon}} </td>
                            </tr>
                            <tr>
                                <th scope="row"> Position</th>
                                <td>NA </td>
                            </tr>
                            <tr>
                                <th scope="row"> Status </th>
                                <td> 
                                    @if($status==1) 
                                        Active 
                                    @else 
                                        Inactve 
                                    @endif 
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"> Branch</th>
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
                                <th scope="row"> Role</th>
                                <td> 
                                    <?php $role = Auth::user()->role; ?>
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