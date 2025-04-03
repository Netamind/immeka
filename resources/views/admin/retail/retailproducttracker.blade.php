    
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

<?php
   
    $branchId = Cookie::get('rbranch') ?? "NA";
    $productId = Cookie::get('rproduct') ?? "NA";
    $branchName = '';
    $categoryName = '';
    $categoryId = DB::table('branches')->where('id',$branchId)->value('category');

    $supplierArray = DB::table('suppliers')->where('sector','Retail')->where('category',$categoryId)->pluck('id');

    if(is_numeric($branchId)){
       
      $branchName = DB::table('branches')->where('id',$branchId)->value('branch');
      $categoryName = DB::table('businesscategories')->where('id',$categoryId)->value('category');
      
      }
      else{
  
        $branchName = 'Branch not defined';
            
      }
      $productName = DB::table('retailbaseproducts')->where('id',$productId)->value('product') ?? "NA";

      $startdate = Cookie::get('startdate') ?? "(Start date not defined)";
      $enddate = Cookie::get('enddate') ?? "(End date not defined)";


      $title = $branchName." | ".$productName." logs "." from ".$startdate." to ".$enddate;

  ?>

<section>
<div class="card">
<div class="card-header">
<h4>
  
<i class="bx bx-calendar" style="font-weight:bold;color:gray;"></i>
<select name="category" id="" style=";border:none;margin-left:-4px" onchange="submitBranchId(this.value)">
<option value="" hidden>{{$branchName}}</option>
<?php
$branches = DB::table('branches')->where('sector','Retail')->get();
?>
@foreach($branches as $branch)
<option value="{{$branch->id}}">{{$branch->branch}}</option>
@endforeach
</select>


<a href="#" class="btn btn-primary" id="intervalBtn" style="float:right">
    <i class="fa fa-edit" style="color:white"></i>Interval
</a>

<a href="#" class="btn btn-primary" id="productBtn" style="float:right;margin-right:10px">
    <i class="fa fa-edit" style="color:white"></i>Product
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
<span style="font-size:16px;">
 Transaction log for <strong>{{$productName}}</strong>  between <strong>{{$startdate}}</strong>  and <strong>{{$enddate}}</strong> .
</span>
</div>
<div class="card-body"> 
<input type="hidden" value="{{$title}}" id="documentTitle">
<div class="table-wrapper">
<table id="logs-table" class="table-striped-column table table-sm table-striped table-fixed-first-column table-fixed-header" >
<thead class="table-dark">
<tr>
<th class="table-dark">Date</th>
<th style="text-align:center">QtyBefore(QB)</th>
<th style="text-align:center">QtyAfter(QA)</th>
<th style="text-align:center">Diff(QA-QB)</th>
<th style="text-align:center">User</th>
<th style="text-align:center">Description</th>
<th style="text-align:center">Action</th>
</tr>
</thead>
<tbody id="tbody">
<?php
$history  = DB::table('retailproducthistory')
            ->where('branchid',$branchId)
            ->where('productid',$productId)
            ->where('date','>=',$startdate)
            ->where('date','<=',$enddate)
            ->get();
?>
@foreach($history as $d)
<?php $row = "row".$d->id;?>
<tr id="{{$row}}">
   <td title="{{$d->devicedetails}}">{{$d->date}}</td>
   <td style="text-align:center" >{{$d->qtybefore}}</td>
   <td style="text-align:center" >{{$d->qtyafter}}</td>
   <td style="text-align:center" >{{$d->qtyadded}}</td>
   <td style="text-align:center" >{{$d->username}}</td>
   <td style="text-align:center">{{$d->description}}</td>
   <td style="text-align:center">
   <a href="#" class="editDataBtnClass" 
    editId ="{{$d->id}}"
    editRow="{{$row}}"
    editdate="{{$productName}}" 
    editproduct="{{$productName}}" 
    editunit="{{0}}" 
    editprice="{{0}}"
    editqtybefore="{{$d->qtybefore}}" 
    editqtyafter="{{$d->qtyafter}}" 
    editqtyadded="{{$d->qtyadded}}"
    > 
    <i class="fa fa-edit text-primary fa-2x" ></i>
    </a>
		<a href="#" class="deleteDataBtnClass" deleteLabel="{{$productName}}"  deleteId="{{$d->id}}" deleteRow="{{$row}}">
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



<section decription="Modal for changing interval">
<div class="modal fade-scale" tabindex="-1" role="dialog" id="intervalModal" data-bs-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change interval</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="change-date-interval" method="post" id="newIntervalForm">
            @csrf
           
            <div class="form-group">
            <label for="">Start date</label>
            <input type="date" name="startdate" class="form-control" value="{{$startdate}}">
            </div>

            <div class="form-group">
            <label for="">End date</label>
            <input type="date" name="enddate" class="form-control" value="{{$enddate}}">
            </div>

        
      
      </div>
      <div class="modal-footer">
      <a href="#" class="btn btn-secondary" style="float:right"  id="cancelDataBtn">Cancel</a> 
      <button class="btn btn-primary" style="float:right"> Submit</button>
      </div>

      </form>

    </div>
  </div>
</div>
</section>


<section decription="Modal for changing product">
<div class="modal fade-scale" tabindex="-1" role="dialog" id="productModal" data-bs-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <?php
       $data = DB::table('retailbaseproducts')->whereIn('supplier',$supplierArray)->get();
      ?>
       <label>Search a product you want to select</label>
    <div class="input-group input-group-button">
     <input type="text" autocomplete="off" style="width:80%;border:1px solid #8c8c8c;text-align:left;"  id="mobile-search" ><button style="border:1px solid #8c8c8c"  id="cancelsearch" >Cancel</button>      
     </div>
      
      <table class="table-sm table mobile-table custom-strip" style="display:none;font-size:14px" id="mobile-table">
        <thead class="table-dark">
        <tr style="border-top:none">
        <th style="border-top:none;border-bottom:none;font-weight:bold;text-align:center;padding:4px;font-size:16px">Click on product to select</th>
        </tr>
        </thead>
        <tbody >
        @foreach($data as  $d)
        <tr>
        <td style="padding:3px">
           
        <form action="select-rproduct" method="post" >
          @csrf
          <input type="hidden" name="product" value="{{$d->id}}">
          <button style="width:100%;border:none;background:none;font-size:15px">
         {{$d->product}} &nbsp; @convert($d->sellingprice) / {{$d->unit}}
         </button>
           </form> 
        </td>
        </tr>
        @endforeach
        </tbody>

          </table>
        
      
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


  $('#intervalBtn').click(function() {
    $('#intervalModal').modal('show');
  });

  
  $('#productBtn').click(function() {
    $('#productModal').modal('show');
  });

  

  
  $('#cancelDataBtn').click(function() {
    document.getElementById('newIntervalForm').reset();
  });

 
$('#logs-table').DataTable({ 
     dom: 'Bfrtip', 
     autoWidth:false,
     paging: true,
     pageLength: -1,
     lengthChange: false,
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

