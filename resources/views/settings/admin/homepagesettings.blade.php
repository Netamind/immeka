    
@extends('admin.dashboard')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" type="text/css" href="dashboard/files/assets/icon/feather/css/feather.css">
 <link rel="stylesheet" type="text/css" href="dashboard/files/assets/icon/font-awesome/css/font-awesome.min.css">


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
<h4><i class="fa fa-cog" style="font-weight:bold;color:gray"></i> Home page settings
<a href="#" class="btn btn-primary" id="newDataBtn" style="float:right"><i class="fa fa-info-circle" style="color:white"></i>Info</a>
</h4>
<span style="font-size:18px;">
These settings allow you to select the sectors you want to display on your homepage.
</span>
</div>
<div class="card-block">

<div class="table-wrapper">
<table id="settings-table" style="padding:2px" class="table-striped-column table table-sm table-striped table-fixed-first-column table-fixed-header" >
<thead class="table-dark">
<tr>
<th class="table-dark">Sector</th>
<th style="text-align:center">Status</th>
<th style="text-align:center">Action</th>
</tr>
</thead>
<tbody id="tbody">
<tr>
  <td>Retail</td>
  <td style="text-align:center">
   <?php
     $retailstatus = DB::table('adminhomepagesettings')->where('user',Auth::user()->id)->where('sector',"Retail")->value('status');
    ?>
    {{$retailstatus}}
  </td>
  <td style="text-align:center">
    
      <?php
      $checkretail = DB::table('adminhomepagesettings')->where('user',Auth::user()->id)->where('sector',"Retail")->value('status');
      ?>
      @if($checkretail=="Enabled")
      <form action="set-admin-homepage" method="post">
        @csrf
        <input type="hidden" name="sector" value="Retail">
        <input type="hidden" name="status" value="Disabled">
        <button class="btn btn-primary">Disable</button>
      </form>
      @else
      <form action="set-admin-homepage" method="post">
        @csrf
        <input type="hidden" name="sector" value="Retail">
        <input type="hidden" name="status" value="Enabled">
        <button class="btn btn-primary">Enable</button>
      </form>
      @endif
  </td>
</tr>
<tr>
  <td>Wholesale</td>
  <td style="text-align:center">
  <?php
    $wholesalestatus = DB::table('adminhomepagesettings')->where('user',Auth::user()->id)->where('sector',"Wholesale")->value('status');
    ?>
     {{$wholesalestatus}}
  </td>
  <td style="text-align:center">
    
      <?php
      $checkwholesale = DB::table('adminhomepagesettings')->where('user',Auth::user()->id)->where('sector',"Wholesale")->value('status');
      ?>
      @if($checkwholesale=="Enabled")
      <form action="set-admin-homepage" method="post">
        @csrf
        <input type="hidden" name="sector" value="Wholesale">
        <input type="hidden" name="status" value="Disabled">
        <button class="btn btn-primary">Disable</button>
      </form>
      @else
      <form action="set-admin-homepage" method="post">
        @csrf
        <input type="hidden" name="sector" value="Wholesale">
        <input type="hidden" name="status" value="Enabled">
        <button class="btn btn-primary">Enable</button>
      </form>
      @endif
   
  </td>
</tr>
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
        <h5 class="modal-title">Home page settings notice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
     Only sectors implemented in the system have enable disable feature
      <br> <br>
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


  
 
$('#settings-table').DataTable({ 
     dom: 'Bfrtip', 
     autoWidth:false,
     paging: true,
     buttons: [
     {
      extend: 'excel',
      title: 'Admin homepage settings',
      exportOptions: {
        columns: ':visible:not(:last-child)'
      }
    },
    
    {
      extend: 'pdf',
      title: 'Admin homepage settings',
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

