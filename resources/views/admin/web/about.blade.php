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
About
<a href="admin-hero" class="btn border-primary" id="newDataBtn2" style="float:right">
<i class="bx bx-refresh" ></i>Refresh
</a>
</h4>
<span>Manage information displayed on about us  of the website.</span>

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
                <form action="#" method="post" id="aboutMessageForm"> 
                @csrf
                <?php
                $aboutTitle=DB::table('about')->value('title');
                $aboutSubTitle=DB::table('about')->value('subtitle');
                $aboutParagraph1=DB::table('about')->value('paragraph1');
                $aboutParagraph2=DB::table('about')->value('paragraph2');
                $aboutParagraph3=DB::table('about')->value('paragraph3');
                ?>

                 <div class="form-group">
                <label for="#" style="padding-bottom:5px">Title</label>
                <input type="text" class="form-control" name="title" value="{{$aboutTitle}}" placeholder="Enter phone number" autocomplete="off"> 
                </div>

                <div class="form-group">
                <label for="#" style="padding-bottom:5px">Sub Title</label>
                <input type="text" class="form-control" name="subtitle" value="{{$aboutSubTitle}}" placeholder="Enter email address" autocomplete="off"> 
                </div>

                <div class="form-group">
                <label for="#" style="padding-bottom:5px">Paragraph 1</label>
                <textarea name="paragraph1" class="form-control"  rows="4" >{{$aboutParagraph1}}</textarea>
                </div>

                <div class="form-group">
                <label for="#" style="padding-bottom:5px">Paragraph 2</label>
                <textarea name="paragraph2" class="form-control"  rows="4" >{{$aboutParagraph2}}</textarea>
                </div>

                <div class="form-group">
                <label for="#" style="padding-bottom:5px">Paragraph 3</label>
                <textarea name="paragraph3" class="form-control"  rows="4" >{{$aboutParagraph2}}</textarea>
                </div>

            
            

            

                <div class="form-group">
                <a href="#"  class="btn border-primary" style="margin-top:10px;float:right" id="updateAboutMessageBtn">Update</a>
                </div>
                </form>  
                </div>
                </div>

        </div>



<div class="tab-pane fade" id="primary-pills-images" role="tabpanel">     
<div class="row" style="margin-top:10px">

<div class="col-md-6">
    <div class="border" style="min-height:250px">
        <h4>
            <a href="#" class="btn" style="margin-top:10px;">Display Image</a>
            <a href="#" class="btn text-primary" style="margin-top:10px;float:right" id="changedisplayimagebtn">
                <i class="bx bx-edit"></i>
            </a>
            <input type="file" id="displayimageinput" accept="image/*" style="display: none;">
        </h4>
        <hr>
        <div id="displayimagediv" style="margin-left:10px">
            <?php $displayImageFile = DB::table('about')->value('displayimage') ?>
            <a href="web/images/{{$displayImageFile}}" data-lightbox="1" data-title="Display Image">
                <img src="web/images/{{$displayImageFile}}" style="max-width: 300px; max-height: 100%; object-fit: cover;" alt>
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

<section description="Modal for changing display image">
    <div class="modal modal-flex" tabindex="-1" role="dialog" id="changeDisplayImageModal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Display Image</h5>
                    <button type="button" class="btn-close closeDisplayImageModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="cropper-container">
                        <img src=" " id="displayImage" alt class="crop-img img-fluid img-responsive">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="uploadDisplayImageBtn" style="float:right">Submit</button>
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


    

$(document).on("click", "#updateAboutMessageBtn", function(e) {
  var self = $(this);
  $(this).prop("disabled", true);
  var form = document.getElementById("aboutMessageForm");
  e.preventDefault(); 
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        type:"post",
         url: '/admin-update-about-info',
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
    $('#changedisplayimagebtn').click(function() {
        $('#displayimageinput').click();
    });

    // Handle file input change
    $('#displayimageinput').change(function(e) {
        var file = e.target.files[0];
        if (file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function(event) {
                var imageData = event.target.result;
                $('#displayImage').attr('src', imageData);
                var image = $('#displayImage');
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
                $('#changeDisplayImageModal').modal('show');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#uploadDisplayImageBtn').click(function() {
        var self = $(this);
        $(this).prop("disabled", true);
        $('#changeDisplayImageModal').modal('hide');
        var image = $('#displayImage');
        var croppedCanvas = image.cropper('getCroppedCanvas');
        var imageData = croppedCanvas.toDataURL();
        var mimeType = imageData.split('/')[1].split(';')[0];
        var blob = dataURLtoBlob(imageData);
        var mimeType = blob.type;
        var formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('displayimage', blob);
        formData.append('mimeType', mimeType);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '/admin-update-about-image',
            data: formData,
            processData: false,
            contentType: false,
            timeout: 60000,
            beforeSend: function() {
                $('#loading-status').css('display', 'block');
            },
            complete: function() {
                $('#loading-status').css('display', 'none');
                $("#displayimagediv").load(" #displayimagediv > *", function() {});
                self.prop("disabled", false);
                $('#displayimageinput').val('')
                imageTargetCropper = $('#displayImage');
                currentCropper = $('#displayImage').data('cropper');
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
    $('.closeDisplayImageModal').on('click', function() {
        $('#displayimageinput').val('')
        imageTargetCropper = $('#displayImage');
        currentCropper = $('#displayImage').data('cropper');
        currentCropper.destroy();
        imageTargetCropper.data('cropper', null);
        $('#changeDisplayImageModal').modal('hide');
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