@include('frontend.header')
@include('frontend.navbar')
<style>
    .dt-button{
      color: white !important;
      background-color: #ff9b44 !important;
      cursor: pointer;
      border-radius: 5px;
      border: none;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      /* margin: 4px 2px; */
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
                            <h3 class="page-title">Department</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Department</li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_department"><i class="fa-solid fa-plus"></i> Add Department</a>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0" id="deptTable">
                                <thead>
                                    <tr>
                                        <th class="width-thirty">#</th>
                                        <th>Department Name</th>
                                        <th>Department Id</th>
                                        <th>Department Description</th>
                                        <th >Action</th>
                                        <!-- class="text-end" -->
                                    </tr>
                                </thead>
                                <tbody>
                                @if($dataArray === null || empty($dataArray['data']))
                                <tr><td colspan="4" class="text-center">No department is available</td></tr>
                                @else
                                    @foreach($dataArray['data'] as $key =>$department)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$department['deptTitle']}}</td>
                                        <td>{{$department['dept_id']}}</td>
                                        <td>{{$department['details']}}</td>
                                        <td>
                                        <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item edit-dept" href="#" data-bs-toggle="modal" data-bs-target="#edit_department" data-id="{{ $department['dept_id'] }}"><i class="fa-solid fa-pencil m-r-5" ></i> Edit</a>
                                                <a class="dropdown-item delete-dept" href="#" data-bs-toggle="modal" data-bs-target="#delete_department"  data-id="{{ $department['dept_id'] }}"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
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
                            <h5 class="modal-title">Add Department</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="msform">
                                @csrf
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Department Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name= "deptTitle" >
                                    <label class="col-form-label">Department Description</label>
                                    <input class="form-control" type="text" name= "details">
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
                                    <label class="col-form-label">Department Name</label>
                                    <input id ="deptName" class="form-control" name="deptTitle" type="text">
                                    <label class="col-form-label">Department Description </label>
                                    <input id ="details" class="form-control" name="details" type="text">
                                    <input id ="dept_id" class="form-control" name="dept_id" type="hidden">
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

<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>

<script>
    
    $(document).ready(function() {
        $('#deptTable').DataTable({
        dom: 'Bfrtip', 
        buttons: [
            {
                extend: 'excel',
                text: 'Download Excel',
                exportOptions: {
                    columns: [0, 1, 2, 3] 
                }
            }
        ]
        });
    });

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
        var baseUrl = "{{ $baseUrl }}";
        $('#msform').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                    url: baseUrl + '/add-department', 
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

        $(document).on('click', '.edit-dept', function(){
            var dept_id = $(this).data('id');
            console.log(dept_id,'ok');
            $.ajax({
                url: baseUrl + '/deptDetails/'+dept_id, 
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                success: function(response) {
                    console.log(response.data);
                    $('#deptName').val(response.data.deptTitle);
                    $('#details').val(response.data.details);
                    $('#dept_id').val(response.data.dept_id);
                    // Show the modal
                    $('#edit_department').modal('show');

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
                            title: 'Validation Error',
                            html: xhr.responseJSON.message
                        });
                    }
                }
            });
        });

        $('#editSubmit').submit(function(e) {
            e.preventDefault();
            var dept_id = $('#dept_id').val();
            // console.log(dept_id);

            var formData = new FormData(this);

            $.ajax({
                    url: baseUrl + '/edit/department/'+dept_id, 
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


        $(document).on('click', '.delete-dept', function(){
            var dept_id = $(this).data('id');
            console.log(dept_id,'ok');
            $('#dept_id').val(dept_id);
        });

        $('#deptDelete').submit(function(e) {
            e.preventDefault();
            var dept_id = $('#dept_id').val();
            console.log(dept_id);
            var formData = new FormData(this);

            $.ajax({
                    url: baseUrl + '/delete/department/'+dept_id, 
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