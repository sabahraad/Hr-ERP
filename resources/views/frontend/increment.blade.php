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
                    <h3 class="page-title">Salary Increment</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Increment</li>
                    </ul>
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
                                <th>Salary</th>
                                <th>joining_date</th>
                                <th>last_increment_date</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataArray['data'] as $sal)
                            <tr>
                                <td>{{$sal['emp_id']}}</td>
                                <td>
                                    <h2 class="table-avatar">
                                        <a href="profile.html" class="avatar"><img src="{{asset($sal['image'])}}" alt="User Image"></a>
                                        <a href="profile.html">{{$sal['name']}}</a>
                                    </h2>
                                </td>
                                <td>{{$sal['email']}}</td>
                                <td>{{$sal['phone_number'] ?? 'N/A'}}</td>
                                <td>{{$sal['salary'] ?? 'N/A'}}</td>
                                <td>{{$sal['joining_date']}}</td>
                                <td>{{$sal['last_increment_date']}}</td>
                                <td >
                                    <button class="btn btn-primary edit-employee" data-bs-toggle="modal" data-bs-target="#edit_employee" data-id="{{ $sal['emp_id'] }}">
                                        <i class="fa-solid fa-pencil m-r-5"></i> Change Salary
                                    </button>
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
   
<!-- Edit Employee Modal -->
<div id="edit_employee" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Salary Increment</h5>
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
                                        <div class="col-sm-6">
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="name" id="name" readonly>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" name="email" id="email" readonly>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Salary</label>
                                                <input class="form-control" type="number" name="old_salary" id="old_salary" readonly>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">  
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">New Salary</label>
                                                <div class="cal-icon"><input class="form-control" type="number" name="salary" id="salary" required></div>
                                            </div>
                                        </div>
                                        <input class="form-control" type="number" name="salaries_id" id="salaries_id" hidden>
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
            </div>
			<!-- /Page Wrapper -->
        </div>
		<!-- /Main Wrapper -->

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#empTable').DataTable();
        var jwtToken = "{{ $jwtToken }}";
        var baseUrl = "{{ $baseUrl }}";
        $(document).on('click', '.edit-employee', function(){
            var empId = $(this).data('id');
            $.ajax({
                url: baseUrl + '/individual-employee-salary-details/'+empId, 
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                success: function(response) {
                    console.log(response.data[0]);
                    $('#name').val(response.data[0].name.trim());
                    $('#email').val(response.data[0].email);
                    $('#old_salary').val(response.data[0].salary);
                    $('#emp_id').val(response.data[0].emp_id);
                    $('#salaries_id').val(response.data[0].salaries_id);
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

        $('#editSubmit').submit(function(e) {
            e.preventDefault();
            var salaries_id = $('#salaries_id').val();
            var formData = new FormData(this);

            $.ajax({
                url: baseUrl + '/change-employee-salary/'+salaries_id, 
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
                        title: 'Employee Increment Added Successfully',
                        text: 'Your Employee Salary Increment Was Successful!',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); 
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