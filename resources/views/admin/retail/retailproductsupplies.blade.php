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
<h4>
<?php
   use Carbon\Carbon;
   $branchId = Cookie::get('rbranch') ?? "NA";
   $productId = Cookie::get('rproduct') ?? "NA";
   $branchName = '';
   $categoryName = '';
   $categoryId = DB::table('branches')->where('id',$branchId)->value('category');
   if(is_numeric($branchId)){ 
     $branchName = DB::table('branches')->where('id',$branchId)->value('branch');
     $categoryName = DB::table('businesscategories')->where('id',$categoryId)->value('category'); 
     }
     else{
       $branchName = 'Branch not defined';   
     }
    
      $date = Cookie::get('rdate') ?? "Date not defined";

      $data = DB::table('retaildeliverynotes')->where('branchid',$branchId)->where('date',$date)->get();
      $data2 = DB::table('retailproducthistory')->where('branchid',$branchId)->where('date',$date)->get();
      $dnotevalue = 0;
      foreach ($data as $row) {
        $dnotevalue += $row->quantity * $row->price;
      }
    

      $dvalue = is_numeric($dnotevalue) ? $dnotevalue : 0; 
      $dvalue = number_format($dvalue, 0); 

     $title1 = $branchName." Deliverynote ".$date." (MWK".$dvalue.")";
     $title2 = $branchName." | Product logs (".$date.")";

     $lossvalue =0;
     $addedvalue =0;

     $losshistory=DB::table('retailproducthistory')
                ->where('branchid',$branchId)
                ->where('date',$date)
                ->where('qtyadded','<',0)->get();
     $addedhistory=DB::table('retailproducthistory')
                ->where('branchid',$branchId)
                ->where('date',$date)
                ->where('qtyadded','>',0)->get();


      foreach ($losshistory as $row) {
        $price = DB::table('retailbaseproducts')->where('id', $row->productid)->value('sellingprice');
        $lossvalue += $row->qtyadded * $price;
      }
      
      foreach ($addedhistory as $row) {
        $price = DB::table('retailbaseproducts')->where('id', $row->productid)->value('sellingprice');
        $addedvalue += $row->qtyadded * $price;
      }




 ?>

  
<i class="bx bx-calendar" style="font-weight:bold;color:gray;"></i>
<select  id="" style=";border:none;margin-left:-4px" onchange="submitBranchId(this.value)">
<option value="" hidden>{{$branchName}}</option>
<?php
$branches = DB::table('branches')->where('sector','Retail')->get();
?>
@foreach($branches as $branch)
<option value="{{$branch->id}}">{{$branch->branch}}</option>
@endforeach
</select>


<a href="#" class="btn btn-primary" id="dateBtn" style="float:right">
    <i class="fa fa-edit" style="color:white"></i>Date
</a>

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
<span style="font-size:15px">Retail branch supplies <strong> ( {{$date}} )</strong> </span>
</div>
<div class="card-body">

<!-----------start tabs ---------------->

<ul class="nav nav-pills mb-3" role="tablist"> 
        <li class="nav-item" role="presentation"> 
            <a class="nav-link active" data-bs-toggle="pill" href="#primary-pills-supplies" role="tab" aria-selected="true"> 
                <div class="d-flex align-items-center"> 
                    <div class="tab-icon">  <i class='bx bx-menu font-18 me-1'></i> </div> 
                    <div class="tab-title">Supplies</div> 
                </div> 
            </a> 
        </li> 
        <li class="nav-item" role="presentation"> 
            <a class="nav-link" data-bs-toggle="pill" href="#primary-pills-logs" role="tab" aria-selected="false"> 
                <div class="d-flex align-items-center"> 
                    <div class="tab-icon"><i class='bx bx-calendar font-18 me-1'></i> </div> 
                    <div class="tab-title">Logs</div> 
                </div> 
            </a> 
        </li> 
    </ul>

	<div class="tab-content" id="pills-tabContent">

  <div class="tab-pane fade show active" id="primary-pills-supplies" role="tabpanel">
     
          <div>
            <a href="#" class="btn" style="margin-left:-10px" disabled>
              <i>Value : @convert($dnotevalue)</i>
            </a>
            <a href="#" class="btn" disabled>
              <i>With selected:</i>
            </a>
            <a href="#" class="btn text-warning">
              <i class="fa fa-undo"></i> Unsubmit
            </a>
            <a href="#" class="btn text-primary">
              <i class="fa fa-calendar"></i> Change date
            </a>
            <a href="#" class="btn text-secondary">
              <i class="fa fa-building"></i> Change branch
            </a>
            <a href="#" class="btn text-success">
            <i class="fa fa-check"></i> Submit
            </a>
            <a href="#" class="btn text-danger">
              <i class="fa fa-trash"></i> Delete
            </a>
          </div>

          <div class="table-wrapper">
          <table id="retailsupplies-table" class="table table-striped-column  table-sm table-striped table-fixed-first-column table-fixed-header" >
          <thead class="table-dark">
          <tr>
          <th class="table-dark">
          <input type="checkbox" class="selectall"> Product</th>
          <th style="text-align:center">Unit</th>
          <th style="text-align:center">Quantity</th>
          <th style="text-align:center">Price</th>
          <th style="text-align:center">Total</th>
          <th style="text-align:center">Submitted</th>
          <th style="text-align:center">Action</th>
          </tr>
          </thead>
          <tbody id="tbody">
          @foreach($data as $d)
          <?php
          $editrow = "editrow".$d->id;
          ?>
          <tr id="{{$editrow}}">
            <td ><input type="checkbox" name="select" class="select"> {{$d->productname}}</td>
            <td style="text-align:center">{{$d->unit}}</td>
            <td style="text-align:center">{{$d->quantity}}</td>
            <td style="text-align:center">@convert($d->price)</td>
            <td style="text-align:center;">@convert($d->quantity*$d->price)</td>
            <td style="text-align:center">{{$d->added_to_branch}}</td>

            <td style="text-align:center">
            <a href="#" class="editDataBtnClass" 
              editId ="{{$d->id}}"
              editRow="{{$editrow}}"
              editproduct="{{$d->productname}}" 
              editunit="{{$d->unit}}"
              editquantity="{{$d->quantity}}"  
              editprice="{{$d->price}}"
              > 
              <i class="fa fa-edit text-primary fa-2x" ></i>
              </a>
              <a href="#" class="deleteDataBtnClass" deleteLabel="{{$d->productname}}"  deleteId="{{$d->id}}" deleteRow="{{$editrow}}">
                <i class="fa fa-trash text-danger fa-2x"></i>
              </a>
            </td>
          </tr>
          @endforeach
          </tbody>
          </table>
          </div>

    </div>

  <div class="tab-pane fade" id="primary-pills-logs" role="tabpanel">  
        <div>
          <a href="#" class="btn" style="margin-left:-10px" disabled>
            <i>Loss value : @convert($lossvalue)</i>
          </a>
          <a href="#" class="btn" disabled>
            <i>Added value : @convert($addedvalue)</i>
          </a>

          <a href="#" class="btn" disabled>
            <i>With selected :</i>
          </a>

          <a href="#" class="btn text-warning">
            <i class="fa fa-undo"></i> Reverse
          </a>
          
          <a href="#" class="btn text-danger">
            <i class="fa fa-trash"></i> Delete
          </a>

        </div>
  
        <div class="table-wrapper">
        <table id="retaillogs-table" class="table table-striped-column  table-sm table-striped table-fixed-first-column table-fixed-header" >
        <thead class="table-dark">
        <tr>
        <th class="table-dark">
        <input type="checkbox" class="selectall">Product</th>
        <th style="text-align:center">QtyBefore(QB)</th>
        <th style="text-align:center">QtyAfrer(QA)</th>
        <th style="text-align:center">Diff(QA-QB)</th>
        <th style="text-align:center">Description</th>
        <th style="text-align:center">Action</th>
        </tr>
        </thead>
        <tbody id="tbody">
        @foreach($data2 as $log)
        <?php
        $editrow = "editrowlogs".$log->id;
        ?>
        <tr id="{{$editrow}}">
          <td ><input type="checkbox" name="select" class="select">
            <?php
            $logProduct =DB::table('retailbaseproducts')->where('id',$log->productid)->value('product');
            $logUnit =DB::table('retailbaseproducts')->where('id',$log->productid)->value('unit');
            $logPrice =DB::table('retailbaseproducts')->where('id',$log->productid)->value('sellingprice');
            ?>
            {{$logProduct}} <span style="color:	 #b3b3b3">[ @convert($logPrice) | {{$logUnit}} ]</span> 
            </td>
          <td style="text-align:center">{{$log->qtybefore}}</td>
          <td style="text-align:center">{{$log->qtyafter}}</td>
          <td style="text-align:center">{{$log->qtyadded}}</td>
          <td style="text-align:center">{{$log->description}} <span style="color:#b3b3b3" >{{$log->time}} by {{$log->username}}</span> </td>
          <td style="text-align:center">
          <a href="#" class="editDataBtnClass" 
            editId ="{{$d->id}}"
            editRow="{{$editrow}}"
            editproduct="{{$logProduct}}" 
            > 
            <i class="fa fa-edit text-primary fa-2x" ></i>
            </a>
            <a href="#" class="deleteDataBtnClass" deleteLabel="{{$logProduct}}"  deleteId="{{$d->id}}" deleteRow="{{$editrow}}">
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
<!-------------- end tabs ---------------------->

</div>
</div>
</div>
</section>
</div>
</div>

<section description="Modal for changing interval">
  <div class="modal fade-scale" tabindex="-1" role="dialog" id="dateModal" data-bs-backdrop="static">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Change date</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="select-rdate" method="post" id="date-form">
            @csrf
            <div class="form-group">
              <label for="">Select custom date</label>
              <input type="date" name="date" id="selected-date" class="form-control" value="{{$date}}">
              <button class="btn btn-primary" style="margin-top:15px;float:right">Submit</button>
              <br> <br> <br>
            
            </div>
            <div class="form-group">
              <label for="">Predefined dates: (Within last 124 days)</label>
              <hr>
              <div class="scrollable-container" style="overflow-x: auto; white-space: nowrap;">
                <?php
                  $dates = DB::table('retaildeliverynotes')
                    ->where('branchid', $branchId)
                    ->where('date', '>=', Carbon::today()->subDays(124))
                    ->pluck('date');
                  $dates2 = DB::table('retailproducthistory')
                    ->where('branchid', $branchId)
                    ->where('date', '>=', Carbon::today()->subDays(124))
                    ->distinct()
                    ->pluck('date');
                  $combinedDates = array_unique(array_merge($dates->toArray(), $dates2->toArray()));
                  rsort($combinedDates);
                ?>
                @foreach($combinedDates as $date)
                  <button class="btn btn-sm btn-secondary predefined-date" style="margin:5px">{{ $date }}</button>
                @endforeach
              </div>
              
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $('.predefined-date').click(function() {
    var selectedDate = $(this).text();
    $('#selected-date').val(selectedDate);
    $('#date-form').submit();
  });
</script>






<script src="Admin320/plugins/jquery/jquery.min.js"></script>
<script src="Admin320/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="Admin320/plugins/toastr/toastr.min.js"></script>
<script>
  $('#toggle-predefined-dates').click(function() {
    $('#predefined-dates-container').toggle();
  });

  $('.btn-secondary').click(function() {
    var selectedDate = $(this).text();
    $('input[name="date"]').val(selectedDate);
  });
</script>
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


  
$('#retailsupplies-table').DataTable({ 
     dom: 'Bfrtip', 
     autoWidth:false,
     paging: true,
     buttons: [

      {
      extend: 'copy',
      title: @json($title1),
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },

     {
      extend: 'excel',
      title: @json($title1),
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },
    
    {
      extend: 'pdf',
      title: @json($title1),
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
      title: @json($title1),
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },
  
  ]
 }); 

 
  
$('#retaillogs-table').DataTable({ 
     dom: 'Bfrtip', 
     autoWidth:false,
     paging: true,
     pageLength: -1,
     lengthChange: false,
     buttons: [

      {
      extend: 'copy',
      title: @json($title2),
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },

     {
      extend: 'excel',
      title: @json($title2),
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },
    
    {
      extend: 'pdf',
      title: @json($title2),
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
      title: @json($title2),
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },
  
  ]
 }); 





  $('body').on('click', '.insertDataBtn', function(e) {
      var self = $(this);
      var formid = $(this).attr('form');
      var row =  $(this).attr('row');
      $(this).prop("disabled", true);
      var form = document.getElementById(formid);

      e.preventDefault(); 
      
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        type:"post",
         url: '/insert-wholesale-branch-product',
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
            self.css('color','red')
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
      url: '/delete-wholesale-branch-product',     
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
         url: '/update-wholesale-branch-product',
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

