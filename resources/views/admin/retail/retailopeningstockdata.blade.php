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
    $date = Cookie::get('rdate') ?? "Date not defined";
    $branchName = '';
    $categoryName = '';
  
    $categoryId = DB::table('branches')->where('id',$branchId)->value('category');
    $sectorName = DB::table('branches')->where('id',$branchId)->value('sector');


    if(is_numeric($branchId)){
       
      $branchName = DB::table('branches')->where('id',$branchId)->value('branch');
      $categoryName = DB::table('businesscategories')->where('id',$categoryId)->value('category');
      
      }
      else{
  
        $branchName = 'Branch not defined';
            
      }

      $openingstock = DB::table('retailnewstocktaking')->where('branchid',$branchId)->where('date',$date)->sum(DB::raw('quantity*price'));


      $title= $branchName." | Openingstock ".$date." | MWK".$openingstock;
  ?>

<h4>
<i class="feather icon-home"></i>
<select name="category" id="" style=";border:none;margin-left:-4px" onchange="submitBranchId(this.value)">
<option value="" hidden>{{$branchName}}</option>
<?php
$branches = DB::table('branches')->where('sector','Retail')->get();
?>
@foreach($branches as $branch)
<option value="{{$branch->id}}">{{$branch->branch}}</option>
@endforeach
</select>
   





<a href="#" class="btn btn-primary"  id="submitOpeningstockToBranchBtn"   style="float:right;margin-right:10px" title="Submit opening stock to branch">
    <i class="fa fa-check"></i>Submit
</a> 


<a href="#" class="btn btn-primary"  id="dateBtn"   style="float:right;margin-right:10px" title="Change date">
    <i class="feather icon-edit"></i> Date
</a> 



<!--<a href="#" class="btn btn-primary"  id="infoBtn"   style="float:right;margin-right:10px" title="Read details">
    <i class="fa fa-info-circle"></i> 
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
Manage retail opening stock  | <strong> {{$date}}</strong> | MWK<strong>@convert($openingstock)</strong>
</span>


</div>

<div class="card-body" >

<div class="table-wrapper">
<table id="openingstockdata-table" class=" table table-striped-column  table-sm table-striped table-fixed-first-column table-fixed-header" >
<thead class="table-dark">
<tr>
<th class="table-dark">
<input type="checkbox" class="selectall"> Product</th>
<th style="text-align:center">Unit</th>
<th style="text-align:center">Price</th>
<th style="text-align:center">Quantity</th>
<th style="text-align:center">Action</th>
</tr>
</thead>
<?php
$data = DB::table('retailnewstocktaking')->where('branchid', $branchId)->where('date', $date)->orderBy('status', 'asc')->get();
?>
<tbody id="tbody">
@foreach($data as $d)
<?php
 $editrow = "editrow".$d->id;
 ?>
<tr id="{{$editrow}}">
   <td ><input type="checkbox" name="select" class="select"> {{$d->product}}</td>
   <td style="text-align:center">{{$d->unit}}</td>
   <td style="text-align:center">@convert($d->price)</td>
   <td style="text-align:center">{{$d->quantity}}</td>
	 <td style="text-align:center">
    @if($d->status=="Pending")
	 <a href="#" class="editDataBtnClass" 
    editId ="{{$d->id}}"
    editRow="{{$editrow}}"
    editproductid="{{$d->productid}}" 
    editproduct="{{$d->product}}" 
    editunit="{{$d->unit}}" 
    editprice="{{$d->price}}"
    editquantity="{{$d->quantity}}" 
    editstatus="{{$d->status}}"
    > 
    <i class="fa fa-edit text-primary fa-2x" ></i>
    </a>
		<a href="#" class="deleteDataBtnClass" deleteLabel="{{$d->product}}"  deleteId="{{$d->id}}" deleteRow="{{$editrow}}">
      <i class="fa fa-trash text-danger fa-2x"></i>
    </a>

    @else
    <a href="#">
    <i class="fa fa-ban  fa-2x" style="color: #ccc"></i>
    </a>
    @endif
	</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>
</section>

</div>
</div>

      

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
<div class="modal fade-scale" tabindex="-1" role="dialog" id="editDataModal" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title">Edit product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
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
				<label for="#">Quantity</label>
				<input type="number" name="quantity" class="form-control" id="editquantity">
			</div>


      
      
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



        <div class="col-md-12" style="margin-top:10px">   
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button class="btn btn-primary" style="float:right" id="submitEditDataBtn">Submit</button>
        </div>





		</form>

      </div>
    </div>
  </div>
</div>
</section>




<section description="Modal for changing date">
  <div class="modal fade-scale" tabindex="-1" role="dialog" id="dateModal" data-backdrop="static">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header ">
          <h5 class="modal-title">Change date</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="select-rdate" method="post" id="date-form">
            @csrf
            <div class="form-group">
              <label for="">Select custom date</label>
              <input type="date" name="date" id="selected-date" class="form-control" value="{{$date}}">
              
              <br>
            </div>
       
        </div>
        <div class="modal-footer">

        <button class="btn btn-primary" style="margin-top:10px;float:right">Submit</button>
        </div>

        </form>
      </div>
    </div>
  </div>
</section>





<section description="Modal for changing date">
  <div class="modal fade-scale" tabindex="-1" role="dialog" id="infoModal" data-backdrop="static">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title">Important notice</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
         
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


 
 $('#dateBtn').click(function() {
    $('#dateModal').modal('show');
  });


  $('#infoBtn').click(function() {
    $('#infoModal').modal('show');
  });



  

  
$('#openingstockdata-table').DataTable({ 
     dom: 'Bfrtip', 
     autoWidth:false,
     paging: true,
     buttons: [

      {
      extend: 'copy',
      title: @json($title),
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },

     {
      extend: 'excel',
      title: @json($title),
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },
    
    {
      extend: 'pdf',
      title: @json($title),
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
      title: @json($title),
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },
  
  ]
 }); 


 $(document).on("click", "#submitOpeningstockToBranchBtn", function(e) {
    var self = $(this);
    $(this).prop("disabled", true);
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "GET",
        url: '/submit-retail-openingstock-to-branch',
        timeout: 60000,
        beforeSend: function() {
            $('#loading-status').css('display', 'block');
        },
        complete: function() {
            $('#loading-status').css('display', 'none');
            self.prop("disabled", false);
        },
        success: function(response) {
            if (response.success) {
                if (response.imported > 0) {
                    toastr.success(response.imported + ' Records imported', 'Import successful', {
                        timeOut: 5000,
                        progressBar: true
                    });
                    $("#tbody").load(" #tbody > *", function() {});
                } else {
                    toastr.info('No data available for uploading', 'Validation Information', {
                        timeOut: 5000,
                        progressBar: true
                    });
                }
            } else {
                if (response.isEmpty) {
                    toastr.info('No pending stock data found', 'Validation Information', {
                        timeOut: 5000,
                        progressBar: true
                    });
                } else if (response.errors.length > 0) {
                  console.log(response.errors)
                    toastr.error('Error importing ' + response.errors.length + ' records', 'Import Error', {
                        timeOut: 5000,
                        progressBar: true
                    });
                } else {
                    toastr.error('Technical error occurred. Contact system developers', 'Technical Error', {
                        timeOut: 5000,
                        progressBar: true
                    });
                }
            }
        },
        error: function(xhr, status, error) {
            if (status === 'timeout') {
                toastr.error('The request took too long to process. Please try again.', 'Timeout Error', {
                    timeOut: 5000,
                    progressBar: true
                });
            } else if (status === 'error' && xhr.status === 0) {
                toastr.error('Please check your internet connection and try again', 'Connection Error', {
                    timeOut: 5000,
                    progressBar: true
                });
            } else if (xhr.status === 500) {
                toastr.error('Server error occurred. Contact system administrator.', 'Server Error', {
                    timeOut: 5000,
                    progressBar: true
                });
            } else {
                toastr.error('Unspecified error occurred. Please refresh the page and try again.', 'Unspecified', {
                    timeOut: 5000,
                    progressBar: true
                });
            }
        }
    });
})



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
$('body').on('click', '#cancelsearch', function () {
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

