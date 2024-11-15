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
                            <h3 class="page-title">Expenses</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Expenses</li>
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
                                        <th>Expense Amount</th>
                                        <th>Expense Catagory</th>
                                        <th>Phone Number</th>
                                        <th>Attachment</th>
                                        <th>Status</th>
                                        <th >Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($dataArray['data'] as $key => $raw)

                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ $raw['name'] }}</td>
                                        <td>{{$raw['total_amount']}}</td>
                                        <td>
                                            {{ $raw['catagory'] }}
                                        </td>
                                        <td>{{$raw['phone_number']}}</td>
                                        <td>
                                            @if($raw['attachment'] == Null)
                                                <div >
                                                    <h4>N/A</h4>
                                                </div>
                                            @else
                                                @php
                                                    $imageExtensions = ['jpeg', 'png', 'gif', 'svg', 'jpg'];
                                                    $pdfExtensions = ['pdf'];
                                                    $excelExtensions = ['xlsx','xls'];
                                                    $extension = pathinfo($raw['attachment'], PATHINFO_EXTENSION);
                                                @endphp

                                                @if (in_array(strtolower($extension), $imageExtensions))
                                                    <div>
                                                        <br>
                                                        <a class="btn btn-primary" href="{{ asset($raw['attachment']) }}" download>Download Image</a>
                                                    </div>
                                                @elseif (in_array(strtolower($extension), $pdfExtensions))
                                                    <br>
                                                    <a class="btn btn-primary" href="{{ url('/') . '/' . $raw['attachment'] }}" download>Download PDF</a>
                                                @elseif (in_array(strtolower($extension), $excelExtensions))
                                                    <a class="btn btn-primary" href="{{ url('/') . '/' . $raw['attachment'] }}" download>Download Excel File</a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($raw['status'] == "pending")
                                                <span style="color: gray;">Pending</span>
                                            @elseif($raw['status'] == "approved")
                                                <span class="badge badge-success">Approved</span>
                                            @else
                                                <span class="badge badge-danger">Declined</span>
                                            @endif 
                                        </td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item edit-leaveApplication" href="#" data-bs-toggle="modal" data-bs-target="#edit_leaveApplication" id="editleaveApplicationButton" data-id="{{$raw['expenses_id']}}">
                                                            <i class="fa-solid fa-pencil m-r-5"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item delete-leaveApplication" href="#" data-bs-toggle="modal" data-bs-target="#delete_leaveApplication" data-id="{{$raw['expenses_id']}}"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
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

            <!-- Edit Department Modal -->
            <div id="edit_leaveApplication" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Expense</h5>
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
                                    <label class="col-form-label">Catagory</label>
                                    <input id ="catagory" class="form-control" name="catagory" type="text" disabled>
                                    <label class="col-form-label">Description</label>
                                    <input id ="description" class="form-control" name="description" type="text" disabled>
                                    <label class="col-form-label">total_amount</label>
                                    <input id ="total_amount" class="form-control" name="total_amount" type="text" disabled>
                                    <label class="col-form-label">Status<span class="text-danger">*</span></label>
                                    <select name="status" class="select">
                                        <option selected disabled>Open this to select your action</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Decline</option>
                                        <option value="0">Pending</option>
                                    </select>
                                    <input id ="expenses_id" class="form-control" name="expenses_id" type="hidden">
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
            var expenses_id = $(this).data('id');
            console.log(expenses_id);
            $.ajax({
                url: baseUrl + '/expense-details/' +expenses_id, 
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                success: function(response) {
                    console.log(response.data[0]);
                    $('#name').val(response.data[0].name.trim());
                    $('#catagory').val(response.data[0].catagory);
                    $('#description').val(response.data[0].description);
                    $('#total_amount').val(response.data[0].total_amount);
                    $('#expenses_id').val(expenses_id);
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
            var expenses_id = $('#expenses_id').val();
            // console.log(leaveApplicationId);

            var formData = new FormData(this);

            $.ajax({
                    url: baseUrl +'/approve-expense', 
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
                                title: 'Expense status made pending',
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
                                title: 'Expense approved successfully',
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
                                title: 'Expense rejected successfully',
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