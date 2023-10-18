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
                            <h3 class="page-title">Designations</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Designations</li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_designation"><i class="fa-solid fa-plus"></i> Add Designation</a>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0 datatable">
                                <thead>
                                    <tr>
                                        <th class="width-thirty">#</th>
                                        <th>Designation </th>
                                        <th>Department </th>
                                        <th >Action</th>
                                    </tr>
                                </thead>
                                <!-- $title = $data->department->deptTitle -->
                                <tbody>
                                @if($dataArray === null || empty($dataArray['data']))
                                    <tr><td colspan="4" class="text-center">No department is available</td></tr>
                                @else
                                    @foreach($dataArray['data'] as $key =>$designations)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$designations['desigTitle']}}</td>
                                        @php
                                            $data = \App\Models\Designation::find($designations['dept_id']);
                                            
                                        @endphp
                                        <td>{{$designations['dept_id']}}</td>
                                        <td >
                                        <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_designation"><i class="fa-solid fa-pencil m-r-5" data-id="{{ $designations['designation_id'] }}"></i> Edit</a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_designation"><i class="fa-regular fa-trash-can m-r-5" data-id="{{ $designations['designation_id'] }}"></i> Delete</a>
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
                                    <label class="col-form-label">Designation Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text">
                                </div>
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Department</label>
                                    
                                </div>
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
                            <form>
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Designation Name <span class="text-danger">*</span></label>
                                    <input class="form-control" value="Web Developer" type="text">
                                </div>
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Department <span class="text-danger">*</span></label>
                                    
                                </div>
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
                                <h3>Delete Designation</h3>
                                <p>Are you sure want to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>

<script>

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#desig_form').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/add-department', 
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
                        title: 'Department added successful',
                        text: 'Your department registration was successful!',
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
            var deptId = $(this).find('.fa-pencil').data('id');

            // Log the dept_id to the console
            console.log(deptId);
            var trElement = $(this).closest('tr');

            // Find the 'td' elements within the 'tr'
            var deptTitle = trElement.find('td:eq(1)').text();
            var details = trElement.find('td:eq(2)').text();

            // Log the data to the console
            console.log('deptTitle:', deptTitle);
            console.log('details:', details);
            $('#deptName').val(deptTitle);
            $('#details').val(details);
            $('#dept_id').val(deptId);
            // Show the modal
            $('#edit_department').modal('show');
        });
    });



    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#editSubmit').submit(function(e) {
        e.preventDefault();
        var dept_id = $('#dept_id').val();
        console.log(dept_id);

        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/edit/department/'+dept_id, 
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
                        title: 'Department Edited successfully',
                        text: 'Your department edit was successful!',
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
        var dept_id = $('#dept_id').val();
        console.log(dept_id);
        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/delete/department/'+dept_id, 
                type: 'POST',
                data: formData,
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Department successfully deleted',
                        text: 'You have successfully deleted a department',
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