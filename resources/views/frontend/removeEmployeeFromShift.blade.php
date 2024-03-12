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
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3 class="page-title">Remove Employee From Shift</h3>
                                </div>
                            </div>
                        </div>
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
                        <form action="{{route('removeEmployeeFromShift')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="input-block mb-3">
                                    @foreach ($value as $raw)
                                        <label class="col-form-label">Shift Name<span class="text-danger">*</span></label>
                                        <input class="form-control" value="{{ $raw->shifts_title }}" readonly></input>
                                        <input class="form-control" name="shift_employees_id" value="{{ $raw->shift_employees_id }}" hidden></input>
                                    </div>
                                </div>
                                        @php
                                            $employees = json_decode($raw->shift_emp_list, true);
                                        @endphp
                                <div class="col-sm-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Employee<span class="text-danger">*</span></label>
                                        <select name="emp_id[]" class="form-control selectpicker" multiple data-live-search="true" title="Select Employees" required>
                                            @if (!empty($employees))
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee['emp_id'] }}">{{ $employee['emp_name'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>  
                                @endforeach

                                <div class="col-sm-2">
                                    <div class="submit-section">
                                        <button class="btn btn-danger submit-btn">
                                            <i class="fas fa-minus-circle m-r-5"></i>
                                                Remove
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
                                        <a class="btn btn-primary" href="{{route('showAddEmployeeInShift')}}" ><i class="fas fa-plus-circle m-r-5"></i> Add Employee From Shift</a>
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

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
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

        $(document).ready(function() {
            $('#msform').submit(function(e) {
                e.preventDefault();

                var dept_id = $('#dept_id').val();
                var emp_id = $('#emp_id').val();
                var priority = $('#priority').val();

                var data = {
                    "deptId": dept_id,
                    "emp_id":emp_id,
                    "priority":priority
                };

                $.ajax({
                        url: 'https://hrm.aamarpay.dev/api/add-approvers', 
                        type: 'POST',
                        contentType: 'application/json',
                        headers: {
                            'Authorization': 'Bearer ' + jwtToken
                        },
                        data: JSON.stringify(data),
                        success: function(response) {
                            var dept_id = response.data.deptId;
                            console.log(dept_id)
                            Swal.fire({
                                icon: 'success',
                                title: 'Leave Approver successfully added',
                                text: 'You have successfully added a Leave Approver',
                                showConfirmButton: true,
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: 'https://hrm.aamarpay.dev/api/approvers-list/'+dept_id,
                                        type: 'GET',
                                        headers: {
                                                'Authorization': 'Bearer ' + jwtToken
                                            },
                                        success: function(response) {
                                            var table = $('#desigTable').DataTable();
                                            table.clear().draw();
                                            var rowNum = 1;

                                            // Iterate through the data and populate the table
                                            response.data.forEach(function(item) {
                                                var rowData = [
                                                    rowNum,
                                                    '<td data-deptid="' + item.deptId + '">' + item.deptName + '</td>',
                                                    '<td data-empid="' + item.emp_id + '">' + item.approver_name + '</td>',
                                                    '<td >' + item.priority + '</td>',
                                                    '<div class="dropdown dropdown-action"><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_designation"><i class="fa-regular fa-trash-can m-r-5" data-id="'+item.approvers_id+'"></i></a></div></div>'
                                                ];
                                                table.row.add(rowData);
                                                rowNum++;
                                            });
                                            
                                            table.draw();
                                        },
                                        error: function(xhr, textStatus, errorThrown) {
                                            console.log('ok');
                                            if (xhr.status == 404) {
                                                var table = $('#desigTable').DataTable();
                                                table.clear().draw();
                                                Swal.fire({
                                                        icon: 'error',
                                                        title: 'No Leave Approver Found for this Department',
                                                    });
                                            } else {
                                                console.log('Error in API call');
                                            }
                                            
                                        }
                                    });
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
                            if(xhr.status == 403){
                                var table = $('#desigTable').DataTable();
                                table.clear().draw();
                                var errors = xhr.responseJSON.message;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    html: errors
                                });
                            }else {
                                console.log('Error in API call');
                            }
                        }
                });
            });
        });

    $(document).on('click', '.dropdown-item[data-bs-target="#delete_designation"]', function() {
        var approvers_id = $(this).find('.fa-regular.fa-trash-can').data('id');
        // console.log(approvers_id);
        $('#approvers_id').val(approvers_id);
    });
  
    $(document).ready(function() {
    $('#desigDelete').submit(function(e) {
        e.preventDefault();
        var approvers_id = $('#approvers_id').val();
        // console.log(approvers_id);
        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/delete-approvers/'+approvers_id, 
                type: 'DELETE',
                data: formData,
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                contentType: false,
                processData: false,
                success: function(response) {
                    var dept_id = response.data;
                    console.log(dept_id)
                    $('#delete_designation').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Leave Approver successfully deleted',
                        text: 'You have successfully deleted a Leave Approver',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'https://hrm.aamarpay.dev/api/approvers-list/'+dept_id,
                                type: 'GET',
                                headers: {
                                        'Authorization': 'Bearer ' + jwtToken
                                    },
                                success: function(response) {
                                    var table = $('#desigTable').DataTable();
                                    table.clear().draw();
                                    var rowNum = 1;

                                    // Iterate through the data and populate the table
                                    response.data.forEach(function(item) {
                                        var rowData = [
                                            rowNum,
                                            '<td data-deptid="' + item.deptId + '">' + item.deptName + '</td>',
                                            '<td data-empid="' + item.emp_id + '">' + item.approver_name + '</td>',
                                            '<td >' + item.priority + '</td>',
                                            '<div class="dropdown dropdown-action"><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_designation"><i class="fa-regular fa-trash-can m-r-5" data-id="'+item.approvers_id+'"></i></a></div></div>'
                                        ];
                                        table.row.add(rowData);
                                        rowNum++;
                                    });
                                    
                                    table.draw();
                                },
                                error: function(xhr, textStatus, errorThrown) {
                                    if (xhr.status == 404) {
                                        var table = $('#desigTable').DataTable();
                                        table.clear().draw();
                                        Swal.fire({
                                                icon: 'error',
                                                title: 'No Leave Approver Found for this Department',
                                            });
                                    } else {
                                        console.log('Error in API call');
                                    }
                                }
                            });
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
    });

</script>