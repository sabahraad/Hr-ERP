@include('frontend.header')
@include('frontend.navbar')
<style>
    .select2-container {
        width: 100% !important;
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
                            <h3 class="page-title">Increment History</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Increment History</li>
                            </ul>
                        </div>
                        
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="card" style="border: 0;box-shadow: 0 0 20px 0 rgba(76,87,125,.2);">
                    <div class="card-body ">
                        <form id="myForm">
                            @csrf
                            <div class="form-group">
                                <label for="inputText4" class="col-form-label">Select Employee</label>
                                <select name="emp_id" id="emp_id" class="select">
                                    <option selected disabled>Open this to select Employee</option>
                                    @foreach ($dataArray['data'] as $emp)
                                        <option value="{{$emp['emp_id']}}">{{$emp['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin-top: 18px;">
                                <input type="submit" name="submit" value="Search" class="btn btn-primary">
                            </div>
                        </form>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0" id="desigTable">
                                <thead>
                                    <tr>
                                        <th class="width-thirty">#</th>
                                        <th>Name</th>
                                        <th>Employee ID</th>
                                        <th>Salary</th>
                                        <th>Joining Date</th>
                                        <th>last Increment Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->

            <!-- Add Designation Modal -->
            <div id="add_designation" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Designation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="desig_form">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Department</label>
                                    <select name="dept_id" id="dept_id1" class="select">
                                    <option selected disabled>Open this to select Department</option>
                                        
                                    </select>
                                </div>
                               
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Approver #1</label>
                                    <select name="emp_id[]" class="select">
                                        <option selected disabled>Open this to select Approver</option>
                                        
                                    </select>
                                </div>

                                <!-- Add IP button -->
                                <button type="button" onclick="addSelectField()">Add Approver</button>
                                <div id="ipInputsEdit">
                                        <!-- IP address input fields will be dynamically added here -->
                                </div>
                                <button type="button" class="btn btn-primary btn" style="margin: 15px;" onclick="addIpInput()">Add IP</button>
                                <button type="button" class="btn btn-primary btn" onclick="removeIpInput()">Cancel</button><br>
                                
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Add Designation Modal -->

            <!-- Edit Designation Modal -->
            <div id="edit_designation" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Designation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editSubmit">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Designation Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="desigTitle" id="desigTitle">
                                </div>
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Details<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="details" id="details">
                                </div>
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Department <span class="text-danger">*</span></label>
                                    <select name="dept_id" class="select">
                                    <option selected disabled>Open this to select Department</option>
                                        
                                    </select>
                                </div>
                                <input class="form-control" type="text" name="designation_id" id="designation_id" hidden>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Edit Designation Modal -->

            <!-- Delete Designation Modal -->
            <div class="modal custom-modal fade" id="delete_designation" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete leave Approver</h3>
                                <p>Are you sure want to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                    <form id="desigDelete">
                                        @csrf
                                        <input id ="approvers_id" class="form-control" name="approvers_id" type="hidden">
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
            <!-- /Delete Designation Modal -->
        
        </div>
        <!-- /Page Wrapper -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>


<script>

    $(document).ready(function(){
        $('#desigTable').DataTable();
    });
    
    $(document).ready(function() {
        $('#emp_id').select2();
    });

    
    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
		$('#myForm').submit(function(event) {
			event.preventDefault(); 
                
            var emp_id = $('#emp_id').val();
            console.log(emp_id);

			$.ajax({
			url: 'https://hrm.aamarpay.dev/api/employee-salary-history/'+emp_id,
			type: 'GET',
            headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
			success: function(response) {
                console.log(response.message)
                var table = $('#desigTable').DataTable();
				table.clear().draw();
                var rowNum = 1;
                // Iterate through the data and populate the table
                response.data.forEach(function(item) {

                    var rowData = [
                        rowNum,
                        item.name,
                        item.emp_id,
                        item.salary,
                        item.joining_date,
                        item.salary_update_date 
                    ];
                    table.row.add(rowData);
                    rowNum++;
                });
                
                table.draw();
			},
            error: function(xhr, textStatus, errorThrown) {
                if (xhr.status == 404) {
                    var table = $('#desigTable').DataTable();
				    table.clear().draw();
                    Swal.fire({
                            icon: 'error',
                            title: 'NO DATA FOUND',
                        });
                } else {
                    console.log('Error in API call');
                }
            }
			});
		});
	});


    $(document).on('click', '.dropdown-item[data-bs-target="#delete_designation"]', function() {
        var approvers_id = $(this).find('.fa-regular.fa-trash-can').data('id');
        // console.log(approvers_id);
        $('#approvers_id').val(approvers_id);
    });
  
    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#desigDelete').submit(function(e) {
        e.preventDefault();
        var approvers_id = $('#approvers_id').val();
        console.log(approvers_id);
        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/delete-approvers/'+approvers_id, 
                type: 'DELETE',
                data: formData,
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                contentType: false,
                processData: false,
                success: function(response) {
                    var dept_id = response.data;
                    console.log(dept_id);
                    $('#delete_designation').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Leave Approver successfully deleted',
                        text: 'You have successfully deleted a Leave Approver',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'https://hrm.aamarpay.dev/api/approvers-list/'+dept_id,
                                type: 'GET',
                                headers: {
                                        'Authorization': 'Bearer ' + jwtToken
                                    },
                                success: function(response) {
                                    var table = $('#desigTable').DataTable();
                                    table.clear().draw();
                                    var rowNum = 1;

                                    // Iterate through the data and populate the table
                                    response.data.forEach(function(item) {
                                        var rowData = [
                                            rowNum,
                                            '<td data-deptid="' + item.deptId + '">' + item.deptName + '</td>',
                                            '<td data-empid="' + item.emp_id + '">' + item.approver_name + '</td>',
                                            '<td >' + item.priority + '</td>',
                                            '<div class="dropdown dropdown-action"><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_designation"><i class="fa-regular fa-trash-can m-r-5" data-id="'+item.approvers_id+'"></i></a></div></div>'
                                        ];
                                        table.row.add(rowData);
                                        rowNum++;
                                    });
                                    
                                    table.draw();
                                },
                                error: function(xhr, textStatus, errorThrown) {
                                    if (xhr.status == 404) {
                                        var table = $('#desigTable').DataTable();
                                        table.clear().draw();
                                        Swal.fire({
                                                icon: 'error',
                                                title: 'No Leave Approver Found for this Department',
                                            });
                                    } else {
                                        console.log('Error in API call');
                                    }
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