@include('frontend.header')
@include('frontend.navbar')
<style>
    .select2-container {
        width: 100% !important;
        z-index: 9999 !important;
    }

    .select2-selection {
        height: 38px !important; 
    }

    .select2-selection__arrow {
        height: 38px !important;
    }
</style>
<!-- Page Wrapper -->
<div class="page-wrapper">
			
    <!-- Page Content -->
    <div class="content container-fluid">
    
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">TimeLine Setting</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">TimeLine Setting</li>
                    </ul>
                </div>
                <div class="col-auto float-end ms-auto">
                    <a href="#" class="btn add-btn add-employee" data-bs-toggle="modal" data-bs-target="#add_employee" id="addEmployeeButton">
                        <i class="fa-solid fa-plus"></i> 
                        Add Employee into Timeline
                    </a>

                    <a style="margin-right: 10px;" href="{{route('employeeWiseTimeline')}}" class="btn add-btn add-employee">
                        <i class="fas fa-clock"></i>
                        Employee Timeline
                    </a>

                    <div class="view-icons">
                        <!-- <a href="employees.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                        <a href="employees-list.html" class="list-view btn btn-link active"><i class="fa-solid fa-bars"></i></a> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
       
        <!-- table start -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table" id="empTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Employee ID</th>
                                <th>fetch Time</th>
                                <th>Mobile</th>
                                <th class="text-end no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataArray['data'] as $Key => $timeline)
                            <tr>
                                <td>{{$Key+1}}</td>
                                <td>
                                    <h2 class="table-avatar">
                                        <a href="profile.html" class="avatar"><img src="{{asset($timeline['image'])}}" alt="User Image"></a>
                                        <a href="profile.html">{{$timeline['name']}}</a>
                                    </h2>
                                </td>
                                <td>{{$timeline['emp_id']}}</td>
                                <?php
                                    $fetch_time = $timeline['fetch_time'];
                                    if($fetch_time == "60 mins"){
                                        $fetch_time = "1 hour";
                                    }elseif($fetch_time == "120 mins"){
                                        $fetch_time = "2 hour";
                                    }elseif($fetch_time == "180 mins"){
                                        $fetch_time = "3 hour";
                                    }
                                ?>
                                <td>{{$fetch_time ?? 'N/A'}}</td>
                                <td>{{$timeline['phone_number'] ?? 'N/A'}}</td>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item edit-employee" href="#" data-bs-toggle="modal" data-bs-target="#edit_employee" id="editEmployeeButton" data-id="{{ $timeline['timeline_settings_id'] }}">
                                                <i class="fa-solid fa-pencil m-r-5"></i> Edit
                                            </a>
                                                <!-- <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_employee" id="editEmployeeButton" data-id="{{ $timeline['emp_id'] }}"><i class="fa-solid fa-pencil m-r-5" ></i> Edit</a> -->
                                            <a class="dropdown-item delete-employee" href="#" data-bs-toggle="modal" data-bs-target="#delete_employee" data-id="{{ $timeline['timeline_settings_id'] }}"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
    <!-- Add Employee Modal -->
    <div id="add_employee" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Employee Timeline</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="msform">
                    <div class="row">
                        <div class="form-group">
                            <label for="inputText4" class="col-form-label">Select Employee</label>
                            <select name="emp_id" id="emp_id_add" class="select2" required>
                                <option selected disabled>Open this to select Employee</option>
                                @foreach ($employee['data'] as $emp)
                                    <option value="{{$emp['emp_id']}}">{{$emp['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">fetch Time <span class="text-danger">*</span></label>
                                <select name="fetch_time" id="fetch_time" class="form-select" required>
                                    <option selected disabled>Open this to select fetch Time</option>
                                    <option value="10 mins">10 minute</option>
                                    <option value="15 mins">15 minute</option>
                                    <option value="30 mins">30 minute</option>
                                    <option value="60 mins">1 hour</option>
                                    <option value="120 mins">2 hour</option>
                                    <option value="180 mins">3 hour</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Employee Modal -->


<!-- Edit Employee Modal -->
<div id="edit_employee" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Employee</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="editSubmit">
									<div class="row">
                                        <div class="col-sm-12">  
											<div class="input-block mb-3">
												<label class="col-form-label">Employee ID <span class="text-danger">*</span></label>
												<input type="text" name="emp_id" id="emp_id" readonly class="form-control floating">
											</div>
										</div>

                                        <div class="col-sm-12">  
											<div class="input-block mb-3">
												<label class="col-form-label">Employee Name <span class="text-danger">*</span></label>
												<input type="text" name="name" id="name" readonly class="form-control floating">
											</div>
										</div>

                                        <div class="col-sm-12"> 
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">fetch Time <span class="text-danger">*</span></label>
                                                <select name="fetch_time" id="fetch_time" class="form-select" required>
                                                    <option value="10 mins">10 minute</option>
                                                    <option value="15 mins">15 minute</option>
                                                    <option value="30 mins">30 minute</option>
                                                    <option value="60 mins">1 hour</option>
                                                    <option value="120 mins">2 hour</option>
                                                    <option value="180 mins">3 hour</option>
                                                </select>
                                            </div>
                                        </div>
									</div>
                                    <input class="form-control" type="text" name="timeline_settings_id" id="timeline_settings_id" hidden>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Edit Employee Modal -->
				
				<!-- Delete Employee Modal -->
				<div class="modal custom-modal fade" id="delete_employee" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Delete Employee</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
                                        <form id="deptDelete">
                                            @csrf
                                            <input id ="timeline_settings_id" class="form-control" name="timeline_settings_id" type="hidden">
                                            <button style="padding: 10px 74px;" type="submit" class="btn btn-primary continue-btn">Delete</button>
                                        </form>										
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Employee Modal -->
				
            </div>
			<!-- /Page Wrapper -->
			
        </div>
		<!-- /Main Wrapper -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
        $('#empTable').DataTable();

        $(document).on('click', '.add-employee', function(){
            $('.select2').each(function () {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                });
            });
        });
    });

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#msform').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/add-timeline', 
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    $('#add_employee').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Employee added to timeline successfully',
                        text: 'Your Employee successfully added into timeline!',
                        showConfirmButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Loading...',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                    setTimeout(() => {
                                        try {
                                            location.reload(true); // Force a hard reload from the server
                                        } catch (error) {
                                            console.error(error);
                                        }
                                    }, 2000); // Set the time you want the loading icon to be visible (in milliseconds)
                                }
                            });
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
                        Swal.fire({
                            icon: 'error',
                            title: 'Something Went Wrong',
                            html: xhr.responseJSON.message
                        });
                    }
                }
            });
        });
    });

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
        $(document).on('click', '.edit-employee', function(){
            var timeline_settings_id = $(this).data('id');
            $.ajax({
                url: 'https://hrm.aamarpay.dev/api/individual-timeline-list/'+timeline_settings_id, 
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                success: function(response) {
                    console.log(response.data[0]);
                    $('#emp_id').val(response.data[0].emp_id);
                    $('#name').val(response.data[0].name);
                    $('#timeline_settings_id').val(timeline_settings_id);

                    var selectedFetchTime = response.data[0].fetch_time;

                    $('#fetch_time option').each(function() {
                        if ($(this).val() === selectedFetchTime) {
                            $(this).prop('selected', true);
                        } else {
                            $(this).prop('selected', false);
                        }
                    });
                    $('#edit_employee').modal('show');
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


    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#editSubmit').submit(function(e) {
        e.preventDefault();
        var timeline_settings_id = $('#timeline_settings_id').val();
        console.log(timeline_settings_id);

        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/edit-timeine/'+timeline_settings_id, 
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#edit_employee').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Employee Timeline Details Edited Successfully',
                        text: 'Your Employee Timeline Details Edit Was Successful!',
                        showConfirmButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Loading...',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                    setTimeout(() => {
                                        try {
                                            location.reload(true); 
                                        } catch (error) {
                                            console.error(error);
                                        }
                                    }, 2000); 
                                }
                            });
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
                    }
                }
            });
        });
    });
    
    $(document).ready(function() {
        $(document).on('click', '.delete-employee', function(){
            var timeline_settings_id = $(this).data('id');
            $('#timeline_settings_id').val(timeline_settings_id);
        });
    });

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#deptDelete').submit(function(e) {
        e.preventDefault();
        var timeline_settings_id = $('#timeline_settings_id').val();
        console.log(timeline_settings_id);
        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/delete-timeline/'+timeline_settings_id, 
                type: 'DELETE',
                data: formData,
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#delete_employee').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Employee Timeline Successfully Deleted',
                        text: 'You have successfully deleted a employee timeline',
                        showConfirmButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Loading...',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                    setTimeout(() => {
                                        try {
                                            location.reload(true); // Force a hard reload from the server
                                        } catch (error) {
                                            console.error(error);
                                        }
                                    }, 2000); // Set the time you want the loading icon to be visible (in milliseconds)
                                }
                            }); 
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
                    }
                }
            });
        });
    });
</script>