    
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

<div class="card-header">
    <?php 
    use Carbon\Carbon; 
    $branchId = Cookie::get('rbranch') ?? "NA";
    $date = Cookie::get('rdate') ?? "Date not defined";
    $disaplaydatey = Carbon::createFromFormat('Y-m-d', $date)->format('d F Y');
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
  
   
  
        $title= $branchName." | System sales ".$date;

    
    
    // Retrieve products for the current branch and date
    $products = DB::table('retailsales')->where('branch',  $branchId )->where('date',   $date )->orderBy('id', 'asc')->get(); 
    ?>
    
    <!-- Select all checkbox -->
    <input type="checkBox" id="selectAll">
    
    <!-- Branch selection dropdown -->
    <select onchange="selectBranch(this.value)" style="background-color:white;border:none;color:blue;padding-left:-20px">
        <option hidden>{{$branchName}}</option>
        <?php $getbra = DB::table('branches')->get(); ?>
        @foreach($getbra as $bras)
            <option value="{{$bras->id}}">{{$bras->branch}} </option>
        @endforeach
    </select>
    
    <!-- Hidden form for branch selection -->
    <form action="select-branch" method="post" style="display:none" id="braform">
        @csrf
        <input type="text" name="id" id="bra">
    </form>
    
    <!-- Script for branch selection -->
    <script>
        function selectBranch(id){
            document.getElementById('bra').value = id;
            document.getElementById('braform').submit();
        }
    </script>
    
    <!-- Action links -->
    <a href="#" style="float:right" id="reversebtn">
        <i class="fas fa-exchange-alt"></i> Reverse
    </a>
    <a href="#" style="float:right;margin-right:10px" id="changedatebtn">
        <i class="fa fa-edit"></i> Change date
    </a>
    <a href="#" style="float:right;margin-right:10px">
        <i class="fa fa-check-double"></i>
        <span id="checkcount" style="font-weight:bold">0</span>
    </a>
</div>
1005041976

<div class="card-body">

<div class="table-wrapper" >
<table id="roles-table" class="table-striped-column table table-sm table-striped table-fixed-first-column table-fixed-header" >
<tbody>
<thead class="table-dark" >
        <tr>
        <th class="table-dark">Product</th>
        <th style="text-align:center">Unit</th>
        <th style="text-align:center">Qty</th>
        <th style="text-align:center">rQty</th>
        <th style="text-align:center">Price</th>
        <th style="text-align:center">Total</th>
        <th style="text-align:center">Transid[20]</th>
        <th style="text-align:center">Time</th>
        
        </tr>
        </thead>
        <tbody id="tbody">
        @foreach($products  as $d)
        <?php
        $row  = "r".$d->id;
        ?>
        <tr id="{{$row}}" >
            <td> 
            <input type="checkbox" class="selectItems" data-id="{{$d->id}}">
            <a href="#" class="editdatabtn" style="color:black"
            id1 = "{{$d->id}}"
            id2 = "{{$d->product}}"
            id3 = "{{$d->unit}}"
            id4 = "{{$d->price}}"
            id5 = "{{$d->quantity}}"
            id6 = "{{$d->date}}"
            id7 = "{{$row}}"
            id8 = "{{$d->quantity}}"
            id9 = "{{$d->productid}}"
            id10 = "{{$braid}}"
            >
            {{$d->product}}
        
            </a>
            </td>
            <td style="text-align:center">{{$d->unit}}</td>
            <td style="text-align:center">@convert2($d->quantity)</td>
            <td style="text-align:center">@convert2($d->rquantity)</td>
            <td style="text-align:center">@convert($d->price)</td>
            <td style="text-align:center">@convert($d->quantity*$d->price)</td>
            <td style="text-align:center">{{$d->transid}}</td>
            <td style="text-align:center">{{$d->time}}</td>
        </tr>
        @endforeach
    
        
</tbody>
</table>
</div>


</div>
</div>
</section>



<section decription="Modal for app data info">
<div class="modal fade-scale" tabindex="-1" role="dialog" id="newDataModal" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Important Notice: Adding New Roles</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      As an administrator, you do not have the authority to create new roles within the system. The available roles are pre-defined and managed by our development team. If you identify a need for an additional role, please reach out to our development team. We will be happy to assist you in adding the necessary role to the system.
      <br> <br>
      </div>
    </div>
  </div>
</div>
</section>
</div>
</div>




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



 
$('#roles-table').DataTable({ 
     dom: 'Bfrtip', 
     autoWidth:false,
     paging: true,
     pageLength: -1,
     lengthChange: false,
     buttons: [

      {
      extend: 'copy',
      title: 'Roles',
      
    },

     {
      extend: 'excel',
      title: 'Roles',
     
    },
    
    {
      extend: 'pdf',
      title: 'Roles',
     
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
      title: 'Roles',
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

