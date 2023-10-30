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
                            <h3 class="page-title">Leave Setting</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Leave Setting</li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_department"><i class="fa-solid fa-plus"></i> Add Leave Type</a>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <table class="table table-striped custom-table mb-0 " id="leaveTypeTable">
                                <thead>
                                    <tr>
                                        <th class="width-thirty">#</th>
                                        <th>Leave Type</th>
                                        <th>Days</th>
                                        <th >Action</th>
                                        <!-- class="text-end" -->
                                    </tr>
                                </thead>
                                <tbody>
                                @if($dataArray === null || empty($dataArray['data']))
                                <tr><td colspan="4" class="text-center">No leave is available</td></tr>
                                @else
                                    @foreach($dataArray['data'] as $key =>$leave)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$leave['leave_type']}}</td>
                                        <td>{{$leave['days']}}</td>
                                        <td>
                                        <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_department"><i class="fa-solid fa-pencil m-r-5" data-id="{{ $leave['leave_setting_id'] }}"></i> Edit</a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_department"><i class="fa-regular fa-trash-can m-r-5" data-id="{{ $leave['leave_setting_id'] }}"></i> Delete</a>
                                            </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
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
                            <h5 class="modal-title">Add Leave Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="msform">
                                @csrf
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Leave Type<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name= "leave_type" >
                                    <label class="col-form-label">Days<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name= "days">
                                    <input class="form-control" type="text" name= "status" value="1" hidden>
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
                            <h5 class="modal-title">Edit Leave Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editSubmit">
                            @csrf
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Leave Type Name</label>
                                    <input id ="deptName" class="form-control" name="leave_type" type="text">
                                    <label class="col-form-label">Days </label>
                                    <input id ="details" class="form-control" name="days" type="text">
                                    <input id ="dept_id" class="form-control" name="dept_id" type="hidden">
                                    <input class="form-control" type="text" name= "status" value="1" hidden>
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
                                <h3>Delete Leave Type</h3>
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
        $('#leaveTypeTable').DataTable();
    });
    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#msform').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/add-leave-setting', 
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
                        title: 'leave type added successful',
                        text: 'Your leave type registration was successful!',
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
    $('.dropdown-item[data-bs-target="#edit_department"]').click(function() {
        // Get the dept_id from the clicked element's data-id attribute
            var leaveID = $(this).find('.fa-pencil').data('id');

            // Log the dept_id to the console
            console.log(leaveID);
            var trElement = $(this).closest('tr');

            // Find the 'td' elements within the 'tr'
            var leave_type = trElement.find('td:eq(1)').text();
            var days = trElement.find('td:eq(2)').text();

            // Log the data to the console
            console.log('leave_type:', leave_type);
            console.log('days:', days);
            $('#deptName').val(leave_type);
            $('#details').val(days);
            $('#dept_id').val(leaveID);
            // Show the modal
            $('#edit_department').modal('show');
        });
    });



    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#editSubmit').submit(function(e) {
        e.preventDefault();
        var leaveID = $('#dept_id').val();
        console.log(leaveID);

        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/edit/leave-setting/'+leaveID, 
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
                        title: 'leave type Edited successfully',
                        text: 'Your leave type edit was successful!',
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
        $('.dropdown-item[data-bs-target="#delete_department"]').click(function() {
            // Get the dept_id from the clicked element's data-id attribute
            var deptId = $(this).find('.fa-regular').data('id');
            // Log the dept_id to the console
            console.log(deptId);
            var trElement = $(this).closest('tr');
            $('#dept_id').val(deptId);
        });
    });





    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#deptDelete').submit(function(e) {
        e.preventDefault();
        var leaveID = $('#dept_id').val();
        console.log(leaveID);
        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/delete/leave-setting/'+leaveID, 
                type: 'POST',
                data: formData,
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'leave type successfully deleted',
                        text: 'You have successfully deleted a leave type',
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