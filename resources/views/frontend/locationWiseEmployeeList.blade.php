@include('frontend.header')
@include('frontend.navbar')
<style>
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
                            <h3 class="page-title">Employee List Location Wise</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Employee List Location Wise</li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_department"><i class="fa-solid fa-plus"></i> Add Employee into Location</a>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                    <!-- error show -->
            @if ($message = Session::get('success'))
                <div class="alert alert-success" role="alert">
                    <div class="txt-success">{{ $message }}</div>
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger" role="alert">
                    <div class="txt-danger">{{ $message }}</div>
                </div>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger" role="alert">
                    <div class="txt-danger">
                        <ul class="list-group">
                            @foreach ($errors->all() as $error)
                                <li><i class="icofont icofont-arrow-right"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        <!-- error show -->
        <div class="card" style="border: 0;box-shadow: 0 0 20px 0 rgba(76,87,125,.2);">
                    <div class="card-body ">
                        <form action="{{route('individualLoctionWiseEmployeeList')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="inputText4" class="col-form-label">Select Location</label>
                                <select name="office_locations_id" id="office_locations_id" class="form-select" required>
                                    <option selected disabled>Open this to select Location</option>
                                    @foreach ($data as $location)
                                        <option value="{{$location->office_locations_id}}">{{$location->location_name}}</option>
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
                            <table class="table table-striped custom-table mb-0 datatable" id="desigTable">
                                <thead>
                                    <tr>
                                        <th class="width-thirty">#</th>
                                        <th>Office Location</th>
                                        <th>Employee Name</th>
                                        <th >Action</th>
                                        <!-- class="text-end" -->
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($result as $key =>$officeLocation)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$officeLocation->officeLocation->location_name}}</td>
                                        <td>
                                            @php
                                                $selectedEmployees = json_decode($officeLocation->employee_ids, true);
                                            @endphp
                                            @if(is_array($selectedEmployees))
                                                @foreach ($selectedEmployees as $employee)
                                                    {{ $employee['name'] }}<br>
                                                @endforeach
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                        <div class="dropdown dropdown-action"> 
                                                <a class="btn btn-info btn-outline" href="{{route('editEmployeeIntoLocation', ['id' => $officeLocation->location_wise_employees_id])}}" ><i class="fa-solid fa-pencil m-r-5" ></i> Edit</a>
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

    <!-- Add Employee Modal -->
    <div id="add_department" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Employee Into Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('addEmployeeIntoLocation')}}" method="post" onsubmit="serializeEmployeeData()">
                        @csrf
                        <div class="form-group">
                            <label for="office_locations_id" class="col-form-label">Select Location</label>
                            <select name="office_locations_id" id="office_locations_id" class="form-select" required>
                                <option selected disabled>Open this to select Location</option>
                                @foreach ($data as $location)
                                    <option value="{{$location->office_locations_id}}">{{$location->location_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="emp_id" class="col-form-label">Select Employee</label>
                            <select name="emp_id[]" id="emp_id" class="select2" multiple="multiple" required onchange="processEmployeeData()">
                                @foreach ($emp as $employee)
                                    <option value="{{$employee->emp_id}}">{{$employee->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="selectedEmployees" id="selectedEmployees">
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Employee Modal -->
            
        </div>
        <!-- /Page Wrapper -->

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Array to store selected employee data
var selectedEmployees = [];
// Function to process selected employee data
function processEmployeeData() {
    // Get the select element
    var selectElement = document.getElementById('emp_id');
    // Clear the previous array
    selectedEmployees = [];
    // Iterate through the selected options
    for (var option of selectElement.options) {
        if (option.selected) {
            // Get emp_id and name from option value and text
            var emp_id = option.value;
            var name = option.text;

            // Add the employee data to the array
            selectedEmployees.push({ emp_id: emp_id, name: name });
        }
    }
    // Output the selected employee data (for demonstration purposes)
    console.log(selectedEmployees);
}
// Serialize the selected employee data before form submission
function serializeEmployeeData() {
    var serializedData = JSON.stringify(selectedEmployees);
    document.getElementById('selectedEmployees').value = serializedData;
}

    $(document).ready(function() {
        $('.select2').select2();

        $(document).on('click', '.add_department', function(){
            $('.select2').each(function () {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                });
            });
        });

        $('#desigTable').DataTable();
        $('#msform').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: baseUrl + '/add-office-location', 
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
                        title: 'Office Location added successful',
                        text: 'Your Office Location created successful!',
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

        $('.dropdown-item[data-bs-target="#edit_department"]').click(function() {
            // Get the dept_id from the clicked element's data-id attribute
            var locationID = $(this).find('.fa-pencil').data('id');
            var trElement = $(this).closest('tr');
            // Find the 'td' elements within the 'tr'
            var location_name = trElement.find('td:eq(1)').text();
            var longitude = trElement.find('td:eq(2)').text();
            var latitude = trElement.find('td:eq(3)').text();
            var radius = trElement.find('td:eq(4)').text();
            $('#locationName').val(location_name);
            $('#latitude1').val(latitude);
            $('#longitude1').val(longitude);
            $('#radius').val(radius);
            $('#locationID').val(locationID);
            // Show the modal
            $('#edit_department').modal('show');
        });

        $('#editSubmit').submit(function(e) {
            e.preventDefault();
            var locationID = $('#locationID').val();
            console.log(locationID);

            var formData = new FormData(this);

            $.ajax({
                url: baseUrl + '/edit/office-location/'+locationID, 
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
                        title: 'Office Location Edited successfully',
                        text: 'Your Office Location edit was successful!',
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
</script>