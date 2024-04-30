<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
    <head>
	<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Best HR Solution">
		<meta name="keywords" content="HR Solution,TimeWise,Bangladesh,Human Resources Management,HR Software,Time Management,Payroll Management,Employee Management,Attendance Tracking
                                      ,Leave Management,Performance Evaluation,Bangladeshi HR Solution,HR Automation,HRIS (Human Resources Information System),Time Tracking,Employee Engagement
                                      ,HR Technology,Compliance Management,Bangladeshi Businesses,HR AnalyticsComplete HR Solution, HRIS, Cost Effective HR Solution, SME Friendly HR Solution,
                                      SME Friendly HRIS, Human Resource Management system for enterprises.">
        <meta name="author" content="aamarDigital Solution Limited">
		

        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/Icon-512x512.png') }}">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

		<!-- Fontawesome CSS -->
		<link rel="stylesheet" href="{{ asset('fontawesome/css/fontawesome.min.css') }}">
		<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

		<!-- Lineawesome CSS -->
		<link rel="stylesheet" href="{{ asset('css/line-awesome.min.css') }}">
		<link rel="stylesheet" href="{{ asset('css/material.css') }}">

		<!-- Datatable CSS -->
		<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">

		<!-- Select2 CSS -->
		<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

		<!-- Datetimepicker CSS -->
		<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css') }}">

		<!-- Main CSS -->
		<link rel="stylesheet" href="{{ asset('css/style3.css') }}">
<style> 
[data-topbar=light] body .header {
  background: #6564ad;
  background: linear-gradient(277.57deg, #6258a6 0%, #82cae8 100%);
}
</style>
		 
    </head>
    <body>
		<!-- Main Wrapper -->
        <div class="main-wrapper">
		
			<!-- Header -->
            <div class="header">
			
				<!-- Logo -->
                <div class="header-left">
                     <a href="admin-dashboard.html" class="logo">
						<img src="{{asset('images/TimeWise-Logo-white.png')}}" alt="Logo" class="hlogo">
					</a>
                </div>
				<!-- /Logo -->
				
				<a id="toggle_btn" href="javascript:void(0);">
					<span class="bar-icon">
						<span></span>
						<span></span>
						<span></span>
					</span>
				</a>
				
				<!-- Header Title -->
                <div class="page-title-box">
					<h3>HR System</h3>
                </div>
				<!-- /Header Title -->
				
				<a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa-solid fa-bars"></i></a>
				
				<!-- Header Menu -->
				<ul class="nav user-menu">
					<li class="nav-item dropdown has-arrow main-drop">
						<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
								<!-- <span class="user-img"><img src="{{ asset('images/TimeWise-Logo.png') }}"  width="60" height="60" alt="User Image"> -->
							<span class="status online"></span></span>
							<!-- <span>{{auth()->user()}}</span> -->
							<span>{{ session('name') }}</span>
						</a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="{{route('company')}}">Profile</a>
							<a class="dropdown-item" href="{{route('showPasswordChange')}}">Password Change</a>
							<a class="dropdown-item" href="{{route('logout')}}">Logout</a>
						</div>
					</li>
				</ul>
				<!-- /Header Menu -->
				
				<!-- Mobile Menu -->
				<div class="dropdown mobile-user-menu">
					<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="{{route('company')}}"> Profile</a>
						<!-- <a class="dropdown-item" href="settings.html">Settings</a> -->
						<a class="dropdown-item" href="{{route('logout')}}">Logout</a>
					</div>
				</div>
				<!-- /Mobile Menu -->
				
            </div>
			<!-- /Header -->

            <!-- jQuery -->
<script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>

<!-- Select2 JS -->
<script src="{{ asset('js/select2.min.js') }}"></script>

<!-- Bootstrap Core JS -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<!-- Slimscroll JS -->
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>

<!-- Datetimepicker JS -->
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>

<!-- Datatable JS -->
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>

<!-- Theme Settings JS -->
<script src="{{ asset('js/layout.js') }}"></script>
<script src="{{ asset('js/theme-settings.js') }}"></script>
<script src="{{ asset('js/greedynav.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('js/app.js') }}"></script>