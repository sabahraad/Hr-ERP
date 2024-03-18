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
                            <h3 class="page-title">Holidays</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Holidays</li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_holiday"><i class="fa-solid fa-plus"></i> Add Holiday</a>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0" id="holidayTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title </th>
                                        <th>Holiday Date</th>
                                        <th >Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if($dataArray === null || empty($dataArray['data']))
                                <tr><td colspan="5" class="text-center">No Holidays is available</td></tr>
                                @else
                                    @foreach($dataArray['data'] as $key =>$holiday)
                                    <tr class="holiday-upcoming">
                                        <td>{{$key+1}}</td>
                                        <td>{{$holiday['reason']}}</td>
                                        <td>{{$holiday['date']}}</td>
                                        <td >
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_holiday"><i class="fa-solid fa-pencil m-r-5" data-id="{{ $holiday['holidays_id'] }}"></i> Edit</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_holiday"><i class="fa-regular fa-trash-can m-r-5" data-id="{{ $holiday['holidays_id'] }}"></i> Delete</a>
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
            
            <!-- Add Holiday Modal -->
            <div class="modal custom-modal fade" id="add_holiday" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Holiday</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id = "msform">
                                @csrf
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Holiday Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="reason">
                                </div>
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Holiday Date <span class="text-danger">*</span></label>
                                    <!-- <div class="cal-icon"><input id="holidayDate" class="form-control datetimepicker" name="date" ></div> -->
                                    <div class="cal-icon"><input class="form-control " name="date" type="date"></div>
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Add Holiday Modal -->
            
            <!-- Edit Holiday Modal -->
            <div class="modal custom-modal fade" id="edit_holiday" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Holiday</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editSubmit">
                                @csrf
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Holiday Name <span class="text-danger">*</span></label>
                                    <input class="form-control" id="holidayName" type="text" name="reason">
                                </div>
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Holiday Date <span class="text-danger">*</span></label>
                                    <div class="cal-icon"><input class="form-control " name="date" type="date"></div>

                                    <!-- <div class="cal-icon"><input id="holidayDate" class="form-control datetimepicker" name="date" ></div> -->
                                </div>
                                <input id ="holidays_id" class="form-control" name="holidays_id" type="hidden">
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Edit Holiday Modal -->

            <!-- Delete Holiday Modal -->
            <div class="modal custom-modal fade" id="delete_holiday" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Holiday</h3>
                                <p>Are you sure want to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                    <form id="holidayDelete">
                                        @csrf
                                        <input id ="holidays_id" class="form-control" name="holidays_id" type="hidden">
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
            <!-- /Delete Holiday Modal -->
            
        </div>
        <!-- /Page Wrapper -->
        
    </div>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#holidayTable').DataTable();
            var jwtToken = "{{ $jwtToken }}";
            var baseUrl = "{{ $baseUrl }}";
            $('#msform').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: baseUrl + '/add-holiday', 
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
                            title: 'Holiday added successful',
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

            $('.dropdown-item[data-bs-target="#edit_holiday"]').click(function() {
                // Get the dept_id from the clicked element's data-id attribute
                var holidays_id = $(this).find('.fa-pencil').data('id');

                // Log the dept_id to the console
                console.log(holidays_id);
                var trElement = $(this).closest('tr');

                // Find the 'td' elements within the 'tr'
                var holidayName = trElement.find('td:eq(1)').text();
                var holidayDate = trElement.find('td:eq(2)').text();

                // Log the data to the console
                console.log('holidayName:', holidayName);
                console.log('holidayDate:', holidayDate);
                $('#holidayName').val(holidayName);
                $('#holidayDate').val(holidayDate);
                $('#holidays_id').val(holidays_id);
                // Show the modal
                $('#edit_holiday').modal('show');
            });

            $('#editSubmit').submit(function(e) {
                e.preventDefault();
                var holidays_id = $('#holidays_id').val();
                var formData = new FormData(this);
                $.ajax({
                    url: baseUrl + '/edit/holiday/'+holidays_id, 
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
                            title: 'Holiday Edited successfully',
                            text: 'Your holiday edit was successful!',
                            showConfirmButton: false, 
                        });
                        setTimeout(function() {
                                location.reload();
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

            $('.dropdown-item[data-bs-target="#delete_holiday"]').click(function() {
                // Get the dept_id from the clicked element's data-id attribute
                var holidays_id = $(this).find('.fa-regular').data('id');
                // Log the dept_id to the console
                console.log(holidays_id);
                var trElement = $(this).closest('tr');
                $('#holidays_id').val(holidays_id);
            });

            $('#holidayDelete').submit(function(e) {
                e.preventDefault();
                var holidays_id = $('#holidays_id').val();
                console.log(holidays_id);
                var formData = new FormData(this);

                $.ajax({
                    url: baseUrl + '/delete/holiday/'+holidays_id, 
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
                            title: 'Holiday successfully deleted',
                            text: 'You have successfully deleted a holiday',
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