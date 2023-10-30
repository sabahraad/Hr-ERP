@include('frontend.header')
@include('frontend.navbar')
<!-- Page Wrapper -->
<div class="page-wrapper">
			
    <!-- Page Content -->
    <div class="content container-fluid">
    
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Employee</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Employee</li>
                    </ul>
                </div>
                <div class="col-auto float-end ms-auto">
                    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_employee" id="addEmployeeButton"><i class="fa-solid fa-plus"></i> Add Employee</a>
                    <div class="view-icons">
                        <a href="employees.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                        <a href="employees-list.html" class="list-view btn btn-link active"><i class="fa-solid fa-bars"></i></a>
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
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>DOB</th>
                                <th>Departement</th>
                                <th>Designation</th>
                                <th class="text-end no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataArray['data'] as $employee)
                            <tr>
                                <td>{{$employee['emp_id']}}</td>
                                <td>
                                    <h2 class="table-avatar">
                                        <a href="profile.html" class="avatar"><img src="{{asset($employee['image'])}}" alt="User Image"></a>
                                        <a href="profile.html">{{$employee['name']}}</a>
                                    </h2>
                                </td>
                                <td>{{$employee['email']}}</td>
                                <td>{{$employee['phone_number'] ?? 'N/A'}}</td>
                                <td>{{$employee['dob'] ?? 'N/A'}}</td>
                                <td>{{$employee['deptTitle']}}</td>
                                <td>{{$employee['desigTitle']}}</td>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_employee" id="editEmployeeButton"><i class="fa-solid fa-pencil m-r-5" data-id="{{ $employee['emp_id'] }}" ></i> Edit</a>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_employee"><i class="fa-regular fa-trash-can m-r-5" data-id="{{ $employee['emp_id'] }}" ></i> Delete</a>
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
                <h5 class="modal-title">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="msform">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="email">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="col-form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <input class="form-control" type="password" name="password" id="password1">
                                <span class="input-group-text">
                                    <span class="fa-solid fa-eye-slash" id="toggle-password" style="cursor: pointer"></span>
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="col-form-label">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation">
                                <span class="input-group-text">
                                    <span class="fa-solid fa-eye-slash" id="toggle-password-confirmation" style="cursor: pointer"></span>
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Phone Number</label>
                                <input class="form-control" type="number" name="phone_number">
                            </div>
                        </div>

                        <div class="col-sm-6">  
                            <div class="input-block mb-3">
                                <label class="col-form-label">Date of Birth</label>
                                <div class="cal-icon"><input class="form-control datetimepicker" type="text" name="dob"></div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Profile picture</label>
                                <input class="form-control" type="file" accept="image/png, image/gif, image/jpeg, image/jpg" name="image">
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Gender</label>
                                <select class="select" name="gender">
                                    <option selected disabled>Select Gender</option>                                   
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Department <span class="text-danger">*</span></label>
                                <select class="select" id="selectDept" name="dept_id">
                                    <option selected disabled>Select Department</option>                                   
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Designation <span class="text-danger">*</span></label>
                                <select class="select" id="desig_id" name="designation_id">
                                    <option selected disabled>Select Designation</option>                                   
                                </select>
                            </div>
                        </div>
                        <input class="form-control" type="number" name="status" value="1" hidden>
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
												<input type="text" name="emp_id" id="empID" readonly class="form-control floating">
											</div>
										</div>
                                        <div class="col-sm-6">
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="name" id="name">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="email" id="email">
                                            </div>
                                        </div>

                                        

                                        <div class="col-sm-6">
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Phone Number</label>
                                                <input class="form-control" type="number" name="phone_number" id="phoneNumber">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">  
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Date of Birth</label>
                                                <div class="cal-icon"><input class="form-control datetimepicker" type="text" name="dob" id="dob"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Profile picture</label>
                                                <input class="form-control" type="file" accept="image/png, image/gif, image/jpeg, image/jpg" name="image">
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Gender</label>
                                                <select class="select" name="gender">
                                                    <option selected disabled>Select Gender</option>                                   
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>
                                        </div>
										<div class="col-md-6">
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Department <span class="text-danger">*</span></label>
                                                <select class="select" id="selectDept1" name="dept_id">
                                                    <option selected disabled>Select Department</option>                                   
                                                </select>
                                            </div>
                                        </div>
										
                                        <div class="col-md-6">
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Designation <span class="text-danger">*</span></label>
                                                <select class="select" id="desig_id1" name="designation_id">
                                                    <option selected disabled>Select Designation</option>                                   
                                                </select>
                                            </div>
                                        </div>
									</div>
									
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
                                            <input id ="emp_id" class="form-control" name="emp_id" type="hidden">
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

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#toggle-password').click(function() {
            
            var passwordInput = $('#password1');
            var icon = $('#toggle-password');
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });
    });

    $(document).ready(function() {
        $('#toggle-password-confirmation').click(function() {
            
            var passwordInput = $('#password_confirmation');
            var icon = $('#toggle-password-confirmation');
            if (passwordInput.attr('type') === 'password') {
                
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });
    });

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
        $('#empTable').DataTable();
        
        $('#addEmployeeButton, #editEmployeeButton').on('click', function(e) {
            e.preventDefault();
            console.log('ok')
            // Make an AJAX call
            $.ajax({
                url: 'https://hrm.aamarpay.dev/api/department-name-list', 
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                success: function(data) {
                    $('#selectDept option:not(:first-child)').remove();
                    $('#selectDept1 option:not(:first-child)').remove();

                    $.each(data, function(value, text) {
                        $('#selectDept').append($('<option>').text(text).attr('value', value));
                        $('#selectDept1').append($('<option>').text(text).attr('value', value));
                    });
                    console.log(data);
                },
                error: function(error) {
                    // Handle any errors
                    console.error(error);
                }
            });
        });
    });

    $(document).on('change', '#selectDept', function() {
        console.log('okllll');
        var jwtToken = "{{ $jwtToken }}";
        var dept_id = $(this).val();
        console.log(dept_id);

        $.ajax({
            url: 'https://hrm.aamarpay.dev/api/designation-name-list/'+dept_id , 
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + jwtToken
            },
            success: function(data) {
                console.log(data);
                $('#desig_id option:not(:first-child)').remove();
                // Handle the response from the server
                $.each(data, function(value, text) {
                    $('#desig_id').append($('<option>').text(text).attr('value', value));
                });
                console.log(data);
            },
            error: function(error) {
                console.error(error);
            }
        });
    });

    $(document).on('change', '#selectDept1', function() {
        var jwtToken = "{{ $jwtToken }}";
        var dept_id = $(this).val();
        console.log(dept_id);

        $.ajax({
            url: 'https://hrm.aamarpay.dev/api/designation-name-list/'+dept_id , 
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + jwtToken
            },
            success: function(data) {
                console.log(data);
                $('#desig_id1 option:not(:first-child)').remove();
                // Handle the response from the server
                $.each(data, function(value, text) {
                    $('#desig_id1').append($('<option>').text(text).attr('value', value));
                });
                console.log(data);
            },
            error: function(error) {
                console.error(error);
            }
        });
    });

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#msform').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/add-employee', 
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
                        title: 'Employee added successful',
                        text: 'Your Employee registration was successful!',
                        showConfirmButton: false, // Hide the OK button
                        }); 
                        setTimeout(function() {
                            location.reload(); // This will refresh the current page
                        }, 100);
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
    $('.dropdown-item[data-bs-target="#edit_employee"]').click(function() {
        // Get the emp_id from the clicked element's data-id attribute
            var empID = $(this).find('.fa-pencil').data('id');

            // Log the emp_id to the console
            console.log(empID);
            var trElement = $(this).closest('tr');

            // Find the 'td' elements within the 'tr'
            var name = trElement.find('td:eq(1)').text();
            var email = trElement.find('td:eq(2)').text();
            var phoneNumber = trElement.find('td:eq(3)').text();
            var dob = trElement.find('td:eq(4)').text();

            $('#name').val(name.trim());
            $('#email').val(email);
            $('#phoneNumber').val(phoneNumber);
            $('#dob').val(dob);
            $('#empID').val(empID);
            // Show the modal
            $('#edit_employee').modal('show');
        });
    });

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#editSubmit').submit(function(e) {
        e.preventDefault();
        var emp_id = $('#empID').val();
        console.log(emp_id);

        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/edit/employee/'+emp_id, 
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Employee Edited successfully',
                        text: 'Your Employee edit was successful!',
                        showConfirmButton: false, 
                    });
                    setTimeout(function() {
                            location.reload(); // This will refresh the current page
                        }, 200);

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
        $('.dropdown-item[data-bs-target="#delete_employee"]').click(function() {
            // Get the dept_id from the clicked element's data-id attribute
            var emp_id = $(this).find('.fa-regular').data('id');
            // Log the dept_id to the console
            console.log(emp_id);
            var trElement = $(this).closest('tr');
            $('#emp_id').val(emp_id);
        });
    });





    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#deptDelete').submit(function(e) {
        e.preventDefault();
        var emp_id = $('#emp_id').val();
        console.log(emp_id);
        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/delete/employee/'+emp_id, 
                type: 'DELETE',
                data: formData,
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Employee successfully deleted',
                        text: 'You have successfully deleted a employee',
                        showConfirmButton: false, 
                    });
                    setTimeout(function() {
                        location.reload(); // This will refresh the current page
                    },200);
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