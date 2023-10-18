<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Smarthr - Bootstrap Admin Template">
		<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
        <meta name="author" content="Dreamguys - Bootstrap Admin Template">
		<meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Include CSRF token -->

        <title>Login</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="{{ asset('fontawesome/css/fontawesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

		<!-- Lineawesome CSS -->
        <link rel="stylesheet" href="{{ asset('css/line-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/material.css') }}">
					
		<!-- Main CSS -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    </head>
    <body class="account-page" style="  background: linear-gradient(to right, #ff9b44 0%, #fc6075 100%);">
	
		<!-- Main Wrapper -->
        <div class="main-wrapper">
			<div class="account-content">
				<div class="container">
				<!-- {{asset('images/logo.png')}} -->
					<!-- Account Logo -->
					<div class="account-logo">
						<a href="admin-dashboard.html"><img src="{{asset('images/aamarPay-02.png')}}" style="width:50%" alt="Dreamguy's Technologies"></a>
					</div>
					<!-- /Account Logo -->
					
					<div class="account-box">
						<div class="account-wrapper">
						
						@if(session('error'))
							<div class="alert alert-danger">
								{{ session('error') }}
							</div>
						@endif

							<h3 class="account-title">Login</h3>
							<p class="account-subtitle">Access to our dashboard</p>
							
							<!-- Account Form -->
							<form action="{{route('login')}}" method="post" >
								@csrf
								<div class="input-block mb-4">
									<label class="col-form-label">Email Address</label>
									<input class="form-control" type="text" name="email">
								</div>
								<div class="input-block mb-4">
									<div class="row align-items-center">
										<div class="col">
											<label class="col-form-label">Password</label>
										</div>
										<div class="col-auto">
											<a class="text-muted" href="forgot-password.html">
												Forgot password?
											</a>
										</div>
									</div>
									<div class="position-relative">
										<input class="form-control" type="password" name="password">
										<span class="fa-solid fa-eye-slash" id="toggle-password"></span>
									</div>
								</div>
								<div class="input-block mb-4 text-center">
									<button class="btn btn-primary account-btn" type="submit">Login</button>
								</div>
								<div class="account-footer">
									<p>Don't have an account yet? <a href="{{route('registrationForm')}}">Register</a></p>
								</div>
							</form>
							<!-- /Account Form -->
							
						</div>
					</div>
				</div>
			</div>
        </div>
		<!-- /Main Wrapper -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

		<!-- jQuery -->
        <script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
		
		<!-- Custom JS -->
        <script src="{{ asset('js/app.js') }}"></script>
		
		<script> 
			$(document).ready(function() {
				$('#loginform').submit(function(e) {
					e.preventDefault();
					console.log('ok');
					var formData = new FormData(this);
					// const accessToken = '{{ session('access_token') }}';

					$.ajax({
							url: '/login', 
							type: 'POST',
							data: formData,
							contentType: false,
							processData: false,
							
							error: function(xhr, status, error) {
								if (xhr.status === 422 ) {
									var errors = xhr.responseJSON.error;
									var errorMessage = "<ul>";
									for (var field in errors) {
										errorMessage += "<li>" + errors[field][0] + "</li>";
									}
									errorMessage += "</ul>";
								}
								if(xhr.status === 401){
									var errors = xhr.responseJSON.error;
									var errorMessage = "";
									for (var field in errors) {
										errorMessage += errors[field][0] ;
									}
								}
								Swal.fire({
									icon: 'error',
									title: 'Validation Error',
									html: errorMessage
								});
								
							}
						});
					});
				});
		</script>
			
    </body>
</html>