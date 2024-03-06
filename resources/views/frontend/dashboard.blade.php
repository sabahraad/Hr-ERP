@include('frontend.header')
@include('frontend.navbar')    
<link rel="stylesheet" href="{{ asset('plugins/morris/morris.css') }}">
    <body>
		<!-- Main Wrapper -->
        <div class="main-wrapper">
			
			<!-- Page Wrapper -->
            <div class="page-wrapper">
			
				<!-- Page Content -->
                <div class="content container-fluid">
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-sm-12">
								<h3 class="page-title">Welcome Admin!</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item active">Dashboard</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->
				
					<div class="row">
						<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
							<div class="card dash-widget">
								<div class="card-body">
									<span class="dash-widget-icon"><i class="fa-solid fa-cubes"></i></span>
									<div class="dash-widget-info">
										<h3>{{$data['total_emp']}}</h3>
										<span>Total Employee</span>
									</div>
								</div>
							</div>


						</div>
						<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
							<div class="card dash-widget">
								<div class="card-body">
									<span class="dash-widget-icon"><i class="fa-solid fa-dollar-sign"></i></span>
									<div class="dash-widget-info">
										<h3>{{$data['total_attendance']}}</h3>
										<span>Total Present</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
							<div class="card dash-widget">
								<div class="card-body">
									<span class="dash-widget-icon"><i class="fa-regular fa-gem"></i></span>
									<div class="dash-widget-info">
										<h3>{{$data['total_on_leave']}}</h3>
										<span>total leave</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
							<div class="card dash-widget">
								<div class="card-body">
									<span class="dash-widget-icon"><i class="fa-solid fa-user"></i></span>
									<div class="dash-widget-info">
										<h3>{{$data['total_absent']}}</h3>
										<span>total absent</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6 text-center">
									<div class="card">
										<div class="card-body">
											<h3 class="card-title">Total Revenue</h3>
											<div id="bar-charts"></div>
										</div>
									</div>
								</div>
								<div class="col-md-6 text-center">
									<div class="card">
										<div class="card-body">
											<h3 class="card-title">Sales Overview</h3>
											<div id="line-charts"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
				<!-- /Page Content -->

            </div>
			<!-- /Page Wrapper -->
        </div>
		<!-- /Main Wrapper -->
        <script src="{{ asset('plugins/morris/morris.min.js') }}"></script>
        <script src="{{ asset('plugins/raphael/raphael.min.js') }}"></script>
        <script src="{{ asset('js/chart.js') }}"></script>
    </body>
</html>