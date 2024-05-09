@include('SuperAdmin.header')
@include('SuperAdmin.navbar')    
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- Page Wrapper -->
<div class="page-wrapper">

<!-- Page Content -->
<div class="content container-fluid">
    
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Holiday</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Holiday</li>
                </ul>
            </div>
            <div class="col-auto float-end ms-auto">
                <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_holiday"><i class="fa-solid fa-plus"></i> Add Holiday</a>
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
                            <th>#</th>
                            <th>Title</th>
                            <th>Holiday Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $key => $holiday)
                            
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$holiday->reason ?? 'N/A'}}</td>
                                <td>{{$holiday->date ?? 'N/A'}}</td>
                                <td>
                                    <a class="btn btn-info edit_holiday" href="#" data-bs-toggle="modal" data-bs-target="#edit_holiday" data-id="{{ $holiday->holidays_id }}"><i class="fa-solid fa-pencil m-r-5"></i> Edit</a>
                                    <a class="btn btn-danger delete_holiday" href="#" data-bs-toggle="modal" data-bs-target="#delete_holiday" data-id="{{ $holiday->holidays_id }}"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
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
                                <br>
                                    <label for="inputText4" class="col-form-label">Select Date Range:</label><br>
                                    <input type="text"  id="date_range" class="form-control" name="date_range">
                                <br>
                                
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
                                
                                    <label for="inputText4" class="col-form-label">Select Date Range:</label><br>
                                    <input type="text"  id="date_range" class="form-control" name="date_range">
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

            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script> 
$(document).ready(function() {
    $('#empTable').DataTable({
        "order": [[ 0, "desc" ]] 
    });

    $(function() {
        $('input[name="date_range"]').daterangepicker({
            // opens: 'left',
            // autoApply: true,
            locale: {
            format: 'YYYY-MM-DD'
        }
        }, function(start, end, label) {
            console.log("A date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        });
})

var jwtToken = "{{ $jwtToken }}";
var baseUrl = "{{ $baseUrl }}";
$('#msform').submit(function(e) {
    console.log('ok');
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

$(document).on('click', '.edit_holiday', function(){
    var holidays_id = $(this).data('id');
    console.log(holidays_id);
    $.ajax({
        url: baseUrl + '/holiday-details/'+holidays_id, 
        type: 'GET',
        headers: {
            'Authorization': 'Bearer ' + jwtToken
        },
        success: function(response) {
            console.log(response);
            $('#holidayName').val(response.data.reason);
            $('#holidayDate').val(response.data.date);
            $('#holidays_id').val(holidays_id);
            // Show the modal
            $('#edit_holiday').modal('show');
            
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
    var holidays_id = $('#holidays_id').val();
    // console.log(holidays_id);
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
$(document).on('click', '.delete_holiday', function(){
    var holidays_id = $(this).data('id');
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
</script>