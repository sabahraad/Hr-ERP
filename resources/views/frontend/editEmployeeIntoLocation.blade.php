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
                <h3 class="page-title">Edit Employee List Location Wise</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Edit Employee List Location Wise</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-body">
            <form action="{{route('updateEmployeeIntoLocation')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-sm-12">
                        <label for="office_locations_id" class="col-form-label">Location Name</label>
                        <input class="form-control" disabled value="{{$data->officeLocation->location_name}}">
                        <input class="form-control" hidden name="location_wise_employees_id" value="{{$data->location_wise_employees_id}}">
                    </div>

                    <div class="col-sm-12">
                        <label for="emp_id" class="col-form-label">Select Employee</label>
                        <select name="emp_id[]" id="emp_id" class="select2" multiple="multiple" required>
                            @foreach ($emp as $employee)
                                <option value="{{$employee->emp_id}}"
                                    @if(in_array($employee->emp_id, array_column(json_decode($data->employee_ids, true), 'emp_id')))
                                        selected
                                    @endif
                                >{{$employee->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="selectedEmployees" id="selectedEmployees">
                </div>
                
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<!-- /Edit Employee Modal -->

<script>
$(document).ready(function() {
    $('.select2').select2();

    $('form').on('submit', function() {
        var selectedEmployees = [];
        $('#emp_id option:selected').each(function() {
            selectedEmployees.push({
                emp_id: $(this).val(),
                name: $(this).text()
            });
        });
        $('#selectedEmployees').val(JSON.stringify(selectedEmployees));
    });
});
</script>
