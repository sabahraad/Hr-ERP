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
                            <h3 class="page-title">Today's Leave Application</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Today's Leave Application</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0" id="desigTable">
                                <thead>
                                    <tr>
                                        <th class="width-thirty">#</th>
                                        <th>Employee Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Count</th>
                                        <th>Leave Reason</th>
                                        <th>Status</th>
                                        <th >Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                               
                                @foreach ($data as $key => $raw)

                                    <tr>
                                    <td>{{$key+1}}</td>
                                        <td>{{ $raw->name }}</td>
                                        <td>{{$raw->start_date}}</td>
                                        <td>{{$raw->end_date}}</td>
                                        <td>{{$raw->count}}</td>
                                        <td>{{$raw->reason}}</td>
                                        <td>
                                            
                                            @if($raw->leave_status == 1)
                                                <span style="color: green;">Approved</span>
                                            @elseif($raw->leave_status == 2)
                                                <span style="color: red;">Declined</span>
                                            @else
                                                <span style="color: gray;">Pending</span>
                                            @endif 
                                        </td>
                                        <td>
                                            <a class="dropdown-item edit-leaveApplication" href="#" data-bs-toggle="modal" data-bs-target="#edit_leaveApplication" id="editleaveApplicationButton" data-id="{{$raw->leave_application_id}}">
                                                <i class="fa-solid fa-pencil m-r-5"></i> Edit
                                            </a>
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

            <!-- Edit Department Modal -->
            <div id="edit_leaveApplication" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Leave Application</h5>
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
                                    <label class="col-form-label">leave start date</label>
                                    <input id ="start_date" class="form-control" name="start_date" type="text" disabled>
                                    <label class="col-form-label">leave end date</label>
                                    <input id ="end_date" class="form-control" name="end_date" type="text" disabled>
                                    <label class="col-form-label">Reason</label>
                                    <input id ="reason" class="form-control" name="reason" type="text" disabled>
                                    <label class="col-form-label">Status<span class="text-danger">*</span></label>
                                    <select name="status" class="select">
                                        <option selected disabled>Open this to select your action</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Decline</option>
                                        <option value="0">Pending</option>
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
            <div class="modal custom-modal fade" id="delete_leaveApplication" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Leave Application</h3>
                                <p>Are you sure want to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                    <form id="deptDelete">
                                        @csrf
                                        <input id ="leave_application_id" class="form-control" name="leave_application_id" type="hidden">
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
        var jwtToken = "{{ $jwtToken }}";
        var baseUrl = "{{ $baseUrl }}";
        $(document).on('click', '.edit-leaveApplication', function(){
            var leaveApplicationID = $(this).data('id');
            $.ajax({
                url: baseUrl + '/leave-application-details/' +leaveApplicationID, 
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                success: function(response) {
                    console.log(response.data[0]);
                    $('#name').val(response.data[0].name.trim());
                    $('#start_date').val(response.data[0].start_date);
                    $('#end_date').val(response.data[0].end_date);
                    $('#reason').val(response.data[0].reason);
                    $('#leaveApplicationId').val(leaveApplicationID);
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
            var leaveApplicationId = $('#leaveApplicationId').val();
            // console.log(leaveApplicationId);

            var formData = new FormData(this);

            $.ajax({
                    url: baseUrl +'/leave-approved-by-HR', 
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + jwtToken
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.data == 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Leave application status made pending',
                                showConfirmButton: true,  // Set to true to show the "OK" button
                                allowOutsideClick: false, // Prevent closing by clicking outside the modal
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // User clicked "OK"
                                    location.reload(); // Reload the current page
                                }
                            });                          
                        } else if (response.data == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Leave application approved successfully',
                                showConfirmButton: true,  // Set to true to show the "OK" button
                                allowOutsideClick: false, // Prevent closing by clicking outside the modal
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // User clicked "OK"
                                    location.reload(); // Reload the current page
                                }
                            });                     
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Leave application rejected successfully',
                                showConfirmButton: true,  // Set to true to show the "OK" button
                                allowOutsideClick: false, // Prevent closing by clicking outside the modal
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // User clicked "OK"
                                    location.reload(); // Reload the current page
                                }
                            });     
                        }
                        

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

        $(document).on('click', '.delete-leaveApplication', function(){
            var leave_application_id = $(this).data('id');
            $('#leave_application_id').val(leave_application_id);
        });

        $('#deptDelete').submit(function(e) {
            e.preventDefault();
            var leave_application_id = $('#leave_application_id').val();
            // console.log(leave_application_id);
            var formData = new FormData(this);

            $.ajax({
                    url: baseUrl + '/delete-leave-application/'+leave_application_id, 
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
                            title: 'Leave Application successfully deleted',
                            text: 'You have successfully deleted a leave Application',
                            showConfirmButton: false, 
                        });
                        setTimeout(function() {
                            location.reload(); // This will refresh the current page
                        },400);
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