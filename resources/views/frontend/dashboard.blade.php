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
									<span class="dash-widget-icon"><i class="fa-solid fa-user"></i></span>
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
									<span class="dash-widget-icon"><i class="fas fa-check-circle"></i></span>
									<div class="dash-widget-info">
										<h3>{{$data['total_attendance']}}</h3>
										<span>Today Present</span>
									</div>
								</div>
							</div>
						</div>
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
							<div class="card dash-widget">
								<div class="card-body">
									<span class="dash-widget-icon"><i class="fas fa-user-slash"></i></span>
									<div class="dash-widget-info">
										<h3>{{$data['total_absent']}}</h3>
										<span>Today Absent</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
							<div class="card dash-widget">
								<div class="card-body">
									<span class="dash-widget-icon"><i class="fas fa-bed"></i></span>
									<div class="dash-widget-info">
										<h3>{{$data['total_on_leave']}}</h3>
										<span>Today On Leave</span>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12 text-center">
									<div class="card">
										<div class="card-body">
											<h3 class="card-title">Attendnace</h3>
											<div id="bar-charts"></div>
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
        <script >
			$(document).ready(function() {
				var jwtToken = "{{ $jwtToken }}";
				var baseUrl = "{{ $baseUrl }}";
				$.ajax({
					url: baseUrl + '/chart-details', 
					type: 'GET',
					headers: {
						'Authorization': 'Bearer ' + jwtToken
					},
					success: function(reportData) {
						console.log(reportData);

						// Convert the data to Morris Bar Chart format
						var morrisData = [];
						
						for (var date in reportData.data) {
							if (reportData.data.hasOwnProperty(date)) {
								var entry = {
									y: date
								};

								// Check if it's a holiday
								if (reportData.data[date].total_attendance === 0 && reportData.data[date].Weekend === 0) {
									entry.h = reportData.data[date].Holiday;
									entry.label = 'Holiday';
								} 
								// Check if it's a weekend
								else if (reportData.data[date].Weekend === 1) {
									entry.w = 1;
									entry.label = 'Weekend';
								} 
								// It's a regular day with attendance and absent data
								else {
									entry.a = reportData.data[date].total_attendance;
									entry.b = reportData.data[date].total_absent;
									entry.label = 'Present/Absent';
								}

								morrisData.push(entry);
							}
						}

						// Initialize Morris Bar Chart
						Morris.Bar({
							element: 'bar-charts',
							redrawOnParentResize: true,
							data: morrisData,  // Use the converted data
							xkey: 'y',
							ykeys: ['a', 'b'],
							labels: ['Present', 'Absent'],
							barColors: ['#ff9b44', '#fc6075'],
							resize: true,
							redraw: true,
							xLabelAngle: 25,
							// yLabelFormat: function(y) {
							// 	return y.toFixed(0);  // Format Y-axis values as integers
							// },
							yLabelInterval: 2
						});
					},
					error: function(error) {
						// Handle any errors
						console.error(error);
					}
				});
			});
    </script>
    </body>
</html>