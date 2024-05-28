@include('frontend.header')
@include('frontend.navbar')    
            <!-- Page Wrapper -->
            <div class="page-wrapper">
                
                <!-- Page Content -->
                <div class="content container-fluid">

                    <!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-sm-12">
								<h3 class="page-title">Profile</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="#">Dashboard</a></li>
									<li class="breadcrumb-item active">Profile</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->
                    
                    <div class="card mb-0">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="profile-view">
                                    @foreach ($dataArray['data'] as $company)
										<div class="profile-img-wrap">
											<div class="profile-img">
												<a href="#"><img src="{{asset($company['logo'])}}" alt="User Image"></a>
											</div>
											
										</div>
										<div class="profile-basic">
											<div class="row">
												<div class="col-md-5">
													<div class="profile-info">
                                        
														<h3 class="user-name m-t-0 mb-0">{{$company['companyName']}}</h3>
                                                        <h5 class="text-muted">FinTech</h5>
														
													</div>
												</div>
												<div class="col-md-7" style="border-left: 2px dashed #cccccc;">

													<ul class="personal-info">
														<li>
															<div class="title">Phone:</div>
															<div class="text"><a href="">{{$company['contactNumber']}}</a></div>
														</li>
														<li>
															<div class="title">Company ID:</div>
															<div class="text"><a href="">{{$company['company_id']}}</a></div>
														</li>
														<li>
															<div class="title">Details</div>
															<div class="text">{{$company['companyDetails']}}</div>
														</li>
														<li>
															<div class="title">Address:</div>
															<div class="text">{{$company['address']}}</div>
														</li>
                                                    
													</ul> 
												</div>
											</div>
										</div>
										<div class="pro-edit"><a data-bs-target="#profile_info" data-bs-toggle="modal" class="edit-icon profile_info" href="#"><i class="fa-solid fa-pencil"></i></a></div>
									</div>
								</div>
							</div>
						</div>
					</div>
                    
                    <div class="card tab-box">
						<div class="row user-tabs">
							<div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
								<ul class="nav nav-tabs nav-tabs-bottom">
									<li class="nav-item"><a href="#emp_profile" data-bs-toggle="tab" class="nav-link active">Profile</a></li>
									<!-- <li class="nav-item"><a href="#emp_projects" data-bs-toggle="tab" class="nav-link">Projects</a></li>
									<li class="nav-item"><a href="#bank_statutory" data-bs-toggle="tab" class="nav-link">Bank & Statutory <small class="text-danger">(Admin Only)</small></a></li>
									<li class="nav-item"><a href="#emp_assets" data-bs-toggle="tab" class="nav-link">Assets</a></li> -->
								</ul>
							</div>
						</div>
					</div>
                    
                    <div class="tab-content">
					
						<!-- Profile Info Tab -->
						<div id="emp_profile" class="pro-overview tab-pane fade show active">
							<div class="row">
								<div class="col-md-6 d-flex">
									<div class="card profile-box flex-fill">
										<div class="card-body">
											<h3 class="card-title">HR Informations </h3>
											<ul class="personal-info">
												<li>
													<div class="title">Name</div>
													
													<div class="text">{{$userDetails['emp_details'][0]['name']}}</div>
												</li>
												<li>
													<div class="title">Email</div>
													<div class="text">{{$userDetails['emp_details'][0]['email']}}</div>
												</li>
												<li>
													<div class="title">Phone Number</div>
													<div class="text">{{$userDetails['emp_details'][0]['phone_number']}}</div>
												</li>
												<li>
													<div class="title">Department</div>
													<div class="text"><a href="">{{$userDetails['emp_details'][0]['deptTitle']}}</a></div>
												</li>
												<li>
													<div class="title">Designation</div>
													<div class="text">{{$userDetails['emp_details'][0]['desigTitle']}}</div>
												</li>
												<li>
													<div class="title">Gender</div>
													<div class="text">{{$userDetails['emp_details'][0]['gender']}}</div>
												</li>
												<li>
													<div class="title">Date of Birth</div>
													<div class="text">{{$userDetails['emp_details'][0]['dob']}}</div>
												</li>
											</ul>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Modal -->
				<div id="profile_info" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Company Information</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="msform">
									<div class="row">
										<div class="col-md-12">
											<div class="profile-img-wrap edit-img">
												<img id="preview" class="inline-block" src="{{asset($company['logo'])}}" alt="User Image">
												<div class="fileupload btn">
													<span class="btn-text">edit</span>
													<input class="upload" type="file" id="imageInput" name="logo" accept="image/*">
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="input-block mb-3">
														<label class="col-form-label">Company Name</label>
														<input type="text" class="form-control" name="companyName" value="{{$company['companyName']}}">
													</div>
												</div>
												<div class="col-md-6">
													<div class="input-block mb-3">
														<label class="col-form-label">Phone Number</label>
														<input type="text" class="form-control" name="contactNumber" value="{{$company['contactNumber']}}">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="input-block mb-3">
												<label class="col-form-label">Address</label>
												<input type="text" class="form-control" name="address" value="{{$company['address']}}">
											</div>
										</div>
										<div class="col-md-12">
											<div class="input-block mb-3">
												<label class="col-form-label">Company Details</label>
												<input type="text" class="form-control" name="companyDetails" value="{{$company['companyDetails']}}">
											</div>
										</div>
									</div>
                                    @endforeach
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Profile Modal -->

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>

<script>

	document.getElementById('imageInput').addEventListener('change', function() {
		var file = this.files[0];
		var reader = new FileReader();

		reader.onload = function(e) {
			document.getElementById('preview').src = e.target.result;
		}

		reader.readAsDataURL(file);
	});


	$(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
		 var baseUrl = "{{ $baseUrl }}";
    $('#msform').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
                url: baseUrl +'/edit/company', 
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
                        title: 'Company Information Updated Successfully',
                        text: 'Your Company Information Update Was Successful!',
                        showConfirmButton: false, 
                        }); 
                        setTimeout(function() {
                            location.reload(); 
                        }, 500);
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
                    }
                }
            });
        });
    });
</script>