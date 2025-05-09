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
Footer info
<a href="admin-hero" class="btn border-primary" id="newDataBtn2" style="float:right">
<i class="bx bx-refresh" ></i>Refresh
</a>
</h4>
<span>Manage information displayed on footer of the website.</span>

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
                <form action="#" method="post" id="footerMessageForm"> 
                @csrf
                <?php
                $footerContact=DB::table('footer')->value('contact');
                $footerEmail=DB::table('footer')->value('email');
                $footerAddress=DB::table('footer')->value('address');
                $footerMessage=DB::table('footer')->value('message');
                ?>

                 <div class="form-group">
                <label for="#" style="padding-bottom:5px">Contact</label>
                <input type="text" class="form-control" name="contact" value="{{$footerContact}}" placeholder="Enter phone number" autocomplete="off"> 
                </div>

                <div class="form-group">
                <label for="#" style="padding-bottom:5px">Email</label>
                <input type="text" class="form-control" name="email" value="{{$footerEmail}}" placeholder="Enter email address" autocomplete="off"> 
                </div>

            
                <div class="form-group">
                <label for="#" style="padding-bottom:5px">Address</label>
                <input type="text" class="form-control" name="address" value="{{$footerAddress}}" placeholder="Enter address" autocomplete="off"> 
                </div>


                <div class="form-group">
                <label for="#" style="padding-bottom:5px">Footer message (Like mission and vission)</label>
                <textarea name="message" class="form-control" name="message" rows="4" >{{$footerMessage}}</textarea>
                </div>

                <div class="form-group">
                <a href="#"  class="btn border-primary" style="margin-top:10px;float:right" id="updateFooterMessageBtn">Update</a>
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
            <a href="#" class="btn" style="margin-top:10px;">Footer Logo (It should be transparent)</a>
            <a href="#" class="btn text-primary" style="margin-top:10px;float:right" id="changefooterlogobtn"><i class="bx bx-edit"></i></a>
            <input type="file" id="footerlogoinput" accept="image/*" style="display: none;">
        </h4>
        <hr>
        <div id="footerlogodiv" style="margin-left:10px">
            <?php $footerLogoFile = DB::table('footer')->value('logo') ?>
            <a href="web/images/{{$footerLogoFile}}" data-lightbox="1" data-title="Footer Logo">
                <img src="web/images/{{$footerLogoFile}}" style="max-width: 300px; max-height: 100%; object-fit: cover;" alt>
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


<section description="Modal for change footer logo">
    <div class="modal modal-flex" tabindex="-1" role="dialog" id="changeFooterLogoModal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Footer Logo (It should be transparent)</h5>
                    <button type="button" class="btn-close closeFooterLogoModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="cropper-container">
                        <img src=" " id="footerLogo" alt class="crop-img img-fluid img-responsive">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="uploadFooterLogoBtn" style="float:right">Submit</button>
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


    

$(document).on("click", "#updateFooterMessageBtn", function(e) {
  var self = $(this);
  $(this).prop("disabled", true);
  var form = document.getElementById("footerMessageForm");
  e.preventDefault(); 
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        type:"post",
         url: '/admin-update-footer-info',
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
    $('#changefooterlogobtn').click(function() {
        $('#footerlogoinput').click();
    });

    // Handle file input change
    $('#footerlogoinput').change(function(e) {
        var file = e.target.files[0];
        if (file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function(event) {
                var imageData = event.target.result;
                $('#footerLogo').attr('src', imageData);
                var image = $('#footerLogo');
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
                $('#changeFooterLogoModal').modal('show');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#uploadFooterLogoBtn').click(function() {
        var self = $(this);
        $(this).prop("disabled", true);
        $('#changeFooterLogoModal').modal('hide');
        var image = $('#footerLogo');
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
            url: '/admin-update-footer-logo',
            data: formData,
            processData: false,
            contentType: false,
            timeout: 60000,
            beforeSend: function() {
                $('#loading-status').css('display', 'block');
            },
            complete: function() {
                $('#loading-status').css('display', 'none');
                $("#footerlogodiv").load(" #footerlogodiv > *", function() {});
                self.prop("disabled", false);
                $('#footerlogoinput').val('')
                imageTargetCropper = $('#footerLogo');
                currentCropper = $('#footerLogo').data('cropper');
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
    $('.closeFooterLogoModal').on('click', function() {
        $('#footerlogoinput').val('')
        imageTargetCropper = $('#footerLogo');
        currentCropper = $('#footerLogo').data('cropper');
        currentCropper.destroy();
        imageTargetCropper.data('cropper', null);
        $('#changeFooterLogoModal').modal('hide');
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