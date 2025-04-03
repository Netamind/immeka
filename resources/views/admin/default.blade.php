<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<!--start page wrapper -->
<div class="page-wrapper">
<div class="page-content">

<?php
use Carbon\Carbon;
$branchArray = DB::table('branches')->where('sector','Retail')->pluck('id');
?>

 <div class="row">
	<div class="col-12 col-lg-8 col-xl-8 d-flex" >
		<!--style="height: 100%; display: flex; flex-direction: column;"-->
		<div class="card radius-10 w-100 " >

		<div class="card-header"  style="padding-bottom:0px">

			<div class="d-flex justify-content-between  align-items-center">
		     
			<button class="btn" style="padding-left: 0px; display: flex; align-items: center;">
             <i class="fa fa-bar-chart text-primary" style="font-weight:bold;font-size:15px"></i>
             <span>Sales</span>
             </button>

			
				<ul class="nav nav-pills mb-3" role="tablist">
					<li class="nav-item" role="presentation">
					<a class="nav-link active bg-primary" style="padding:2px;marigin-right:10px" data-bs-toggle="pill" href="#primary-pills-today" role="tab" aria-selected="true">
						<div class="d-flex align-items-center">
						<div class="tab-icon"><i class='bx bx-calendar-alt font-18 me-1'></i> </div>
						<div class="tab-title">Today</div>
						</div>
					</a>
					</li>
					<li class="nav-item" role="presentation">
					<a class="nav-link" style="padding:2px" data-bs-toggle="pill" href="#primary-pills-yesterday" role="tab" aria-selected="false">
						<div class="d-flex align-items-center">
						<div class="tab-icon"><i class='bx bx-calendar-minus font-18 me-1'></i> </div>
						<div class="tab-title">Yesterday</div>
						</div>
					</a>
					</li>
				</ul>
			</div>

		</div>

		<div class="card-body">

					
			

				<script>
				const navLinks = document.querySelectorAll('.nav-link');

				navLinks.forEach(link => {
					link.addEventListener('click', () => {
					navLinks.forEach(otherLink => {
						otherLink.classList.remove('bg-primary');
					});
					link.classList.add('bg-primary');
					});
				});
				</script>


					
		<div class="tab-content" id="pills-tabContent">

		<div class="tab-pane fade show active" id="primary-pills-today" role="tabpanel">

		<?php
            $today = Carbon::today()->toDateString();
			$todaysSales = DB::table('retailsales')->where('date', $today)->sum(DB::raw('quantity * price'));
            ?>
                 
          <div style="margin-top:-10px;">
		  <a href="#" style="font-size:15px" >
			<span style="color:black;font-size:15px">Live sales | </span> 
			<span style="color:gray;font-weight:bold;font-size:15px">MWK</span><span style="color:gray;font-weight:bold;font-size:15px">@convert($todaysSales)</span>
		  </a>

			<a href="#" style="float:right;margin-right:2px" > <i class="bx bx-cog text-primary"></i> </a>
		
		  </div>
        

		<table class="table table-sm today"  id="ztable" >
			<thead>
				<tr>
				<th class="bg-primary">#</th>
				<th class="bg-primary" style="text-align:left">Branch</th>
				<th class="bg-primary" style="text-align:center">Sales</th>
				</tr>
			</thead>
			<tbody>
				<?php

				$branches=DB::table('branches')->where('Sector','Retail')->get();
		
				$i=1;
				?>
				@foreach($branches as $branch)
				<tr>
				<?php
				$totalsales = DB::table('retailsales')->where('date',Carbon::today()->toDateString())->where('branch',$branch->id)->sum(DB::raw('quantity * price'));
				$todayssalesmodal = "todayssalesmodal".$branch->id;
				?>
				<td>{{$i++}}</td>
				<td style="text-align:left">
				{{$branch->branch}}
				<!---modal---->
				<div class="modal" tabindex="-1" role="dialog" id="{{$todayssalesmodal}}">
					<div class="modal-dialog" role="document">
					<div class="modal-content">

					<div class="modal-header">

					<?php
						$ssales = DB::table('retailsales')->where('branch',$branch->id)->where('date',Carbon::today()->toDateString())->sum(DB::raw('quantity * price'));
						$msales = DB::table('retailmanualsales')->where('branch',$branch->id)->where('date',Carbon::today()->toDateString())->value('sales'); 
						?>
						<span></span>
						<a href="#" style="float:left;color:black">{{$branch->branch}} | Live sales</a>
							<button type="button" style="font-size:10px" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

					</div>
						<div class="modal-body">		
					<table class="table table-sm" style="margin-top:-10px">
					<thead>
						<?php
						$intervals = DB::table('intervalsales')->where('branch',$branch->id)->where('date',Carbon::today()->toDateString())->orderBy('id','asc')->pluck('slot');
						?>
						<tr>
					<td style="text-align:left;border-bottom:2px solid #737373;font-weight:bold">Interval</td>
					<td style="text-align:center;border-bottom:2px solid #737373;font-weight:bold">Username</td>
					<td style="text-align:center;border-bottom:2px solid #737373;font-weight:bold">System</td>
					<td style="text-align:center;border-bottom:2px solid #737373;font-weight:bold">Cash</td>
					<td style="text-align:center;border-bottom:2px solid #737373;font-weight:bold">Diff</td>
						
						</tr>
					</thead>
					<tbody>
						@foreach($intervals as $interval)
						<tr>
							<td style="float:left">{{$interval}}</td>
							<?php
							$users = DB::table('retailsales')->where('branch',$branch->id)->where('date',Carbon::today()->toDateString())->where('slot',$interval)->distinct()->pluck('user');
		
							$sys = DB::table('retailsales')->where('branch',$branch->id)->where('date',Carbon::today()->toDateString())->where('slot',$interval)->sum(DB::raw('quantity * price'));
							$mns = DB::table('intervalsales')->where('branch',$branch->id)->where('date',Carbon::today()->toDateString())->where('slot',$interval)->value('sales');
					
							$userid =   DB::table('intervalsales')->where('branch',$branch->id)->where('date',Carbon::today()->toDateString())->where('slot',$interval)->value('user');
							$username = DB::table('users')->where('id', $userid)->value('username')
							?>
							<td style="text-align:center">
								<span title="<?php foreach($users as $user){ $sysuser = DB::table('users')->where('id',$user)->value('username'); echo $sysuser; } ?>">{{$username}}</span>
							</td>
							<td style="text-align:center">@convert($sys)</td>
							<td style="text-align:center">@convert($mns)</td>
							<td style="text-align:center">@convert($mns-$sys)</td>
						</tr>
					
						@endforeach
						<tr>
					<td style="text-align:center;border-bottom:2px solid #737373;border-top:2px solid #737373"></td>
					<td style="text-align:center;border-bottom:2px solid #737373;border-top:2px solid #737373;font-weight:bold">Grand total</td>
					<td style="text-align:center;border-bottom:2px solid #737373;border-top:2px solid #737373;font-weight:bold">@convert($ssales)</td>
					<td style="text-align:center;border-bottom:2px solid #737373;border-top:2px solid #737373;font-weight:bold">@convert($msales)</td>
					<td style="text-align:center;border-bottom:2px solid #737373;border-top:2px solid #737373;font-weight:bold">@convert($msales-$ssales)</td>
					</tr>
					</tbody>
				</table>
						</div>
					</div>
					</div>
				</div>
				<!---/Modal---->
				</td>

				<td style="text-align:center">
				@if($totalsales>0)
				<a href="#"  class="zbtn" id1="{{$todayssalesmodal}}">@convert($totalsales)</a>
				@else
				<a href="#" class="zbtn" id1="{{$todayssalesmodal}}">-</a>
				@endif
				</td>
				
				</tr>
				@endforeach
			</tbody>
			</table>
		</div>

	<div class="tab-pane fade" id="primary-pills-yesterday" role="tabpanel">

	    <?php
            $yesterday = Carbon::today()->subDays(1)->toDateString();
            $totalsYesterday = DB::table('retailsales')->where('date', $yesterday)->sum(DB::raw('quantity * price'));
            ?>
                 
	    <div style="margin-top:-10px;">
		  <a href="#" style="font-size:15px" >
			<i class="feather icon-shopping-cart"></i>
			 Yesterdays sales | <span>MWK</span><span>@convert($totalsYesterday)</span>
		  </a>

			<a href="#" style="float:right;margin-right:2px" > <i class="bx bx-cog"></i> </a>
		
		  </div>
		
     
	<table class="table table-sm "  id="ztable2" style="margin-top:10px" >
          <thead>
            <tr>
              <th class="bg-primary">#</th>
              <th class="bg-primary" style="text-align:left">Branch</th>
              <th class="bg-primary" style="text-align:center">Sales</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i=1;
            $yesterdate = Carbon::today()->subDays(1)->toDateString();
            ?>
            @foreach($branches as $branch)
            <tr>
              <?php
              $totalsalesyesterday = DB::table('retailsales')->where('date',$yesterdate)->where('branch',$branch->id)->sum(DB::raw('quantity * price'));
              $manualsales =  DB::table('retailmanualsales')->where('date',$yesterdate)->where('branch',$branch->id)->value('sales');
              $salesdiff =  $manualsales-$totalsalesyesterday;
              $modalid = "yzmodal".$branch->id;

              ?>
              <td>{{$i++}}</td>
              <td style="text-align:left">{{$branch->branch}}
              <span style="color:gray"> 
             [  
              @if($salesdiff>0)
              <span>+</span> 
              @else
              <span></span>
              @endif
              @convert($salesdiff)
              ]</span> </td>
              <td style="text-align:center">
              @if($totalsalesyesterday>0)
              <a href="#"  class="zbtn" id1="{{$modalid}}">@convert($totalsalesyesterday)</a>
              @else
              <a href="#">-</a>
              @endif
              <?php
            $ssales = DB::table('retailsales')->where('branch',$branch->id)->where('date',$yesterdate)->sum(DB::raw('quantity * price'));
            $msales = DB::table('retailmanualsales')->where('branch',$branch->id)->where('date',$yesterdate)->value('sales'); 
            ?>
            
              <!--Modal-->
           
      <div class="modal fade  text-left" id="{{$modalid}}">
        <div class="modal-dialog ">
          <div class="modal-content">
            <div class="modal-header bg-primary" >
              <h4 class="modal-title"><i class="fa fa-info-circle"></i> Sales info</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <span style="font-weight:bold">Branch Name</span>&nbsp;  :&nbsp; {{$branch->branch}} <br>
            <span  style="font-weight:bold">Date</span>&nbsp; :&nbsp; {{$yesterdate}} <br>
            <span  style="font-weight:bold">System sales</span>&nbsp; :&nbsp;<span>MWK</span>@convert($ssales ) <br>
            <span  style="font-weight:bold">Manual sales</span>&nbsp; : &nbsp;<span>MWK</span>@convert($msales) <br>
            <span  style="font-weight:bold">Difference</span>&nbsp; :&nbsp;<span>MWK</span>@convert($msales-$ssales)<br>
            <table class="table table-sm"  style="margin-top:15px">
            <?php
              $intervals = DB::table('intervalsales')->where('branch',$branch->id)->where('date',$yesterdate)->pluck('slot');
              ?>
                <thead style="background-color:#f0f5f5">
                    <tr>
                        <th>Interval</th>
                        <th style="text-align:center">User</th>
                        <th style="text-align:center">System</th>
                        <th style="text-align:center">Manual</th>
                        <th style="text-align:center">Diff</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($intervals as $interval)
                    <tr>
                        <td>{{$interval}}</td>
                        <?php
                        $users = DB::table('retailsales')->where('branch',$branch->id)->where('date',$yesterdate)->where('slot',$interval)->distinct()->pluck('user');
      
                        $sys = DB::table('retailsales')->where('branch',$branch->id)->where('date',$yesterdate)->where('slot',$interval)->sum(DB::raw('quantity * price'));
                        $mns = DB::table('intervalsales')->where('branch',$branch->id)->where('date',$yesterdate)->where('slot',$interval)->value('sales');
                
                        $userid =   DB::table('intervalsales')->where('branch',$branch->id)->where('date',$yesterdate)->where('slot',$interval)->value('user');
                        $username = DB::table('users')->where('id', $userid)->value('username')
                        ?>
                        <td style="text-align:center">
                            <span title="<?php foreach($users as $user){ $sysuser = DB::table('users')->where('id',$user)->value('username'); echo $sysuser; } ?>">{{$username}}</span>
                        </td>
                        <td style="text-align:center">@convert($sys)</td>
                        <td style="text-align:center">@convert($mns)</td>
                        <td style="text-align:center">@convert($mns-$sys)</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
      
           
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.Edit modal -->
    


              </td>






            </tr>
            @endforeach
          </tbody>
        </table>
	</div>


</div>	
</div>

	   <div class="row row-cols-1 row-cols-md-3 row-cols-xl-3 g-0 row-group text-center border-top">
		<?php

			$thisMonthSales = DB::table('retailsales')
			->whereMonth('date', Carbon::today()->month)
			->whereYear('date', Carbon::today()->year)
			->whereIn('branch', $branchArray)
			->sum(DB::raw('quantity * price'));

			$lastMonthSales = DB::table('retailsales')
			->whereMonth('date', Carbon::today()->subDays(Carbon::today()->day + 1)->month)
			->whereYear('date', Carbon::today()->subDays(Carbon::today()->day + 1)->year)
			->whereIn('branch', $branchArray)
			->sum(DB::raw('quantity * price'));

			$previousMonthDate1 = Carbon::today()->subDays(Carbon::today()->day + 1);
			$lastMonthMinusOneDate1 = $previousMonthDate1->subDays($previousMonthDate1->day + 1);

			$lastMonthMinusOneSales = DB::table('retailsales')
				->whereMonth('date', $lastMonthMinusOneDate1->month)
				->whereYear('date', $lastMonthMinusOneDate1->year)
				->whereIn('branch', $branchArray)
				->sum(DB::raw('quantity * price'));


					
			$thisMonthName = Carbon::today()->format('F');
			$lastMonthName = Carbon::today()->subDays(Carbon::today()->day + 1)->format('F');
			$previousMonthDate = Carbon::today()->subDays(Carbon::today()->day + 1);
            $lastMonthMinusOneName = $previousMonthDate->subDays($previousMonthDate->day + 1)->format('F');

			
		?>
			<div class="col bg-primary text-white">
			<div class="p-2">
			<h5 class="mb-0" style="font-size:13px"><span>MWK</span><span>@convert($thisMonthSales)</span></h5>
			<small class="mb-0"> {{$thisMonthName}}</small>		
			</div>
			</div>

			<div class="col bg-success text-white">
			<div class="p-2">
			<h5 class="mb-0" style="font-size:13px"><span>MWK</span><span>@convert($lastMonthSales)</span></h5>
			<small class="mb-0">{{$lastMonthName}}</small>	
			</div>
			</div>

			<div class="col bg-danger text-white">
			<div class="p-2">
			<h5 class="mb-0" style="font-size:13px"><span>MWK</span><span>@convert($lastMonthMinusOneSales)</span></h5>
			<small class="mb-0">{{$lastMonthMinusOneName}}</small>	
			</div>
			</div>

		</div>
		</div>
	</div>

	<div class="col-12 col-lg-4 col-xl-4 d-flex" style="height: 100%; display: flex; flex-direction: column;">
		<div class="card radius-10 overflow-hidden w-100" >
           
		<div class="card-header" style="padding-bottom: 6px;">
			<div class="d-flex align-items-center ">
			<button class="btn" style="padding-left: 0px; display: flex; align-items: center;">
			<i class="fa fa-line-chart text-primary"></i>
             <span>Value added</span>
             </button>

			<div class="dropdown options ms-auto">
				<div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
				<i class='bx bx-dots-horizontal-rounded text-primary'></i>
				</div>
				<ul class="dropdown-menu">
				<li><a class="dropdown-item" href="javascript:;">Refresh</a></li>
				</ul>
			</div>
			</div>
			</div>

		<div class="card-body">
			<div class="chart-js-container2">
			<?php


			$thisDayDnotes = DB::table('retaildeliverynotes')
			->whereDate('date', Carbon::today())
			->whereIn('branchid', $branchArray)
			->sum(DB::raw('quantity * price'));
					
		$thisMonthDnotes = DB::table('retaildeliverynotes')
		->whereMonth('date', Carbon::today()->month)
		->whereYear('date', Carbon::today()->year)
		->whereIn('branchid', $branchArray)
		->sum(DB::raw('quantity * price'));

		$lastMonthDate = Carbon::today()->subDays(Carbon::today()->day + 1);
		$lastMonthDnotes = DB::table('retaildeliverynotes')
		->whereMonth('date', $lastMonthDate->month)
		->whereYear('date', $lastMonthDate->year)
		->whereIn('branchid', $branchArray)
		->sum(DB::raw('quantity * price'));

		$twoMonthsAgoDate = $lastMonthDate->subDays($lastMonthDate->day + 1);
		$lastMonthMinusOneDnotes = DB::table('retaildeliverynotes')
		->whereMonth('date', $twoMonthsAgoDate->month)
		->whereYear('date', $twoMonthsAgoDate->year)
		->whereIn('branchid', $branchArray)
		->sum(DB::raw('quantity * price'));
			

				
			 ?>



			    <div class="piechart-legend border border-primary border-5 p-4">
				<h2 class="mb-1">MWK @convert($thisMonthDnotes) <br><span></span> </h2>
				<h6 class="mb-0">This month</h6>
				</div>
		
			</div>
		</div>
		<ul class="list-group list-group-flush">
			<li class="list-group-item d-flex justify-content-between align-items-center border-top">
			Today
			<span class="badge bg-primary rounded-pill"><span>MWK</span>@convert($thisDayDnotes)</span>
			</li>
			<li class="list-group-item d-flex justify-content-between align-items-center">
			 {{$lastMonthName}}
			<span class="badge bg-success rounded-pill"><span>MWK</span>@convert($lastMonthDnotes)</span>
			</li>
			<li class="list-group-item d-flex justify-content-between align-items-center">
			{{$lastMonthMinusOneName }}
			<span class="badge bg-danger rounded-pill"><span>MWK</span>@convert($lastMonthMinusOneDnotes)</span>
			</li>
		</ul>
		</div>
	</div>
	</div><!--End Row-->







		<!--<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                        

		

					<div class="col">
					<div class="card radius-10 bg-gradient-ibiza">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<h5 class="mb-0 text-white">6200</h5>
							<div class="ms-auto">
								<i class='bx bx-bar-chart fs-3 text-white'></i>
							</div>
						</div>
						<div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
							<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<div class="d-flex align-items-center text-white">
							<p class="mb-0">Losses (Today) </p>
							<p class="mb-0 ms-auto"><span><i class='bx bx-right-arrow-alt'></i></span></p>
						</div>
					</div>
				</div>
				</div>





						<div class="col">
							<div class="card radius-10 bg-gradient-deepblue">
							 <div class="card-body">
								<div class="d-flex align-items-center">
									<h5 class="mb-0 text-white">9526</h5>
									<div class="ms-auto">
                                        <i class='bx bx-cart fs-3 text-white'></i>
									</div>
								</div>
								<div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
									<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center text-white">
									<p class="mb-0">Loses today</p>
									<p class="mb-0 ms-auto"><i class='bx bx-right-arrow-alt'></i><span></span></p>
								</div>
							</div>
						  </div>
						</div>


						<div class="col">
							<div class="card radius-10 bg-gradient-ohhappiness">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<h5 class="mb-0 text-white">$8323</h5>
									<div class="ms-auto">
                                        <i class='bx bx-dollar fs-3 text-white'></i>
									</div>
								</div>
								<div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
									<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center text-white">
									<p class="mb-0">Value added (Today) </p>
									<p class="mb-0 ms-auto">+1.2%<span><i class='bx bx-up-arrow-alt'></i></span></p>
								</div>
							</div>
						  </div>
						</div>



						<div class="col">
							<div class="card radius-10 bg-gradient-moonlit">
							 <div class="card-body">
								<div class="d-flex align-items-center">
									<h5 class="mb-0 text-white">5630</h5>
									<div class="ms-auto">
                                        <i class='bx bx-envelope fs-3 text-white'></i>
									</div>
								</div>
								<div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
									<div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="d-flex align-items-center text-white">
									<p class="mb-0">Messages</p>
									<p class="mb-0 ms-auto">+2.2%<span><i class='bx bx-up-arrow-alt'></i></span></p>
								</div>
							</div>
						 </div>
						</div>


					</div>--><!--end row-->
			</div>
		</div>
		<!--end page wrapper -->




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

      $('body').on('click', '#infobtn', function () {
        $('#info-modal').modal('show');
    }); 
    
    $('body').on('click', '#changedate', function () {
        $('#date-modal').modal('show');
    });    

    $('body').on('click', '#businessbtn', function () {
        $('#business-modal').modal('show');
    }); 


  $('body').on('click', '.zbtn', function () {
  var modal = $(this).attr('id1');
  $('#'+modal).modal('show');
  }); 


    
  $(document).ready(function() {
    var t = $('#ztable').DataTable( {
        "paging":false,
        "searching": false,
        "bInfo" : false,
        "order": [[ 2, "desc" ]]
    } );
 
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
} );



 
$(document).ready(function() {
    var t = $('#ztable2').DataTable( {
        "paging":false,
        "searching": false,
        "bInfo" : false,
        "order": [[ 2, "desc" ]]
    } );
 
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
} );

$('body').on('click', '.zbtn', function () {
  var modal = $(this).attr('id1');
  $('#'+modal).modal('show');
  }); 

</script>
	
</body>
</html>
	
	