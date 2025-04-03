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


<?php
   
    $branchId = Cookie::get('rbranch') ?? "NA";
    $branchName = '';
    $categoryName = '';
  
    $categoryId = DB::table('branches')->where('id',$branchId)->value('category');
    $sectorName = DB::table('branches')->where('id',$branchId)->value('sector');


    $supplierArray = DB::table('suppliers')->where('sector','Retail')->where('category',$categoryId)->pluck('id');

    if(is_numeric($branchId)){
       
      $branchName = DB::table('branches')->where('id',$branchId)->value('branch');
      $categoryName = DB::table('businesscategories')->where('id',$categoryId)->value('category');
      
      }
      else{
  
        $branchName = 'Branch not defined';
            
      }

      use Carbon\Carbon;
  ?>

<h4>
<i class="bx bx-building" style="font-size:20px" ></i>
<select name="category" id="" style=";border:none;margin-left:-4px;font-size:20px" onchange="submitBranchId(this.value)">
<option value="" hidden>{{$branchName}}</option>
<?php
$branches = DB::table('branches')->where('sector','Retail')->get();
?>
@foreach($branches as $branch)
<option value="{{$branch->id}}">{{$branch->branch}}</option>
@endforeach
</select>
   




<a href="#" class="btn btn-primary" id="uploadCsvBtn"style="float:right" title="Add new product"> 
    <i class="feather icon-plus-circle" style="color:white"></i>  Add 
</a>


<a href="#" class="btn btn-primary"  id="infoBtn"   style="float:right;margin-right:10px" title="view more info">
    <i class="feather icon-info"></i> Info
</a> 


<!--<a href="#" class="btn btn-primary"  id="newDataBtn"   style="float:right;margin-right:10px" title="Download deliverynotes">
    <i class="feather icon-file"></i>
</a> -->




<!--<a href="#" class="btn btn-primary"  id="newDataBtn"   style="float:right;margin-right:10px" title="Choose action for selected products">
    <i class="fa fa-check"></i> <counter>0</counter>
</a> -->

 

<script> 
    function submitBranchId(value) {
        document.getElementById('branchId').value = value;
        document.getElementById('branchForm').submit();
    }
</script>
<form action="select-rbranch" method="post" id="branchForm">
  @csrf
  <input type="hidden" name="branch" id="branchId">
 </form>


</h4>
<span style="font-size:14px;">
Manage retail branch inventory  <span style="color:gray">[{{$categoryName}}]</span>
</span>

</div>

<div class="card-body" style="margin-top:5px">
<div class="table-wrapper">
<table id="retailinventory-table" class="table  table-striped-column  table-sm table-striped table-fixed-first-column table-fixed-header" >
<thead class="table-dark">
<tr>
<th class="table-dark">
<input type="checkbox" class="selectall"> Product</th>
<th style="text-align:center">Unit</th>
<th style="text-align:center">Quantity</th>
<th style="text-align:center">Price</th>
<th style="text-align:center">Rate</th>
<th style="text-align:center">Batch</th>
<th style="text-align:center">Expiry</th>
<th style="text-align:center">Status</th>
<th style="text-align:center">VAT</th>
<th style="text-align:center">Action</th>
</tr>
</thead>
<?php
$data = DB::table('retailbranchproducts')->where('branch',$branchId)->get();
?>
<tbody id="tbody">
@foreach($data as $d)
<?php
 $editrow = "editrow".$d->id;
 $productName = DB::table('retailbaseproducts')->where('id', $d->product)->value('product');
 $productUnit = DB::table('retailbaseproducts')->where('id', $d->product)->value('unit');
 $productPrice = DB::table('retailbaseproducts')->where('id', $d->product)->value('sellingprice');
 $vat = DB::table('retailbaseproducts')->where('id', $d->product)->value('vat');
 $price = round($productPrice*$d->rate, -2)
 ?>
<tr id="{{$editrow}}">
   <td ><input type="checkbox" name="select" class="select"> {{$productName}}</td>
   <td style="text-align:center">{{$productUnit}}</td>
   <td style="text-align:center">{{$d->quantity}}</td>
   <td style="text-align:center">@convert($price)</td>
   <td style="text-align:center;color:gray">{{$d->rate}}</td>

    <td style="text-align:center">
    @if($d->batchnumber)
    {{$d->batchnumber}}
    @else
    <span style="color:gray">NA</span>
    @endif
    </td>
    <td style="text-align:center">
    @if($d->expirydate)
    {{$d->expirydate}}
    @else
    <span style="color:gray">NA</span>
    @endif
    </td>
    <td style="text-align:center">{{$d->status}}</td>
   <td style="text-align:center">{{$vat}}</td>
	 <td style="text-align:center">
	 <a href="#" class="editDataBtnClass" 
    editId ="{{$d->id}}"
    editRow="{{$editrow}}"
    editproduct="{{$productName}}" 
    editunit="{{$productUnit}}" 
    editprice="{{$price}}"
    editquantity="{{$d->quantity}}" 
    editbatchnumber="{{$d->batchnumber}}" 
    editexpirydate="{{$d->expirydate}}"
    editstatus="{{$d->status}}"
    editshelfnumber="{{$d->snumber}}"
    > 
    <i class="fa fa-edit text-primary fa-2x" ></i>
    </a>
		<a href="#" class="deleteDataBtnClass" deleteLabel="{{$productName}}"  deleteId="{{$d->id}}" deleteRow="{{$editrow}}">
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


<section>
  <div class="modal fade card-info"  id="newDataModal" data-bs-backdrop="static">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header ">
        <h4 class="modal-title">Add product for {{$branchName}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

      </div>
      <div class="modal-body">

          <?php
             $data = DB::table('retailbaseproducts')->whereIn('supplier',$supplierArray)->get();
             ?>

                <div class="row">
                <div class="col-sm-12">
                <label>Search a product you want to add</label>
                <div class="input-group input-group-button">

                <input type="text" class="form-control" autocomplete="off" style="width:80%;border:1px solid #8c8c8c;text-align:left;"  id="mobile-search" >
              
                
                <!--<button style="border:1px solid #8c8c8c"  id="cancelsearch" >Cancel</button>-->
               
              </div>
                </div>
                </div>

          <div class="row">
          <div class="col-md-12">
     
          </div>
          </div>

        <table class="table-sm table mobile-table " style="display:none;font-size:14px" id="mobile-table">
        <thead>
        <tr style="border-top:none">
        <th style="border-top:none;border-bottom:none;font-weight:bold">Item Description</th>
        <th style="text-align:center;border-top:none;border-bottom:none;font-weight:bold">Action</th>
        </tr>
        </thead>
        <tbody >
        @foreach($data as  $d)
        <tr>
        <td>{{$d->product}} &nbsp;
        <strong>@convert($d->sellingprice)</strong> / {{$d->unit}}
        </td>
        <?php
        $btnrow = "row".$d->id;
        $formid = "form".$d->id;
        ?>
        <td style="margin-align:center">
        <form  action="insert-retail-branch-product"  id="{{$formid}}"  class="cart-forms"  method="post">
        @csrf

            <input type="hidden" name="productid"  value="{{$d->id}}"> 
            <input type="hidden" name="branch"  value="{{$branchId}}"> 
              <div class="input-group-append" style="font-size:10px">
            <input type="text" class="form-control insertDataBtn" name = "quantity" style="width:70%;border:1px solid #8c8c8c;text-align:center;" autocomplete="off" form="{{$formid}}"  row="{{$btnrow}}" id="{{$btnrow}}">
            <!--<button class="btn insertDataBtn" style="border:1px solid #8c8c8c" form="{{$formid}}"  row="{{$btnrow}}" id="{{$btnrow}}">Add</button>-->
  

        </form>
        </td>

        </tr>
        @endforeach
        </tbody>

          </table>

        
          </div>
            
            </div>

          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.excel modal -->
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
		   <form action="delete-wholesale-branch-product" method="post" id="deleteForm">
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
<div class="modal fade-scale" tabindex="-1" role="dialog" id="editDataModal" data-bs-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header ">
        <h5 class="modal-title">Edit product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		<form action="#" method="post"   id="editDataForm">
			@csrf
      <input type="hidden" id="editId" name="id">
      <input type="hidden" id="editRow">

      
		<div class="row">

			<div class="form-group col-md-6">
				<label for="#">Product Name</label>
				<input type="text"name="product" class="form-control" id="editproduct" readonly>
			</div>





			<div class="form-group col-md-6">
				<label for="#">Unit</label>
				<input type="text" name="unit" class="form-control" id="editunit" readonly>
			</div>



			<div class="form-group col-md-6">
				<label for="#">Price</label>
				<input type="number" name="orderprice" class="form-control" id="editprice" readonly>
			</div>

      

      
      
			<div class="form-group col-md-6">
				<label for="#"><strong>Quantity</strong> </label>
				<input type="number" name="quantity" class="form-control" id="editquantity">
			</div>


      <hr style="margin-top:10px;margin-top:20px">
      
			<div class="form-group col-md-6">
				<label for="#">Batch Number</label>
				<input type="text" name="batchnumber" class="form-control" id="editbatchnumber">
			</div>

      
			<div class="form-group col-md-6">
				<label for="#">Expiry Date</label>
				<input type="date" name="expirydate" class="form-control" id="editexpirydate">
			</div>


      
      
			<div class="form-group col-md-6">
				<label for="#">Status</label>
			  <select name="status" class="form-control" id="edtitstatus">
          <option value="Active">Active (Able to sale)</option>
          <option value="Locked">Locked (Not able to sale)</option>
        </select>
			</div>



      
			<div class="form-group col-md-6">
				<label for="#">Shelf Number</label>
				<input type="text" name="shelfnumber" class="form-control" id="editshelfnumber">
			</div>

      <div class=" foem-group col-md-12">
        <label for="#">Decription (For qty change)</label>
        <textarea class="form-control" name="description" id="#">NA</textarea>
      </div>




		</form>

      </div>
    </div>


    <div class="modal-footer">
    <button class="btn btn-primary" style="float:right" id="submitEditDataBtn">Submit</button>
    </div>


  </div>
</div>
</section>




<section decription="Modal for app data info">
<div class="modal fade-scale" tabindex="-1" role="dialog" id="infoModal" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Branch details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <?php
            $productlist = DB::table('retailbranchproducts')->where('branch',$branchId)->get();
            $shopvalue = 0;
            foreach($productlist as $list){
              $price1 = DB::table('retailbaseproducts')->where('id',$list->product)->value('sellingprice');
              $price2 = round($price1*$list->rate, -2);
              $shopvalue = ($list->quantity*$price2) + $shopvalue;
            } 
            ?>
          <div style="margin-top:-10px">
        <span style="display:inline-block;font-size:15px;padding:5px">Name</span> : <span style="font-weight:bold">{{$branchName}}</span>  <br>
        <span style="display:inline-block;font-size:15px;padding:5px">Sector</span> : <span style="font-weight:bold">{{$sectorName}}</span>  <br>
        <span style="display:inline-block;font-size:15px;padding:5px">Category</span> : <span style="font-weight:bold">{{$categoryName}} </span> <br>
        <span style="display:inline-block;font-size:15px;padding:5px">Shopvalue</span> : <span style="font-weight:bold">@convert($shopvalue)</span>  <br>
        <span style="display:inline-block;font-size:15px;padding:5px">Date</span> : <span style="font-weight:bold">{{Carbon::today()->toDateString()}}</span>  <br>      </div>
      </div>
    </div>
  </div>
</div>
</section>











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

 $('#newDataBtn').click(function() {
    $('#csvDataModal').modal('show');
  });


  $('#infoBtn').click(function() {
    $('#infoModal').modal('show');
  });

  $('#retailinventory-table').DataTable({ 
     dom: 'Bfrtip', 
     autoWidth:false,
     paging: true,
     pageLength: -1,
     lengthChange: false,
     buttons: [

      {
      extend: 'copy',
      title: 'Retail inventory',
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },

     {
      extend: 'excel',
      title: 'Retail inventory',
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },
    
    {
      extend: 'pdf',
      title: 'Retailinventory',
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
      title: 'Retail inventory',
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },
  
  ]
 }); 


  $('body').on('change', '.insertDataBtn', function(e) {
      var self = $(this);
      var formid = $(this).attr('form');
      var row =  $(this).attr('row');
      //$(this).prop("disabled", true);
      var form = document.getElementById(formid);

      e.preventDefault(); 
      
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        type:"post",
         url: '/insert-retail-branch-product',
         data: $(form).serialize(),
          timeout: 60000,
          beforeSend: function() {
            $('#loading-status').css('display', 'block');
          },
          complete: function() {
            $('#loading-status').css('display', 'none');
            $("#tbody").load(" #tbody  > *",function(){});
            form.reset();
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
            self.css('border','1px solid blue')
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
         form.reset();
          }  
        });
      });



	  

      $('#tbody').on('click', '.deleteDataBtnClass', function() {
      $('#deleteInputId').val($(this).attr('deleteId'));  
      $('#displayDeleteItem').html($(this).attr('deleteLabel'));
      $('#deleteInputRow').val($(this).attr('deleteRow'));
      $('#deleteDataModal').modal('show');
      })

      
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
      url: '/delete-retail-branch-product',     
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


  

  

$('#tbody').on('click', '.editDataBtnClass', function() {

$('#editId').val($(this).attr('editId'));

$('#editRow').val($(this).attr('editRow'));

$('#editproduct').val($(this).attr('editproduct'));

$('#editunit').val($(this).attr('editunit'));

$('#editprice').val($(this).attr('editprice'));

$('#editquantity').val($(this).attr('editquantity'));


$('#editbatchnumber').val($(this).attr('editbatchnumber'));

$('#editexpirydate').val($(this).attr('editexpirydate'));


$('#editstatus').val($(this).attr('editstatus'));

$('#editshelfnumber').val($(this).attr('editshelfnumber'));

$('#editDataModal').modal('show');
});





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
         url: '/update-retail-branch-product',
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





})
</script>

<script>
$(document).ready( function () {
 var table = $("#mobile-table").DataTable({
          "paging": false,
        "bInfo" : false,
        "ordering":false,
  dom: 'lrtip'
  })
  $('#mobile-table').hide();
  $('#mobile-search').keyup( function() {
    var value = document.getElementById('mobile-search').value;
    if (value.length<2) {
      $('#mobile-table').hide();
    }else{
      $('#mobile-table').show();
     table.search($(this).val()).draw();
    }
  } );
} );
$('body').on('click', '#mobile-search', function () {
  $('#mobile-search').val('');
  $('#mobile-table').hide();
});
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

