@include('frontend.header')
@include('frontend.navbar')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .table {
            --bs-table-bg: transparent; /* Set it to a transparent color */
        }
    .bg-danger td {
            color: white; /* Set text color to white */
        }
    .dt-button{
      color: white !important;
      background-color: #6564ad !important;
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
    .select2-container {
        width: 100% !important;
        z-index: 9999 !important;
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
                            <h3 class="page-title">Timeline</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Timeline</li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <a href="{{route('timelineSetting')}}" class="btn add-btn"><i class="fas fa-cog"></i> Timeline Setting</a>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="card" style="border: 0;box-shadow: 0 0 20px 0 rgba(76,87,125,.2);">
                    <div class="card-body ">
                        <form id="myForm">
                            @csrf
                            <label for="inputText4" class="col-form-label">Select Employee</label>
                            <select name="emp_id" id="emp_id" class="select" required>
                                <option selected disabled>Open this to select Employee</option>
                                @foreach ($employee['data'] as $emp)
                                    <option value="{{$emp['emp_id']}}">{{$emp['name']}}</option>
                                @endforeach
                            </select>

                            <label for="inputText4" class="col-form-label">Select Date Range:</label><br>
                                <input type="text"  id="date_range" class="form-control" name="date_range">
                            <br>
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
                                        <th>Employee Name</th>
                                        <th>Date</th>
                                        <th>Location Data</th>
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
                                        @foreach ($employee['data'] as $emp)
                                            <option value="{{$emp['emp_id']}}">{{$emp['name']}}</option>
                                        @endforeach
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
                                        <input id ="attendance_id" class="form-control" name="attendance_id" type="hidden">
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


<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>

    $(document).ready(function() {
        $('#emp_id').select2();

        $('#desigTable').DataTable({
            dom: 'Bfrtip', 
            buttons: [
                'excel'
            ]
        });

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

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
        var baseUrl = "{{ $baseUrl }}";
        
        $('#myForm').submit(function(event) {
			event.preventDefault(); // Prevent the form from submitting normally
                
            var formData = new FormData(this);

			$.ajax({
                url: baseUrl + '/employee-wise-timeline-list',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var table = $('#desigTable').DataTable();
                    table.clear().draw();

                    response.data.forEach(function(item) {
                        var locations = JSON.parse(item.location);
                        var locationData = '';

                        locations.forEach(function(location) {
                            locationData += ' <b>Name:</b> ' + (location.name || 'N/A') + '&nbsp;&nbsp;&nbsp;' +
                                            '<b>Time:</b> ' + location.time + '&nbsp;&nbsp;&nbsp;' + '<br>';
                        });
                        key = 0;
                        table.row.add([
                            key+1,
                            item.name,
                            item.track_date,
                            locationData
                        ]).draw();
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.status == 404) {
                        var table = $('#desigTable').DataTable();
                        table.clear().draw();
                        Swal.fire({
                                icon: 'error',
                                title: 'No Timeline Details Found For This Date',
                            });
                    } else {
                        console.log('Error in API call');
                    }
                }
			});
		});
    });
</script>