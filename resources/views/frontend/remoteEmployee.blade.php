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
                            <h3 class="page-title">Employee List Remote</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Employee List Remote</li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_department"><i class="fa-solid fa-plus"></i> Add Employee into Remote</a>
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
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0 datatable" id="desigTable">
                                <thead>
                                    <tr>
                                        <th class="width-thirty">#</th>
                                        <th>Attendance Type</th>
                                        <th>Employee Name</th>
                                        <th >Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($result as $key =>$remote)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>Remote</td>
                                        <td>
                                            @php
                                                $selectedEmployees = json_decode($remote->employee_ids, true);
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
                                                <a class="btn btn-info btn-outline" href="{{route('editEmployeeIntoRemote', ['id' => $remote->remote_employees_id])}}" ><i class="fa-solid fa-pencil m-r-5" ></i> Edit</a>
                                                <a class="btn btn-danger btn-outline delete-employee" href="#" data-bs-toggle="modal" data-bs-target="#delete_employee" data-id="{{ $remote->remote_employees_id }}"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
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
                    <h5 class="modal-title">Add Employee Into Remote</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('addEmployeeIntoRemote')}}" method="post" onsubmit="serializeEmployeeData()">
                        @csrf
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

    <!-- Delete Employee Modal -->
    <div class="modal custom-modal fade" id="delete_employee" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Remote List</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                <form action="{{route('deleteEmployeeIntoRemote')}}" method="post">
                                    @csrf
                                    <input id ="remote_employees_id" class="form-control" name="remote_employees_id" type="hidden">
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
        <!-- /Delete Employee Modal -->
            
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

        $(document).on('click', '.delete-employee', function(){
            var remote_employees_id = $(this).data('id');
            $('#remote_employees_id').val(remote_employees_id);
        });
        
    });
</script>