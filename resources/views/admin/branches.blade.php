@extends('admin.dashboard')
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

<section>
<div class="card">
<div class="card-header">
<h4><i class="feather icon-home"></i>Branches
<a href="#" class="btn btn-primary" id="newDataBtn" style="float:right"><i class="fa fa-plus-circle" style="color:white"></i>New Branch</a>
</h4>
<span style="font-size:18px;">
Manage branches here : Add , edit , delete  and view
</span>
</div>
<div class="card-body">

<div class="table-wrapper" >
<table id="branches-table" class="table  table-sm table-striped-column   table-striped table-fixed-first-column table-fixed-header" >
<thead class="table-dark">
<tr>
<th class="table-dark" >BranchName</th>
<th style="text-align:center">Sector</th>
<th style="text-align:center">Category</th>
<th style="text-align:center">Address</th>
<th style="text-align:center">Contact</th>
<th style="text-align:center">Email</th>
<th style="text-align:center">Action</th>
</tr>
</thead>
<tbody id="tbody">
<?php
$data  = DB::table('branches')->get();
?>
@foreach($data as $d)
<?php $row = "row".$d->id;?>
<tr id="{{$row}}">
   <td >{{$d->branch}}</td>

   <td style="text-align:center">{{$d->sector}}</td>
   <td style="text-align:center">
    <?php $category = DB::table('businesscategories')->where('id',$d->category)->value('category');?>
    {{$category}}
  </td>
   <td style="text-align:center">{{$d->address}}</td>
   <td style="text-align:center">{{$d->contact}}</td>
   <td style="text-align:center">{{$d->email}}</td>

	 <td style="text-align:center">
		<a href="#" class="editDataBtnClass" 
    editId ="{{$d->id}}"
    editRow={{$row}} 
    editbranch="{{$d->branch}}" 
    editsector="{{$d->sector}}" 
    editcategory="{{$d->category}}" 
    editaddress="{{$d->address}}" 
    editcontact="{{$d->contact}}" 
    editemail="{{$d->email}}" 
    > 
    <i class="fa fa-edit text-primary fa-2x" ></i>
    </a>
		<a href="#" class="deleteDataBtnClass" deleteLabel="{{$d->branch}}"  deleteId="{{$d->id}}" deleteRow="{{$row}}">
      <i class="fa fa-trash text-danger fa-2x"></i>
    </a>
	</td>

</tr>
@endforeach
</tbody>
</table>
</div>



</div>
</div>
</section>

</div>
</div>



<section decription="Modal for app data info">
<div class="modal fade-scale" tabindex="-1" role="dialog" id="newDataModal" data-bs-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header ">
        <h5 class="modal-title">Add  new branch</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="insert-branch" method="post"  enctype="multipart/form-data" id="newDataForm">
			@csrf

		
			<div class="form-group">
				<label for="#">Branch Name</label>
				<input type="text"name="branch" class="form-control" placeholder="Enter branch name">
			</div>


  
			<div class="form-group">
				<label for="#">Sector</label>
				<select name="sector" id="" class="form-control">
        <option value="" hidden>Select sector</option>
        <?php 
        $sectors = DB::table('sectors')->get();
        ?>
        @foreach($sectors as $sec)
        <option value="{{$sec->sector}}">{{$sec->sector}}</option>
        @endforeach
        </select>
			</div>


      
			<div class="form-group">
				<label for="#">Category</label>
				<select name="category" id="" class="form-control">
        <option value="" hidden>Select category</option>
        <?php 
        $sectors = DB::table('businesscategories')->get();
        ?>
        @foreach($sectors as $cat)
        <option value="{{$cat->id}}">{{$cat->category}}</option>
        @endforeach
        </select>
			</div>

			<div class="form-group">
				<label for="#">Address</label>
				<input type="text"name="address" class="form-control" placeholder="Enter branch address">
			</div>


      
			<div class="form-group">
				<label for="#">Contact</label>
				<input type="text"name="contact" class="form-control" placeholder="Enter branch contact">
			</div>

      
			<div class="form-group">
				<label for="#">Email</label>
				<input type="text"name="email" class="form-control" placeholder="Enter branch email">
			</div>

    
  
  

	
      </div>

      <div class="modal-footer">
         <a href="#" class="btn btn-secondary" style="float:right"  id="cancelDataBtn">Cancel</a> 
          <button class="btn btn-primary" style="float:right" id="submitDataBtn">Submit</button>   
      </div>

      </form>
    </div>
  </div>
</div>
</section>



<section>
<div class="modal fade-scale" tabindex="-1" role="dialog" id="editDataModal" data-bs-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header ">
        <h5 class="modal-title">Edit branch</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <form action="edit-business-cartigory" method="post"   id="editDataForm">
			@csrf
      <input type="hidden" id="editId" name="id">
      <input type="hidden" id="editRow">
			
      
	
			<div class="form-group">
				<label for="#">Branch Name</label>
				<input type="text"name="branch" class="form-control" id="editbranch">
			</div>


  
			<div class="form-group">
				<label for="#">Sector</label>
				<select name="sector" id="editsector" class="form-control">
        <option value="" hidden>Select sector</option>
        <?php 
        $sectors = DB::table('sectors')->get();
        ?>
        @foreach($sectors as $sec)
        <option value="{{$sec->sector}}">{{$sec->sector}}</option>
        @endforeach
        </select>
			</div>


       
			<div class="form-group">
				<label for="#">Category</label>
				<select name="category" id="editcategory" class="form-control">
        <option value="" hidden>Select category</option>
        <?php 
        $sectors = DB::table('businesscategories')->get();
        ?>
        @foreach($sectors as $cat)
        <option value="{{$cat->id}}">{{$cat->category}}</option>
        @endforeach
        </select>
			</div>


			<div class="form-group">
				<label for="#">Address</label>
				<input type="text"name="address" class="form-control" id="editaddress">
			</div>


      
			<div class="form-group">
				<label for="#">Contact</label>
				<input type="text"name="contact" class="form-control" id="editcontact">
			</div>

      
			<div class="form-group">
				<label for="#">Email</label>
				<input type="text"name="email" class="form-control" id="editemail">
			</div>





      </div>
      <div class="modal-footer">
      <button class="btn btn-primary" style="float:right" id="submitEditDataBtn">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>
</section>






<section>
<!-- Modal -->
<div class="sweet-modal-container ">
  <div class="modal fade sweet-modal " id="deleteDataModal" tabindex="-1" role="dialog" aria-labelledby="sweet-modal-label" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body text-center" >
          <i class="feather icon-alert-circle text-warning" style="font-size:50px"></i>
		<h4 style="paddin-top:10px;padding-bottom:10px">Are you sure you want to delete <span id="displayDeleteItem"></span> ?</h4>
		   <h5>You won't be able to revert this!</h5>
		   <form action="delete-business-cartigory" method="post" id="deleteForm">
			@csrf
			<input type="hidden" id="deleteInputId" name="id">
			<input type="hidden" id="deleteInputRow">
		   </form>
		<a href="#" class="btn btn-primary deleteDataBtn" style="margin-top:25px;margin-bottom:10px">Yes, Delete it</a>
		<a href="#" class="btn btn-warning keepDataBtn" style="margin-top:25px;margin-bottom:10px" >No, Keep it</a>
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
 var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 12000
    });
$(document).ready(function() {


  $('#newDataBtn').click(function() {
    $('#newDataModal').modal('show');
  });

  $('#cancelDataBtn').click(function() {
    document.getElementById('newDataForm').reset();
  });


 
$('#submitDataBtn').click(function(e) {
      var self = $(this);
      $(this).prop("disabled", true);
      var form = document.getElementById("newDataForm");
      e.preventDefault(); 
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        type:"post",
         url: '/insert-branch',
         data: $(form).serialize(),
          timeout: 60000,
          beforeSend: function() {
            $('#loading-status').css('display', 'block');
          },
          complete: function() {
            $('#loading-status').css('display', 'none');
            $("#tbody").load(" #tbody  > *",function(){});
            self.prop("disabled", false);
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

	  

      $('#tbody').on('click', '.deleteDataBtnClass', function() {
      $('#deleteInputId').val($(this).attr('deleteId'));
			$('#displayDeleteItem').html($(this).attr('deleteLabel'));
      $('#deleteInputRow').val($(this).attr('deleteRow'));
			$('#deleteDataModal').modal('show');
      })
		
	
	

    $('#tbody').on('click', '.editDataBtnClass', function() {

			$('#editId').val($(this).attr('editId'));

      $('#editRow').val($(this).attr('editRow'));

      $('#editbranch').val($(this).attr('editbranch'));

      $('#editsector').val($(this).attr('editsector'));

      $('#editcategory').val($(this).attr('editcategory'));

      $('#editaddress').val($(this).attr('editaddress'));
    
      $('#editcontact').val($(this).attr('editcontact'));

      $('#editemail').val($(this).attr('editemail'));

			$('#editDataModal').modal('show');
		});
		

		$('.keepDataBtn').click(function() {
			$('#deleteDataModal').modal('hide');
			toastr.info('Your data is safe', 'Great!',
			{
				timeOut: 5000,
				progressBar: true,
				
			});
		});
	
		
  $(document).on("click", ".deleteDataBtn", function(e) {
  var self = $(this);
  $(this).prop("disabled", true);
  $('#deleteDataModa').modal('hide');
  var form = document.getElementById("deleteForm");
  var row = document.getElementById('deleteInputRow').value;
  e.preventDefault(); 
  $.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
  });
  $.ajax({
      type:"post",
      url: '/delete-branch',     
	  data: $(form).serialize(),
	  beforeSend: function() {
        $('#loading-status').css('display', 'block');
       },
     complete: function() {
		$('#loading-status').css('display', 'none');
		$("#"+row).load(" "+"#"+row+ ">"+ "*",function(){});
		self.prop("disabled", false);
		form.reset();
       },
  success:function(data) {
	  if(data.status===201){
		toastr.success(data.success,'Success',{ timeOut : 5000 ,	progressBar: true});
    $('#deleteDataModal').modal('hide'); 
		}else if(data.status===422){
		toastr.error(data.error,'Error',{ timeOut : 5000 , 	progressBar: true});
    $('#deleteDataModal').modal('hide'); 
		}else{
		toastr.info('Success!','Success',{ timeOut : 5000 , 	progressBar: true});
    $('#deleteDataModal').modal('hide');  
		}
      },
      error:function(jqXHR, textStatus, errorThrown) {
        if(textStatus === 'timeout')
          {   
            toastr.error('It is taking longer to delete the data check your internet connection and try again','Timeout Error',{ timeOut : 5000 , 	progressBar: true})  
            form.reset();
          }
          else{
        
            toastr.error('Server error occured try again later','Server Error',{ timeOut : 5000 , 	progressBar: true})  
            form.reset();
          }
      },
      timeout: 60000
  });

  })




  $(document).on("click", "#submitEditDataBtn", function(e) {
  var self = $(this);
  $(this).prop("disabled", true);
  $('#editDataModal').modal('hide');
  var form = document.getElementById("editDataForm");
  var row = document.getElementById('editRow').value;
  e.preventDefault(); 
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        type:"post",
         url: '/edit-branch',
         data: $(form).serialize(),
          timeout: 60000,
          beforeSend: function() {
            $('#loading-status').css('display', 'block');
          },
          complete: function() {
            $('#loading-status').css('display', 'none');
            $("#"+row).load(" "+"#"+row+ ">"+ "*",function(){});
		         self.prop("disabled", false);
		         form.reset();
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
      })

 
$('#branches-table').DataTable({ 
     dom: 'Bfrtip', 
     autoWidth:false,
     paging: true,
     pageLength: -1,
     lengthChange: false,
     buttons: [

      {
      extend: 'copy',
      title: 'Branches',
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },

     {
      extend: 'excel',
      title: 'Branches',
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },
    
    {
      extend: 'pdf',
      title: 'Branches',
      exportOptions: {
      columns: ':visible:not(:last-child)'
      },
      customize: function (doc) {
        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
        doc.content[1].table.body.forEach(function(row, i) {
          row[0].alignment = 'left'; 
          for (var j = 1; j < row.length; j++) {
            row[j].alignment = 'center'; 
          }
       
        });
      },
     

    },{
      extend: 'print',
      title: 'Branches',
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },
  
  ]
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

