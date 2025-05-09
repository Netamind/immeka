<!doctype html>
<html lang="en" class="semi-dark">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
  <link rel="icon" href="system/images/neta1.png" type="image/x-icon">
	<!--plugins-->
	<link href="dashboard/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="dashboard/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="dashboard/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="dashboard/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="dashboard/feather/css/feather.css">
	<!-- loader-->
	<link href="dashboard/assets/css/pace.min.css" rel="stylesheet" />
	<script src="dashboard/assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="dashboard/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="dashboard/assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="dashboard/assets/css/app.css" rel="stylesheet">
	<link href="dashboard/assets/css/icons.css" rel="stylesheet">
    <title>Netacube - Business management system</title>
</head>

<body class="bg-login">
	<!--wrapper-->
	<div class="wrapper">
            <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
            <div class="col mx-auto">
            <div class="card mb-0">
            <div class="card-body">
            <div class="p-4">
            <div class="mb-3 text-center">
            <img src="system/images/netacube.png" alt="" style="height:35px">
            </div>
            <div class="text-center mb-4">

            <?php
            $companyNeme = DB::table('appdata')->value('appname');
            ?>
            <h4 class="">{{$companyNeme}}</h4>
            </div>
            <div class="form-body">
            <form action="/user-login" class="row g-3" method="post" id="dataForm">
            @csrf
            <div class="col-12">
            <label for="inputEmailAddress" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="inputEmailAddress" placeholder="Enter your email">
            </div>

            <div class="col-12">
            <label for="password" class="form-label">Password</label>
            <input type="text" id="password" autocomplete="off"  class="form-control" placeholder="Enter your password"/>
            <input type="hidden" id="password-actual" name="password">
            </div>


            <div class="col-12">

            <a href="#" id="cancelDataBtn">Cancel</a>
            <a href="/" style="float:right">Forgot Password ?</a>
            </div>

           

            <div class="col-12">

            
              <div class="d-grid">
              <button type="submit" class="btn btn-primary" style="margin-top:10px"><i class="feather icon-lock"></i>Login</button>

              </div>
     
          
         
            </div>
            <div class="col-12">
            <div class="text-center ">
            <!--<p class="mb-0">Don't have an account yet? <a href="auth-basic-signup.html">Sign up here</a></p>-->
            </div>
            </div>
            </form>
            </div>

            <div class="login-separater text-center mb-5"> 
            <span> <a href="#">Contact support <i class="fa fa-paper-plane text-primary"></i></a></span>
            <hr/>
            </div>


            </div>
            </div>
            </div>
            </div>
            </div>
            <!--end row-->
            </div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="dashboard/assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="dashboard/assets/js/jquery.min.js"></script>
	<script src="dashboard/assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="dashboard/assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="dashboard/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<!--app JS-->
	<script src="dashboard/assets/js/app.js"></script>

  <script>
  $('#cancelDataBtn').click(function() {
    document.getElementById('dataForm').reset();
  });
  </script>
    
<script>
const passwordInput = document.getElementById('password');
const passwordActualInput = document.getElementById('password-actual');
let actualPasswordValue = '';

passwordInput.addEventListener('input', (e) => {
    if (e.inputType === 'deleteContentBackward') {
        actualPasswordValue = actualPasswordValue.slice(0, -1);
    } else if (e.data) {
        actualPasswordValue += e.data;
    }
    const maskedValue = '*'.repeat(actualPasswordValue.length);
    e.target.value = maskedValue;
    passwordActualInput.value = actualPasswordValue;
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
            toastr.error( "{{ Session::get('message') }}");
            break;
    }
  @endif
</script>
<!--js toastr notification--> 
</body>
</html>

