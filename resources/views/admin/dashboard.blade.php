<!doctype html>
<html lang="en" class="semi-dark">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="system/images/cube.png" type="image/x-icon">
	<!--plugins-->
	<link href="dashboard/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
	<link href="dashboard/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="dashboard/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="dashboard/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet"/>
	<link rel="stylesheet" type="text/css" href="dashboard/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="dashboard/feather/css/feather.css">

	<link href="dashboard/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

	<link rel="stylesheet" type="text/css" href="dashboard/bower_components/cropper/dist/cropper.css">

	<link rel="stylesheet" type="text/css" href="dashboard/bower_components/lightbox2/dist/css/lightbox.min.css">

	<!-- loader-->
	<link href="dashboard/assets/css/pace.min.css" rel="stylesheet"/>
	<script src="dashboard/assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="dashboard/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="dashboard/assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="dashboard/assets/css/app.css" rel="stylesheet">
	<link href="dashboard/assets/css/icons.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="dashboard/assets/css/dark-theme.css"/>
	<link rel="stylesheet" href="dashboard/assets/css/semi-dark.css"/>
	<link rel="stylesheet" href="dashboard/assets/css/header-colors.css"/>
    <title>Netacube | The Ultimate Business Management Solution </title>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">

			    <div>
				   <a href="admin-dashboard"><img src="system/images/cube2.png" style="width:30px;margin-left:10px" class="logo-iconk" alt=""></a>	
				</div>


				<div>
					<h4 class="logo-text"> <a href="admin-dashboard" class="text-white">| Admin</a></h4>
				</div>
				
                
				<div class="toggle-icon ms-auto">
                    <i class='bx bx-arrow-back'></i>
				</div>
			 </div>
			<!--navigation-->
			<ul class="metismenu" id="menu">

			<!--<li class="menu-label">DASHBOARD (ADMIN)</li>-->
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-desktop'></i>
						</div>
						<div class="menu-title">System</div>
					</a>
					<ul>

					<li><a href="/business-sector"><i class='bx bx-radio-circle'></i>Sectors</a></li>

					<li><a href="/user-roles"><i class='bx bx-radio-circle'></i>Roles</a></li>

					
					<li><a href="/vat-statuses"><i class='bx bx-radio-circle'></i>VAT</a></li>

					</ul>
				</li>


				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class="bx bx-menu"></i>
						</div>
						<div class="menu-title">General</div>
					</a>
					<ul>

					<li><a href="/admin-dashboard"><i class='bx bx-radio-circle'></i>Home</a></li>

					<li><a href="/company-info"><i class='bx bx-radio-circle'></i>Company</a></li>

					<li><a href="/employees"><i class='bx bx-radio-circle'></i>Employees</a></li>

					<li><a href="/users"><i class='bx bx-radio-circle'></i>Users</a></li>

					<li><a href="/business-categories"><i class='bx bx-radio-circle'></i>Categories</a></li>

					<li><a href="/branches"><i class='bx bx-radio-circle'></i>Branches</a></li>
					
					<li><a href="/suppliers"><i class='bx bx-radio-circle'></i>Suppliers</a></li>
					
					</ul>
				</li>
				<!--<li class="menu-label">OPERATIONS</li>-->
			
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-cart'></i>
						</div>
						<div class="menu-title">Retail</div>
					</a>
					<ul>
					 <li><a href="admin-retail-baseproducts"><i class='bx bx-radio-circle'></i>Products</a></li>
					
					 <li><a href="admin-retail-branch-products"><i class='bx bx-radio-circle'></i>Inventory</a></li>

					 <li><a href="admin-retail-product-tracker"><i class='bx bx-radio-circle'></i>Logs</a></li>

					 <li><a href="admin-retail-product-supplies"><i class='bx bx-radio-circle'></i>Supplies</a></li>

					 <li><a href="admin-retail-openingstock"><i class='bx bx-radio-circle'></i>Opening</a></li>

					 
					 <li><a href="admin-retail-system-sales"><i class='bx bx-radio-circle'></i>Systemsales</a></li>

					 <li><a href="admin-retail-clients"><i class='bx bx-radio-circle'></i>Clients</a></li>

					
					</ul>
				</li>



				<!--<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class='bx bx-car'></i>
						</div>
						<div class="menu-title">Wholesale</div>
					</a>
					<ul>
						
					<li><a href="admin-wholesale-baseproducts"><i class='bx bx-radio-circle'></i>Products</a></li>

					<li><a href="admin-wholesale-branch-products"><i class='bx bx-radio-circle'></i>Invetory</a></li>

					<li><a href="admin-wholesale-product-tracker"><i class='bx bx-radio-circle'></i>Logs</a></li>

					<li><a href="admin-wholesale-product-supplies"><i class='bx bx-radio-circle'></i>Supplies</a></li>

					<li><a href="admin-wholesale-clients"><i class='bx bx-radio-circle'></i>Clients</a></li>
						
					</ul>
				</li>-->




				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-cog"></i>
						</div>
						<div class="menu-title">Settings</div>
					</a>
					<ul>
						<!--<li><a href="admin-homepage-settings"><i class='bx bx-radio-circle'></i>Homepage</a></li>-->
						<li><a href="javascript:;"><i class='bx bx-radio-circle'></i>Homepage</a></li>
					</ul>
				</li>


		
				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class="bx bx-globe"></i>
						</div>
						<div class="menu-title">Website</div>
					</a>
					<ul>
						<li><a href="javascript:;"><i class='bx bx-radio-circle'></i>Status</a></li>
						<li><a href="javascript:;"><i class='bx bx-radio-circle'></i>Wellcome</a></li>
						<li><a href="javascript:;"><i class='bx bx-radio-circle'></i>About</a></li>
						<li><a href="javascript:;"><i class='bx bx-radio-circle'></i>Services</a></li>
						<li><a href="javascript:;"><i class='bx bx-radio-circle'></i>Gallery</a></li>
						<li><a href="javascript:;"><i class='bx bx-radio-circle'></i>Contact</a></li>
						<li><a href="javascript:;"><i class='bx bx-radio-circle'></i>Settings</a></li>
					</ul>
				</li>


			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->

		<!--start header -->
           <header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand gap-3">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>

					  <div class="position-relative search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
						<input class="form-control px-5" type="search" placeholder="Search">
						<span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-5"><i class='bx bx-search'></i></span>
					  </div>


					  <div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center gap-1">


							<li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
								<a class="nav-link" href="avascript:;"><i class='bx bx-search'></i>
								</a>
							</li>



							<li class="nav-item dropdown dropdown-app">
                             <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="javascript:;">
							   <i class='bx bx-grid-alt'></i>
							  </a>
								<div class="dropdown-menu dropdown-menu-end p-0">
									<div class="app-container p-2 my-2">
									  <div class="row gx-0 gy-2 row-cols-3 justify-content-center p-2">

										 <!--<div class="col">
										  <a href="javascript:;">
											<div class="app-box text-center">
											  <div class="app-icon">
												  <img src="dashboard/assets/images/app/youtube.png" width="30" alt="">
											  </div>
											  <div class="app-name">
												  <p class="mb-0 mt-1">YouTube</p>
											  </div>
											  </div>
											</a>
										 </div>-->

				
									  </div><!--end row-->
									</div>
								</div>	
							</li>

							<li class="nav-item dropdown dropdown-large">
							<!--<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown"><span class="alert-count">7</span>
									<i class='bx bx-bell'></i>
								</a>-->
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Notifications</p>
											<p class="msg-header-badge">8 New</p>
										</div>
									</a>
									<div class="header-notifications-list">


										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-1.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Daisy Anderson<span class="msg-time float-end">5 sec
												ago</span></h6>
													<p class="msg-info">The standard chunk of lorem</p>
												</div>
											</div>
										</a>

									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">
											<button class="btn btn-primary w-100">View All Notifications</button>
										</div>
									</a>
								</div>
							</li>





							<li class="nav-item dropdown dropdown-large">
								<!--<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">8</span>
									<i class='bx bx-shopping-bag'></i>
								</a>-->
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">My Cart</p>
											<p class="msg-header-badge">10 Items</p>
										</div>
									</a>
									<div class="header-message-list">

										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center gap-3">
												<div class="position-relative">
													<div class="cart-product rounded-circle bg-light">
														<img src="assets/images/products/11.png" class="" alt="product image">
													</div>
												</div>
												<div class="flex-grow-1">
													<h6 class="cart-product-title mb-0">Men White T-Shirt</h6>
													<p class="cart-product-price mb-0">1 X $29.00</p>
												</div>
												<div class="">
													<p class="cart-price mb-0">$250</p>
												</div>
												<div class="cart-product-cancel"><i class="bx bx-x"></i>
												</div>
											</div>
										</a>
									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">
											<div class="d-flex align-items-center justify-content-between mb-3">
												<h5 class="mb-0">Total</h5>
												<h5 class="mb-0 ms-auto">$489.00</h5>
											</div>
											<button class="btn btn-primary w-100">Checkout</button>
										</div>
									</a>
								</div>
							</li>


							
						</ul>
					</div>
					<div class="user-box dropdown px-3">
						<a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<!--<img src="assets/images/avatars/avatar-2.png" class="user-img" alt="user avatar">--->
							<i class="bx bx-user fs-4"></i>
							<div class="user-info">
								<p class="user-name mb-0">{{Auth::user()->username}}</p>
								<!--<p class="designattion mb-0">Main</p>-->
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li>
								<a href="admin-profile" class="dropdown-item d-flex align-items-center" href="javascript:;">
								 <i class="bx bx-user fs-5"></i><span>Profile</span>
								</a>
							</li>
							<li>
								<a class="dropdown-item d-flex align-items-center" href="javascript:;">
									<i class="bx bx-cog fs-5"></i><span>Settings</span>
								</a>
							</li>

							<li>
								<div class="dropdown-divider mb-0"></div>
							</li>
							<li>
								<a href="/" class="dropdown-item d-flex align-items-center" href="javascript:;">
								 <i class="bx bx-log-out-circle"></i><span>Logout</span>
								</a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<!--end header -->

		

      @yield('content',View::make('admin.default'))
      


		<!--start overlay-->
		 <div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button-->
		  <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<!--<footer class="page-footer">
			<p class="mb-0">Copyright © 2023. All right reserved.</p>
		</footer>-->
	</div>
	<!--end wrapper-->


	<!-- search modal -->
    <div class="modal" id="SearchModal" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-md-down">
		  <div class="modal-content">
			<div class="modal-header gap-2">
			  <div class="position-relative popup-search w-100">
				<input class="form-control form-control-lg ps-5 border border-3 border-primary" type="search" placeholder="Search">
				<span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-4"><i class='bx bx-search'></i></span>
			  </div>
			  <button type="button" class="btn-close d-md-none" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="search-list">
				   <p class="mb-1">Html Templates</p>
				   <div class="list-group">
					  <a href="javascript:;" class="list-group-item list-group-item-action active align-items-center d-flex gap-2 py-1"><i class='bx bxl-angular fs-4'></i>Best Html Templates</a>
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vuejs fs-4'></i>Html5 Templates</a>
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-magento fs-4'></i>Responsive Html5 Templates</a>
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-shopify fs-4'></i>eCommerce Html Templates</a>
				   </div>
				   <p class="mb-1 mt-3">Web Designe Company</p>
				   <div class="list-group">
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-windows fs-4'></i>Best Html Templates</a>
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-dropbox fs-4' ></i>Html5 Templates</a>
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-opera fs-4'></i>Responsive Html5 Templates</a>
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-wordpress fs-4'></i>eCommerce Html Templates</a>
				   </div>
				   <p class="mb-1 mt-3">Software Development</p>
				   <div class="list-group">
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-mailchimp fs-4'></i>Best Html Templates</a>
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-zoom fs-4'></i>Html5 Templates</a>
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-sass fs-4'></i>Responsive Html5 Templates</a>
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vk fs-4'></i>eCommerce Html Templates</a>
				   </div>
				   <p class="mb-1 mt-3">Online Shoping Portals</p>
				   <div class="list-group">
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-slack fs-4'></i>Best Html Templates</a>
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-skype fs-4'></i>Html5 Templates</a>
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-twitter fs-4'></i>Responsive Html5 Templates</a>
					  <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vimeo fs-4'></i>eCommerce Html Templates</a>
				   </div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
    <!-- end search modal -->




	<!--start switcher-->
	<div class="switcher-wrapper">
		<!--<div class="switcher-btn"> 
		<i class='bx bx-cog bx-spin'></i>
		</div>-->
		<div class="switcher-body">
			<div class="d-flex align-items-center">
				<h5 class="mb-0 text-uppercase">Theme Customizer</h5>
				<button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
			</div>
			<hr/>
			<h6 class="mb-0">Theme Styles</h6>
			<hr/>
			<div class="d-flex align-items-center justify-content-between">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode">
					<label class="form-check-label" for="lightmode">Light</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode">
					<label class="form-check-label" for="darkmode">Dark</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark" checked>
					<label class="form-check-label" for="semidark">Semi Dark</label>
				</div>
			</div>
			<hr/>
			<div class="form-check">
				<input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
				<label class="form-check-label" for="minimaltheme">Minimal Theme</label>
			</div>
			<hr/>
			<h6 class="mb-0">Header Colors</h6>
			<hr/>
			<div class="header-colors-indigators">
				<div class="row row-cols-auto g-3">
					<div class="col">
						<div class="indigator headercolor1" id="headercolor1"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor2" id="headercolor2"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor3" id="headercolor3"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor4" id="headercolor4"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor5" id="headercolor5"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor6" id="headercolor6"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor7" id="headercolor7"></div>
					</div>
					<div class="col">
						<div class="indigator headercolor8" id="headercolor8"></div>
					</div>
				</div>
			</div>
			<hr/>
			<h6 class="mb-0">Sidebar Colors</h6>
			<hr/>
			<div class="header-colors-indigators">
				<div class="row row-cols-auto g-3">
					<div class="col">
						<div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
					</div>
					<div class="col">
						<div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--end switcher-->
	<!-- Bootstrap JS -->
	<script src="dashboard/assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="dashboard/assets/js/jquery.min.js"></script>
	<script src="dashboard/assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="dashboard/assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="dashboard/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="dashboard/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="dashboard/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="dashboard/assets/plugins/chartjs/js/chart.js"></script>
	<script src="dashboard/assets/plugins/sparkline-charts/jquery.sparkline.min.js"></script>
	<script src="dashboard/assets/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js"></script>
	<script src="dashboard/assets/plugins/jquery-knob/excanvas.js"></script>
	<script src="dashboard/assets/plugins/jquery-knob/jquery.knob.js"></script>

	<script src="dashboard/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="dashboard/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

	<script src="dashboard/bower_components/cropper/dist/cropper.min.js"></script>

	<script type="text/javascript" src="dashboard/bower_components/lightbox2/dist/js/lightbox.min.js"></script>

     <script src="dashboard/pages/cropper/croper.js"></script>


	 <script src="papaparse/papaparse.min.js"></script>




	<script>
		  $(function() {
			  $(".knob").knob();
		  });
	</script>
	<script src="dashboard/assets/js/index.js"></script>
	<!--app JS-->
	<script src="dashboard/assets/js/app.js"></script>
	<script>
		new PerfectScrollbar(".app-container")
	</script>
</body>
</html>