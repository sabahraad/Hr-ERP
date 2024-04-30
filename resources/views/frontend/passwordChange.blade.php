<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
    <head>
        <meta charset="utf-8">
        <title>Change Password</title>
		
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
        <link rel="stylesheet" href="{{ asset('css/style3.css') }}">
		
		 <style>
            .account-page {
                background: linear-gradient(277.57deg, #6258a6 0%, #82cae8 100%);
            } 
        </style>
    </head>
	<body class="account-page">
		<!-- Main Wrapper -->
		
			<div class="main-wrapper">
				<div class="account-content">
					<!-- Account Logo -->
					<!-- <div class="account-logo">
						<h1>TimeWise</h1>
					</div> -->
					<div class="account-box">
						<div class="account-wrapper">
							<h3 class="account-title">Change Password</h3>
							<form id="msform" >
								<div class="input-block mb-3">
									<label class="col-form-label">Old password</label>
									<input type="password" class="form-control" name = "old_password">
								</div>
								<div class="input-block mb-3">
									<label class="col-form-label">New password</label>
									<input type="password" class="form-control" name = "password">
								</div>
								<div class="input-block mb-3">
									<label class="col-form-label">Confirm password</label>
									<input type="password" class="form-control" name = "password_confirmation">
								</div>
                                @php
                                    $email = session('email');
                                @endphp
                                <input type="text" class="form-control" name = "email" value="{{$email}}" hidden>

								<div class="submit-section mb-4">
									<button class="btn btn-primary submit-btn" style="border: 1px solid #6b77b8; background: #6b77b8;">Update Password</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- /Two Col Sidebar --><!-- Page Wrapper -->
           
		<!-- /Main Wrapper -->
		

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

		<script> 
			$(document).ready(function() {
                var jwtToken = "{{ $jwtToken }}";
                var baseUrl = "{{ $baseUrl }}";
				$('#msform').submit(function(e) {
                    e.preventDefault();

                    var formData = new FormData(this);

                    $.ajax({
                        url: baseUrl + '/password-change', 
                        type: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + jwtToken
                        },
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            console.log(response);
                            Swal.fire({
                                icon: 'success',
                                title: 'Password Change successful',
                                text: 'Password change was successful!',
                                showConfirmButton: true, 
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '/login-form';
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.error;
                                var errorMessage = "<ul>";
                                for (var field in errors) {
                                    errorMessage += "<li>" + errors[field][0] + "</li>";
                                }
                                errorMessage += "</ul>";
                                
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    html: errorMessage
                                });
                            }else{
                                console.log(xhr.responseJSON.data);
                                Swal.fire({
                                    icon: 'error',
                                    title: xhr.responseJSON.message
                                });
                            }
                        }
                    });
                });
			});
		</script>

    </body>
</html>