@extends('admin.dashboard')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>
<body>

<!--start page wrapper -->
<div class="page-wrapper">
<div class="page-content">


		
	  <div class="card">


    
<div class="card-header">
<h4>
 Header info
<a href="admin-hero" class="btn border-primary" id="newDataBtn2" style="float:right">
<i class="bx bx-refresh" ></i>Refresh
</a>
</h4>
<span>Manage information displayed on top of the navigation bar of the website.</span>

</div>



<div class="card-body">
	<ul class="nav nav-pills mb-3" role="tablist">				
		<li class="nav-item" role="presentation"> 
			<a class="nav-link active" data-bs-toggle="pill" href="#primary-pills-info" role="tab" aria-selected="true"> 
				<div class="d-flex align-items-center"> 
					<div class="tab-icon"><i class='feather icon-info font-18 me-1'></i> </div> 
					<div class="tab-title">Info</div> 
				</div> 
			</a> 
		</li>
	
		<li class="nav-item" role="presentation"> 
			<a class="nav-link" data-bs-toggle="pill" href="#primary-pills-images" role="tab" aria-selected="false"> 
				<div class="d-flex align-items-center"> 
					<div class="tab-icon"><i class='bx bx-image font-18 me-1'></i> </div> 
					<div class="tab-title">Images</div> 
				</div> 
			</a> 
		</li>

				</ul>
	<div class="tab-content" id="pills-tabContent">
	

        <div class="tab-pane  show fade active" id="primary-pills-info" role="tabpanel">
         

                <div class="row" >
                <div class="col-md-12">
                <form action="#" method="post" id="headerInfoForm"> 
                @csrf
                <?php
                $contact=DB::table('header')->value('contact');
                $email=DB::table('header')->value('email');
                $address=DB::table('header')->value('address');
                ?>
                <div class="form-group">
                <label for="#" style="padding-bottom:5px">Contact</label>
                <input type="text" class="form-control" name="contact" value="{{$contact}}" placeholder="Enter phone number" autocomplete="off"> 
                </div>

                <div class="form-group">
                <label for="#" style="padding-bottom:5px">Email</label>
                <input type="text" class="form-control" name="email" value="{{$email}}" placeholder="Enter email address" autocomplete="off"> 
                </div>

                <div class="form-group">
                <label for="#" style="padding-bottom:5px">Address</label>
                <input type="text" class="form-control" name="address" value="{{$address}}" placeholder="Enter  address" autocomplete="off"> 
                </div>


                <div class="form-group">
                <a href="#"  class="btn border-primary" style="margin-top:10px;float:right" id="updateHeaderInfoBtn">Update</a>
                </div>
                </form>  
                </div>
                </div>

        </div>
        <div class="tab-pane fade" id="primary-pills-images" role="tabpanel">
              
<div class="row" style="margin-top:10px">


<div class="col-md-6 ">
    <div class="border" style="min-height:250px">
        <h4>
            <a href="#" class="btn" style="margin-top:10px;">Navbar logo <span style="color:gray">(Upload an image without background color)</span></a>
            <a href="#" class="btn text-primary" style="margin-top:10px;float:right" id="changenavbarlogobtn"><i class="bx bx-edit"></i></a>
            <input type="file" id="navbarlogoinput" accept="image/*" style="display: none;">
        </h4>
        <hr>
        <div id="navbarlogodiv" style="margin-left:10px">
            <?php $navbarLogoFile = DB::table('header')->value('logo') ?>
            <a href="web/images/{{$navbarLogoFile}}" data-lightbox="1" data-title="Navbar logo">
                <img src="web/images/{{$navbarLogoFile}}" style="max-width: 300px; max-height: 100%; object-fit: cover;" alt>
            </a>
        </div>
    </div>
</div>



<div class="col-md-6 ">
    <div class="border" style="min-height:250px">
        <h4>
            <a href="#" class="btn" style="margin-top:10px;">Website Icon <span style="color:gray">(Upload an image without background color)</span></a>
            <a href="#" class="btn text-primary" style="margin-top:10px;float:right" id="changewebsiteiconbtn"><i class="bx bx-edit"></i></a>
            <input type="file" id="websiteiconinput" accept="image/*" style="display: none;">
        </h4>
        <hr>
        <div id="websiteicodiv" style="margin-left:10px">
            <?php $websiteIconFile = DB::table('header')->value('icon') ?>
            <a href="web/images/{{$websiteIconFile}}" data-lightbox="1" data-title="Website Icon">
                <img src="web/images/{{$websiteIconFile}}" style="max-width: 300px; max-height: 100%; object-fit: cover;" alt>
            </a>
        </div>
    </div>
</div>



</div>
</div>



		</div>
	</div>
	</div>
	</div>



</div>
</div>
<!--end page wrapper -->



<section description="Modal for changing navbar logo">
    <div class="modal modal-flex" tabindex="-1" role="dialog" id="changeNavbarLogoModal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Navbar Logo</h5>
                    <button type="button" class="btn-close closeNavbarLogoModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="cropper-container">
                        <img src=" " id="navbarLogo" alt class="crop-img img-fluid img-responsive">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="uploadNavbarLogoBtn" style="float:right">Submit</button>
                </div>
            </div>
        </div>
    </div>
</section>


<section description="Modal for changing website icon">
    <div class="modal modal-flex" tabindex="-1" role="dialog" id="changeWebsiteIconModal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Website Icon</h5>
                    <button type="button" class="btn-close closeWebsiteIconModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="cropper-container">
                        <img src=" " id="websiteIcon" alt class="crop-img img-fluid img-responsive">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="uploadWebsiteIconBtn" style="float:right">Submit</button>
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

 var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 12000
    });


    

$(document).on("click", "#updateHeaderInfoBtn", function(e) {
  var self = $(this);
  $(this).prop("disabled", true);
  var form = document.getElementById("headerInfoForm");
  e.preventDefault(); 
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        type:"post",
         url: '/admin-update-header-info',
         data: $(form).serialize(),
          timeout: 60000,
          beforeSend: function() {
            $('#loading-status').css('display', 'block');
          },
          complete: function() {
            $('#loading-status').css('display', 'none');
		         self.prop("disabled", false);
		         //form.reset();
           },
          success: function(data) {
            if(data.status===201){
              toastr.success(data.success,'Success',{ timeOut : 5000 ,	progressBar: true});
            }else if(data.status===422){
              toastr.error(data.error,'Error',{ timeOut : 5000 , 	progressBar: true})  
            }else{
              toastr.info('Success!','Success',{ timeOut : 5000 , 	progressBar: true}); 
            }
          },
        error: function(xhr, status, error) {
        if (xhr.status === 0 && xhr.readyState === 0) {
            toastr.error('Timeout check your internet connect and try again','Timeout Error',{ timeOut : 5000 , 	progressBar: true})  
          }
          else {
          toastr.error('Unspecified error occured try again later', 'Unspecified Error',{timeOut: 5000 ,	progressBar: true});
        }
          }  
        });
      })



      $(document).ready(function() {
    // Trigger file input click on button click
    $('#changenavbarlogobtn').click(function() {
        $('#navbarlogoinput').click();
    });

    // Handle file input change
    $('#navbarlogoinput').change(function(e) {
        var file = e.target.files[0];
        if (file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function(event) {
                var imageData = event.target.result;
                $('#navbarLogo').attr('src', imageData);
                var image = $('#navbarLogo');
                image.cropper({
                    viewMode: 1,
                    dragMode: 'crop',
                    autoCrop: true,
                    zoomable: true,
                    scalable: true,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    dataUrl: function(original) {
                        var extension = original.src.split('.').pop();
                        return original.toDataURL('image/' + extension);
                    }
                });
                $('#changeNavbarLogoModal').modal('show');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#uploadNavbarLogoBtn').click(function() {
        var self = $(this);
        $(this).prop("disabled", true);
        $('#changeNavbarLogoModal').modal('hide');
        var image = $('#navbarLogo');
        var croppedCanvas = image.cropper('getCroppedCanvas');
        var imageData = croppedCanvas.toDataURL();
        var mimeType = imageData.split('/')[1].split(';')[0];
        var blob = dataURLtoBlob(imageData);
        var mimeType = blob.type;
        var formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('logo', blob);
        formData.append('mimeType', mimeType);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '/admin-update-navbar-logo',
            data: formData,
            processData: false,
            contentType: false,
            timeout: 60000,
            beforeSend: function() {
                $('#loading-status').css('display', 'block');
            },
            complete: function() {
                $('#loading-status').css('display', 'none');
                $("#navbarlogodiv").load(" #navbarlogodiv > *", function() {});
                self.prop("disabled", false);
                $('#navbarlogoinput').val('')
                imageTargetCropper = $('#navbarLogo');
                currentCropper = $('#navbarLogo').data('cropper');
                currentCropper.destroy();
                imageTargetCropper.data('cropper', null);
            },
            success: function(data) {
                if (data.status === 201) {
                    toastr.success(data.success, 'Success', {
                        timeOut: 5000
                    })
                } else if (data.status === 422) {
                    toastr.error(data.error, 'Error', {
                        timeOut: 5000
                    })
                } else {
                    toastr.info('Success!', 'Success', {
                        timeOut: 5000
                    })
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 0 && xhr.readyState === 0) {
                    toastr.error('Timeout check your internet connect and try again', 'Timeout Error', {
                        timeOut: 5000
                    })
                } else if (xhr.status === 422) {
                    var errorPassage = '';
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        errorPassage += value + '\n'
                    });
                    toastr.error(errorPassage, 'Validation Errors', {
                        timeOut: 5000
                    });
                } else if (xhr.status === 500) {
                    var errorMessage = xhr.responseText;
                    toastr.error('Internal server error occured try again later', 'Server Error', {
                        timeOut: 5000
                    });
                } else {
                    toastr.error('Unspecified error occured try again later', 'Unspecified Error', {
                        timeOut: 5000
                    });
                }
            }
        });
    });
});


$(document).ready(function() {
    $('.closeNavbarLogoModal').on('click', function() {
        $('#navbarlogoinput').val('')
        imageTargetCropper = $('#navbarLogo');
        currentCropper = $('#navbarLogo').data('cropper');
        currentCropper.destroy();
        imageTargetCropper.data('cropper', null);
        $('#changeNavbarLogoModal').modal('hide');
    });
});




$(document).ready(function() {
    // Trigger file input click on button click
    $('#changewebsiteiconbtn').click(function() {
        $('#websiteiconinput').click();
    });

    // Handle file input change
    $('#websiteiconinput').change(function(e) {
        var file = e.target.files[0];
        if (file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function(event) {
                var imageData = event.target.result;
                $('#websiteIcon').attr('src', imageData);
                var image = $('#websiteIcon');
                image.cropper({
                    viewMode: 1,
                    dragMode: 'crop',
                    autoCrop: true,
                    zoomable: true,
                    scalable: true,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    dataUrl: function(original) {
                        var extension = original.src.split('.').pop();
                        return original.toDataURL('image/' + extension);
                    }
                });
                $('#changeWebsiteIconModal').modal('show');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#uploadWebsiteIconBtn').click(function() {
        var self = $(this);
        $(this).prop("disabled", true);
        $('#changeWebsiteIconModal').modal('hide');
        var image = $('#websiteIcon');
        var croppedCanvas = image.cropper('getCroppedCanvas');
        var imageData = croppedCanvas.toDataURL();
        var mimeType = imageData.split('/')[1].split(';')[0];
        var blob = dataURLtoBlob(imageData);
        var mimeType = blob.type;
        var formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('icon', blob);
        formData.append('mimeType', mimeType);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '/admin-update-website-icon',
            data: formData,
            processData: false,
            contentType: false,
            timeout: 60000,
            beforeSend: function() {
                $('#loading-status').css('display', 'block');
            },
            complete: function() {
                $('#loading-status').css('display', 'none');
                $("#websiteicodiv").load(" #websiteicodiv > *", function() {});
                self.prop("disabled", false);
                $('#websiteiconinput').val('')
                imageTargetCropper = $('#websiteIcon');
                currentCropper = $('#websiteIcon').data('cropper');
                currentCropper.destroy();
                imageTargetCropper.data('cropper', null);
            },
            success: function(data) {
                if (data.status === 201) {
                    toastr.success(data.success, 'Success', {
                        timeOut: 5000
                    })
                } else if (data.status === 422) {
                    toastr.error(data.error, 'Error', {
                        timeOut: 5000
                    })
                } else {
                    toastr.info('Success!', 'Success', {
                        timeOut: 5000
                    })
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 0 && xhr.readyState === 0) {
                    toastr.error('Timeout check your internet connect and try again', 'Timeout Error', {
                        timeOut: 5000
                    })
                } else if (xhr.status === 422) {
                    var errorPassage = '';
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        errorPassage += value + '\n'
                    });
                    toastr.error(errorPassage, 'Validation Errors', {
                        timeOut: 5000
                    });
                } else if (xhr.status === 500) {
                    var errorMessage = xhr.responseText;
                    toastr.error('Internal server error occured try again later', 'Server Error', {
                        timeOut: 5000
                    });
                } else {
                    toastr.error('Unspecified error occured try again later', 'Unspecified Error', {
                        timeOut: 5000
                    });
                }
            }
        });
    });
});

$(document).ready(function() {
    $('.closeWebsiteIconModal').on('click', function() {
        $('#websiteiconinput').val('')
        imageTargetCropper = $('#websiteIcon');
        currentCropper = $('#websiteIcon').data('cropper');
        currentCropper.destroy();
        imageTargetCropper.data('cropper', null);
        $('#changeWebsiteIconModal').modal('hide');
    });
});




function dataURLtoBlob(dataURL) {
    var byteString = atob(dataURL.split(',')[1]);
    var mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
    var arrayBuffer = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(arrayBuffer);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([arrayBuffer], { type: mimeString });
 } 

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