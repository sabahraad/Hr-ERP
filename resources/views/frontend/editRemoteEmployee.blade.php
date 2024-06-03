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
                            <h3 class="page-title">Edit Employee List Remote</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Edit Employee List Remote</li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <a href="{{route('remoteEmployeeList')}}" class="btn add-btn"><i class="fa-solid fa-plus"></i>Remote Employee List</a>
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
                        <div class="card-body ">
                            <form method="post" action="{{route('updateEmployeeIntoRemote')}}">
                                @csrf
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
                                <input type="hidden" name="remote_employees_id" value="{{$data->remote_employees_id}}" >
                                <input type="hidden" name="selectedEmployees" id="selectedEmployees">
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->

   
        </div>
        <!-- /Page Wrapper -->

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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