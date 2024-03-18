@include('frontend.header')
@include('frontend.navbar')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" rel="stylesheet">
<style> 
    .btn-light{
        height: 49px;
    }
    .table td a {
        color: #fff;
    }
    .btn-danger {
        background-color: #362121;
        border: 1px solid #f62d51;
    }
</style>
    <!-- Page Wrapper -->
<div class="page-wrapper">
            <!-- Page Content -->
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-md-12">
                         <!-- Page Header -->
                         <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Add Employee In Shift</h3>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item">Dashboard</li>
                                        <li class="breadcrumb-item active">Shift Employee</li>
                                    </ul>
                                </div>
                                <div class="col-auto float-end ms-auto">
                                    <a href="{{route('ShiftList')}}" class="btn add-btn" >
                                        <i class="fa-solid fa-list"></i> 
                                        Shift List
                                    </a>

                                    <div class="view-icons">
                                        <!-- <a href="employees.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                                        <a href="employees-list.html" class="list-view btn btn-link active"><i class="fa-solid fa-bars"></i></a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Page Header -->
                        @if(session('success'))
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                            <script>
                                // Display SweetAlert with a "Reload" button
                                Swal.fire({
                                    title: 'Success',
                                    text: "{{ session('success') }}",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Reload the page
                                        location.reload();
                                    }
                                });
                            </script>
                        @endif
                        <!-- /Page Header -->
                        <form action="{{route('addEmployeeInShift')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="input-block mb-3">
                                       
                                        <label class="col-form-label">Shift Name<span class="text-danger">*</span></label>
                                        <select name="shifts_id" class="form-control select" required>
                                            @foreach ($shift as $raw)
                                                <option value="{{ $raw['shifts_id'] }}">{{ $raw['shifts_title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Employee<span class="text-danger">*</span></label>
                                        <select name="emp_id[]" class="form-control selectpicker" multiple data-live-search="true" title="Select Employees" required>
                                            @foreach ($employee['data'] as $employee)
                                                <option value="{{ $employee['emp_id'] }}">{{ $employee['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>  
                                <div class="col-sm-2">
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn-md" style="padding-left: 56px;padding-right: 57px;padding-top: 7px;padding-bottom: 5px;">
                                            <i class="fas fa-plus-circle m-r-5"></i>
                                                Add
                                        </button>
                                    </div>
                                </div>
                                                            
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
                                        <th>Shift Name </th>
                                        <th>Employee </th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <!-- $title = $data->department->deptTitle -->
                                <tbody>
                                    @foreach ($value as $key => $raw )
                                    <tr>
                                        <td>
                                            {{$key+1}}
                                        </td>
                                        <td>{{$raw->shifts_title}}</td>
                                        
                                    <td>
                                        @php
                                            $employees = json_decode($raw->shift_emp_list, true);
                                        @endphp

                                        @if (!empty($employees))
                                            <ul>
                                                @foreach ($employees as $employee)
                                                    <li> Name: {{ $employee['emp_name'] }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            No employees in the list.
                                        @endif
                                    </td>
                                    <td>{{$raw->shifts_start_time}}</td>
                                    <td>{{$raw->shifts_end_time}}</td>
                                    <td>
                                        <a class="btn btn-danger" href="{{ route('showRemoveEmployeeFromShift', ['id' => $raw->shift_employees_id]) }}" ><i class="fas fa-minus-circle m-r-5"></i> Remove Employee From Shift</a>
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
                                        <input id ="approvers_id" class="form-control" name="approvers_id" type="hidden">
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


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
</head>
<script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker();
        });

        $(document).ready(function(){
            $('#desigTable').DataTable();
        });
</script>