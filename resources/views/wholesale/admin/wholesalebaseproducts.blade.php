@extends('admin.dashboard')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" type="text/css" href="dashboard/files/assets/icon/feather/css/feather.css">


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


.sweet-modal-container {
  /* styles for the modal container */
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





	</style>
</head>
<body>


<div class="loading-status" id="loading-status" style="display:none">
  <div class="waves"></div>
</div>
<section>
<div class="card">
<div class="card-header">
<h4><i class="fa fa-database"></i>  Wholesale Base Products 
<a href="#" class="btn btn-primary" id="uploadCsvBtn"style="float:right"> <i class="feather icon-plus-circle" style="color:white"></i> New</a>

<a href="#" class="btn btn-primary"  id="newDataBtn"   style="float:right;margin-right:10px"><i class="fa fa-cloud-upload" style="color:white"></i> CSV</a> 
</h4>
<span style="font-size:14px;">
Manage wholesale base products. Note that a product must be created here before it can be added to a wholesale branch.
</span>



<hr>


<?php
   
    $catId =  DB::table('session')->where('user',Auth::user()->id)->value('category');
    $supId = Cookie::get('supplier') ?? "NA";
    $categoryName = '';
    $supplierName = '';
    $supplierArray = array();

    if($catId){

    if($catId==0){
      $categoryName = 'All';
    }
     else{
      $categoryName = DB::table('businesscategories')->where('id',$catId)->value('category');
     }
    }
    else{

      $categoryName = 'Not defined';
          
    }
 
    if(is_numeric($supId)){

      if($supId==0){
        $supplierName = 'All';
        $supplierArray = DB::table('suppliers')->where('category',$catId)->pluck('id');
      }
       else{
        $supplierName = DB::table('suppliers')->where('id',$supId)->value('supplier');
        $supplierArray = DB::table('suppliers')->where('id',$supId)->where('category',$catId)->pluck('id');
       }
      }
      else{
  
        $supplierName = 'Not defined';
            
      }

  ?>

<select name="category" id="" style=";border:none;margin-left:-4px" onchange="submitCategoryId(this.value)">
<option value="" hidden>Category : {{$categoryName }}</option>
<?php
$categories = DB::table('businesscategories')->get();
?>
@foreach($categories as $cat)
<option value="{{$cat->id}}">{{$cat->category}}</option>
@endforeach
</select>

| &nbsp;

<select name="supplier" id="" style=";border:none;margin-left:-4px" onchange="submitSupplierId(this.value)">
    <option value="" hidden>Supplier : {{$supplierName}}</option>
    <?php
    $suppliers = DB::table('suppliers')->where('sector','Wholesale')->where('category',$catId)->get();
    ?>
    @foreach($suppliers as $sup)
    <option value="{{$sup->id}}">{{$sup->supplier}}</option>
    @endforeach
    <option value="0">All</option>
</select>


<a href="#" class="btn" style="float:right; display: flex; align-items: center;margin-top:-10px;background-color: #f2f2f2;"> 
  <span><i class="fa fa-check"></i></span><span> 0 </span>
</a>


<script>
    function submitCategoryId(value) {
        document.getElementById('categoryId').value = value;
        document.getElementById('categoryForm').submit();
    }
    
    function submitSupplierId(value) {
        document.getElementById('supplierId').value = value;
        document.getElementById('supplierForm').submit();
    }
</script>
<form action="select-category" method="post" id="categoryForm">
  @csrf
  <input type="hidden" name="category" id="categoryId">
 </form>

<form action="select-supplier" method="post" id="supplierForm">
@csrf
<input type="hidden" name="supplier" id="supplierId">
</form>




<hr>
</div>

<div class="card-block" style="margin-top:-5px">

<div class="table-wrapper">
<table id="business-table" class="table-striped-column  table-sm table-striped table-fixed-first-column table-fixed-header" >
<thead class="table-dark">
<tr>
<th class="table-dark">
<input type="checkbox" class="selectall"> Product</th>
<th style="text-align:center">Unit</th>
<th style="text-align:center">Order Price</th>
<th style="text-align:center">Selling Price</th>
<th style="text-align:center">VAT</th>
<th style="text-align:center">Action</th>
</tr>
</thead>
<tbody id="tbody">
<?php
$data  = DB::table('wholesalebaseproducts')->whereIn('supplier',$supplierArray)->get();
?>
@foreach($data as $d)
<?php $row = "row".$d->id;?>
<tr id="{{$row}}">
   <td ><input type="checkbox" name="select" class="select"> {{$d->product}}</td>
   <td style="text-align:center">{{$d->unit}}</td>
   <td style="text-align:center"> @convert($d->orderprice) </td>
   <td style="text-align:center"> @convert($d->sellingprice) </td>
   <td style="text-align:center">{{$d->vat}}</td>
	 <td style="text-align:center">
		<a href="#" class="editDataBtnClass" 
    editId ="{{$d->id}}"
    editRow={{$row}} 
    editproduct="{{$d->product}}" 
    editsupplier="{{$d->supplier}}" 
    editunit="{{$d->unit}}" 
    editorderprice="{{$d->orderprice}}" 
    editsellingprice="{{$d->sellingprice}}" 
    editvat="{{$d->vat}}" 
    > 
    <i class="fa fa-edit text-primary fa-2x" ></i>
    </a>
		<a href="#" class="deleteDataBtnClass" deleteLabel="{{$d->product}}"  deleteId="{{$d->id}}" deleteRow="{{$row}}">
      <i class="fa fa-trash text-danger fa-2x"></i>
    </a>
	</td>

</tr>
@endforeach
</tbody>
</table>
<!--</div>-->


</div>
</div>
</section>



<section decription="Modal for app data info">
<div class="modal fade-scale" tabindex="-1" role="dialog" id="newDataModal" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title">New product <strong>{{$categoryName}}</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="insert-wholesale-baseproduct" method="post"  enctype="multipart/form-data" id="newDataForm">
			@csrf


		<div class="row">
			<div class="form-group col-md-6">
				<label for="#">Product Name</label>
				<input type="text"name="product" class="form-control" placeholder="Enter product name">
			</div>


      
			<div class="form-group col-md-6">
				<label for="#">Supplier</label>
				<select name="supplier" id="" class="form-control">
        <option value="" hidden>Select supplier</option>
        <?php 
        $suppliers = DB::table('suppliers')->where('category',$catId)->get();
        ?>
        @foreach($suppliers as $sup)
        <option value="{{$sup->id}}">{{$sup->supplier}}</option>
        @endforeach
        </select>
			</div>




			<div class="form-group col-md-6">
				<label for="#">Unit</label>
				<input type="text" name="unit" class="form-control" placeholder="Enter unit">
			</div>



			<div class="form-group col-md-6">
				<label for="#">Order Price</label>
				<input type="number" name="orderprice" class="form-control" placeholder="Enter order price">
			</div>

      

      
      
			<div class="form-group col-md-6">
				<label for="#">Selling Price</label>
				<input type="number" name="sellingprice" class="form-control" placeholder="Enter selling price">
			</div>


    




      <div class="form-group col-md-6">
        <label for="#">VAT</label>
				<select name="vat"  class="form-control"    >
        <option value="EX" hidden>Select VAT</option>
        <?php 
        $vats = DB::table('vat_statuses')->get();
        ?>
        @foreach($vats as $vat)
        <option value="{{$vat->code}}">{{$vat->status}}</option>
        @endforeach
        </select>
			</div>

      <div class="col-md-12">

      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>


        <button class="btn btn-primary" style="float:right" id="submitDataBtn">Submit</button> 


      </div>
    
  


    </div>
		</form>
      </div>
    </div>
  </div>
</div>
</section>



<section>
<div class="modal fade-scale" tabindex="-1" role="dialog" id="editDataModal" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title">Edit product <strong>{{$categoryName}}</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="edit-wholesale-baseproduct" method="post"   id="editDataForm">
			@csrf
      <input type="hidden" id="editId" name="id">
      <input type="hidden" id="editRow">

      

		<div class="row">
			<div class="form-group col-md-6">
				<label for="#">Product Name</label>
				<input type="text"name="product" class="form-control" id="editproduct">
			</div>


      
			<div class="form-group col-md-6">
				<label for="#">Supplier</label>
				<select name="supplier" id="editsupplier" class="form-control">
        <option value="" hidden>Select supplier</option>
        <?php 
        $suppliers = DB::table('suppliers')->where('category',$catId)->get();
        ?>
        @foreach($suppliers as $sup)
        <option value="{{$sup->id}}">{{$sup->supplier}}</option>
        @endforeach
        </select>
			</div>




			<div class="form-group col-md-6">
				<label for="#">Unit</label>
				<input type="text" name="unit" class="form-control" id="editunit">
			</div>



			<div class="form-group col-md-6">
				<label for="#">Order Price</label>
				<input type="number" name="orderprice" class="form-control" id="editorderprice">
			</div>

      

      
      
			<div class="form-group col-md-6">
				<label for="#">Selling Price</label>
				<input type="number" name="sellingprice" class="form-control" id="editsellingprice">
			</div>


      



      <div class="form-group col-md-6">
        <label for="#">VAT</label>
				<select name="vat" class="form-control"   id="editvat" >
        <?php 
        $vats = DB::table('vat_statuses')->get();
        ?>
        @foreach($vats as $vat)
        <option value="{{$vat->code}}">{{$vat->code}} ({{$vat->status}})</option>
        @endforeach
        </select>
			</div>

      <div class="col-md-12">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button class="btn btn-primary" style="float:right" id="submitEditDataBtn">Submit</button>


      </div>
    
  


    </div>
		</form>

      </div>
     
    </div>
  </div>
</div>
</section>






<section>
<!-- Modal -->
<div class="sweet-modal-container ">
  <div class="modal fade sweet-modal " id="deleteDataModal" tabindex="-1" role="dialog" aria-labelledby="sweet-modal-label" aria-hidden="true" data-backdrop="static">
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






<section decription="Modal for app data info">
<div class="modal fade-scale" tabindex="-1" role="dialog" id="csvDataModal" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title"><i class="fa fa-cloud-upload" style="color:white"></i> Upload csv </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group" id="csvdiv">
             To upload a csv prepare an excel file as shown below and save it as csv. 
             For <strong>Order price</strong> collumn if you dont have  data you can leave it blank but the first row should contain headers for all the collumns and in that order.
             <img src="system/images/csvsample.png" alt="" style="width:100%;margin-top:5px">
             <br> <br>
             <input type="file"  accept=".csv" id="csvinput" >
             <button style="float:right;margin-top:5px;border:1px solid gray" id="uploadcsvbtn">Submit</button>
            </div>

            <div class="form-group" id="uploaddiv" style="display:none">
            @if($supId==0 || $supId == "NA")
           <p>You can not upload data when selected supplier is <strong>All</strong> or <strong>Not defined</strong>, please select a specific supplier and try again.</p>
           @else
            <h3 style="text-align:center">
              <button class="btn btn-info" id="submitCsvDataBtn">Click here to upload <span id="uploadcount" style="font-weight:bold">0</span> rows</button>
            </h3>
            @endif
            </div>
      </div>
    </div>
  </div>
</div>
</section>







<section>
<!--Edit Modal-->
  <div class="modal fade " id="csv-modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header btn-primary">
              <h4 class="modal-title">Baseproducts csv upload</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">



        


          
           
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.Edit modal -->
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

  $('#uploadCsvBtn').click(function() {
    $('#newDataModal').modal('show');
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
         url: '/insert-wholesale-baseproduct',
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

      $('#editproduct').val($(this).attr('editproduct'));

      $('#editsupplier').val($(this).attr('editsupplier'));

      $('#editunit').val($(this).attr('editunit'));
    
      $('#editorderprice').val($(this).attr('editorderprice'));

      $('#editsellingprice').val($(this).attr('editsellingprice'));

      $('#editbatchnumber').val($(this).attr('editbatchnumber'));

      $('#editexpirydate').val($(this).attr('editexpirydate'));

      $('#editvat').val($(this).attr('editvat'));

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
      url: '/delete-wholesale-baseproduct',     
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
         url: '/edit-wholesale-baseproduct',
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



      
 
$('#business-table').DataTable({ 
     dom: 'Bfrtip', 
     autoWidth:false,
     paging: true,
     buttons: [
     {
      extend: 'excel',
      title: 'Wholesale baseproducts',
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },
    
    {
      extend: 'pdf',
      title: 'Wholesale baseproducts',
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
     

    }
  
  ]
 }); 


 

 $('#newDataBtn').click(function() {
    $('#csvDataModal').modal('show');
  });

 
$('body').on('click', '#uploadcsvbtn', function () {
  const csvFile  = document.getElementById("csvinput");
  const finalArray = [];
  if(csvFile.files.length == 0 ){ 
       toastr.error('An error occured, please select a csv file from your device storage to upload');
    } 
else{
  Papa.parse(csvFile.files[0], { 
	header: true,
	complete: function(results) {
    localStorage.csvData = JSON.stringify((results.data));  
          dataLength  =JSON.parse(localStorage.csvData)
          if(dataLength.length>0){
            document.getElementById("csvdiv").style.display="none";
            document.getElementById("uploaddiv").style.display="block"
            document.getElementById("uploadcount").innerHTML = dataLength.length-1;
          }
      }
    });
}
 });



 $(document).on("click", "#submitCsvDataBtn", function(e) {
var self = $(this);
$(this).prop("disabled", true);

var data1 = [];
if (localStorage.csvData) {
    data1 = JSON.parse(localStorage.csvData);
}
data = JSON.stringify(data1);

var mergedArray = [];
e.preventDefault();

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$.ajax({
    type: "post",
    url: '/upload-wholesale-baseproducts-csvfile',
    data: { data: data },
    timeout: 60000,
    beforeSend: function() {
        $('#loading-status').css('display', 'block');
    },
    complete: function() {
        $('#loading-status').css('display', 'none');
        $("#tbody").load(" #tbody > *", function() {});
        self.prop("disabled", false);
        form.reset();
    },
    success: function(response) {
          if (response.success) {
              if(response.imported == 0){
                  toastr.info('All the products that you are trying to import already exist in the database','Validation Information' ,{timeOut: 5000 , progressBar: true});
              } else {
                  toastr.success(response.imported + ' Records imported','Import successful' ,{timeOut: 5000 , progressBar: true});
              }
          } else {
              toastr.error('Technical error occured contact system developers ','Technical Error',{timeOut: 5000 , progressBar: true});
          }
      },

    error: function(xhr, status, error) {
    if (status === 'timeout') {
        toastr.error('The request took too long to process. Please try again.','Timeout Error',{timeOut: 5000 ,	progressBar: true});
    } else if (status === 'error' && xhr.status === 0) {
        toastr.error(' Please check your internet connection and try again',' Connection Error',{timeOut: 5000 ,	progressBar: true});
    } else if (xhr.status === 500) {
        toastr.error('Server error occurred. Contact system administrator.','Server Error',{timeOut: 5000 ,	progressBar: true});
    } else {
        toastr.error('Unspecified error occurred. Please refresh the page and try again.','Unspecified',{timeOut: 5000 ,	progressBar: true});
    }
}





});

});

function clearcsvData(){

  
if(localStorage.csvData){

  window.localStorage.removeItem('csvData');
  
}



}

function onLoadFunctions(){
  clearcsvData();
}


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

