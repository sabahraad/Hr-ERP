@include('frontend.header')
@include('frontend.navbar')
<style>
    .table {
            --bs-table-bg: transparent; /* Set it to a transparent color */
        }
    .bg-danger td {
            color: white; /* Set text color to white */
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
                            <h3 class="page-title">Leave Application List</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Leave Application List</li>
                            </ul>
                        </div>
                        <!-- <div class="col-auto float-end ms-auto">
                            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_department"><i class="fa-solid fa-plus"></i>Add Leave Application</a>
                        </div> -->
                    </div>
                </div>
                <!-- /Page Header -->

                <!-- <div class="card" style="border: 0;box-shadow: 0 0 20px 0 rgba(76,87,125,.2);">
                    <div class="card-body ">
                        <form id="myForm">
                            @csrf
                            <div class="form-group">
                                <label class="col-form-label" for="dateInput">Select Date:<span class="text-danger">*</span></label>
                                <input class="form-control" type="date" id="date" name="date" required>
                            </div>
                            <div class="form-group" style="margin-top: 18px;">
                                <input type="submit" name="submit" value="Search" class="btn btn-primary">
                            </div>
                        </form>

                    </div>
                </div> -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0" id="desigTable">
                                <thead>
                                    <tr>
                                        <th class="width-thirty">#</th>
                                        <th>Employee Name</th>
                                        <th>Strat Date</th>
                                        <th>End Date</th>
                                        <th>Count</th>
                                        <th>Leave Reason</th>
                                        <th>Status</th>
                                        <th >Action</th>
                                    </tr>
                                </thead>
                                @foreach ($leaveApplicationList['data'] as $key => $raw)

                                <tbody>
                                        <td>{{$key+1}}</td>
                                        <td>{{ $raw['employee']['name'] }}</td>
                                        <td>{{$raw['start_date']}}</td>
                                        <td>{{$raw['end_date']}}</td>
                                        <td>{{$raw['count']}}</td>
                                        <td>{{$raw['reason']}}</td>
                                        <td>
                                            @if($raw['status'] == 1)
                                                <span style="color: green;">Approved</span>
                                            @elseif($raw['status'] == 2)
                                                <span style="color: red;">Declined</span>
                                            @else
                                                <span style="color: gray;">Pending</span>
                                            @endif 
                                        </td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_department"><i class="fa-solid fa-pencil m-r-5" data-id="{{$raw['leave_application_id']}}"></i> Edit</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_department"><i class="fa-regular fa-trash-can m-r-5" data-id="{{$raw['leave_application_id']}}"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                      
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->

            <!-- Add Department Modal -->
            <div id="add_department" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Attendance</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="msform">
                                @csrf
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Employee Name <span class="text-danger">*</span></label>
                                    <select name="emp_id" class="select">
                                    <option selected disabled>Open this to select Employee</option>
                                       
                                    </select>
                                    <label class="col-form-label">Attendance<span class="text-danger">*</span></label>
                                    <select name="action" class="select">
                                        <option selected disabled>Open this to select your action</option>
                                        <option value="1">Check In</option>
                                        <option value="2">Check Out</option>
                                    </select>

                                    <label class="col-form-label" for="datetime">Select Date and Time:<span class="text-danger">*</span></label>
                                    <input class="form-control" type="datetime-local" id="datetime" name="datetime" required>
                                    
                                    <label class="col-form-label">Reason</label>
                                    <input class="form-control" type="text" name= "edit_reason">
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Add Department Modal -->

            <!-- Edit Department Modal -->
            <div id="edit_department" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Department</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editSubmit">
                            @csrf
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Employee Name</label>
                                    <input id ="name" class="form-control" name="name" type="text" disabled>
                                    <label class="col-form-label">Status<span class="text-danger">*</span></label>
                                    <select name="action" class="select">
                                        <option selected disabled>Open this to select your action</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Decline</option>
                                        <option value="3">Pending</option>
                                    </select>
                                    <input id ="leaveApplicationId" class="form-control" name="leaveApplicationId" type="hidden">
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Edit Department Modal -->

            <!-- Delete Department Modal -->
            <div class="modal custom-modal fade" id="delete_department" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Department</h3>
                                <p>Are you sure want to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                    <form id="deptDelete">
                                        @csrf
                                        <input id ="dept_id" class="form-control" name="dept_id" type="hidden">
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
            <!-- /Delete Department Modal -->
        
        </div>
        <!-- /Page Wrapper -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>

<script>

    $(document).ready(function(){
        $('#desigTable').DataTable();
    });

    $(document).ready(function() {
    $('#msform').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/attendance-add-by-HR', 
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
                        title: 'Attendance added successful',
                        text: 'Your attendance registration was successful!',
                        showConfirmButton: false, // Hide the OK button
                        }); 
                        setTimeout(function() {
                            location.reload(); // This will refresh the current page
                        }, 100);
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422 ) {
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
                            title: 'Validation Error',
                            html: xhr.responseJSON.message
                        });
                    }
                }
            });
        });
    });

    
    $(document).ready(function() {
		$('#myForm').submit(function(event) {
			event.preventDefault(); // Prevent the form from submitting normally
                
            var formData = new FormData(this);

			$.ajax({
			url: 'https://hrm.aamarpay.dev/api/present-employee-list',
			type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + jwtToken
            },
            data: formData,
            contentType: false,
            processData: false,
			success: function(response) {
                console.log(response)
                var table = $('#desigTable').DataTable();
				table.clear().draw();
                var rowNum = 1;

                // Iterate through the data and populate the table
                response.data.forEach(function(item) {
                    var createdAt = new Date(item.created_at).toLocaleString();
                    var updatedAt = item.OUT ? new Date(item.updated_at).toLocaleString() : 'N/A';
                    console.log(item.INstatus);
                    var rowColorClass = (item.INstatus === 2 || item.OUTstatus === 2) ? 'bg-danger' : '';
                    var editRoute = '{{ route("editAttendance", ["id" => ":id"]) }}'.replace(':id', item.attendance_id);
                    var rowData = [
                        rowNum,
                        '<td >' + (item.employee_name || 'N/A') + '</td>',
                        '<td >' + (createdAt !== 'null' ? createdAt : 'N/A') + '</td>',
                        '<td >' + (updatedAt !== 'N/A' ? updatedAt : 'N/A')  + '</td>',
                        '<td >' + (item.lateINreason || 'N/A') + '</td>',
                        '<td >' + (item.earlyOUTreason || 'N/A') + '</td>',
                        '<td >' + (item.edited_by_name || 'N/A') + '</td>',
                        '<td >' + (item.edit_reason || 'N/A') + '</td>',
                        '<td class="' + rowColorClass + '"><div class="dropdown dropdown-action"><a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a><div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="'+editRoute+'" ><i class="fa-solid fa-pencil m-r-5" data-id="'+item.attendance_id+'" ></i> Edit</a><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_designation" id="editEmployeeButton"><i class="fa-regular fa-trash-can m-r-5" data-id="'+item.attendance_id+'" ></i>Delete</a></div></div></td>'
                    ];
                    // table.row.add(rowData);
                    if (rowColorClass) {
                        table.row.add(rowData).node().classList.add(rowColorClass);
                    } else {
                        table.row.add(rowData);
                    }
                    // table.row.add(rowData).node().classList.add(rowColorClass);
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
                            title: 'No Attendance Found For This Date',
                        });
                } else {
                    console.log('Error in API call');
                }
            }
			});
		});
	});

    $(document).ready(function() {
    $('.dropdown-item[data-bs-target="#edit_department"]').click(function() {
        // Get the dept_id from the clicked element's data-id attribute
            var leaveApplicationId = $(this).find('.fa-pencil').data('id');

            // Log the dept_id to the console
            console.log(leaveApplicationId);
            var trElement = $(this).closest('tr');

            // Find the 'td' elements within the 'tr'
            var name = trElement.find('td:eq(1)').text();
            // var details = trElement.find('td:eq(2)').text();

            // Log the data to the console
            console.log('leaveApplicationId:', leaveApplicationId);
            console.log('name:', name);
            $('#name').val(name);
            $('#leaveApplicationId').val(leaveApplicationId);
            // Show the modal
            $('#edit_department').modal('show');
        });
    });

    $(document).on('click', '.dropdown-item[data-bs-target="#delete_designation"]', function() {
        var attendance_id = $(this).find('.fa-regular.fa-trash-can').data('id');
        console.log(attendance_id);
        $('#attendance_id').val(attendance_id);
    });
  
    $(document).ready(function() {
    $('#desigDelete').submit(function(e) {
        e.preventDefault();
        var attendance_id = $('#attendance_id').val();
        console.log(attendance_id);
        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/delete-attendance/'+attendance_id, 
                type: 'DELETE',
                data: formData,
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                contentType: false,
                processData: false,
                success: function(response) {
                    // var dept_id = response.data;
                    console.log(response);
                    $('#delete_designation').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Attendance successfully deleted',
                        text: 'You have successfully deleted a Attendance',
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