@include('frontend.header')
@include('frontend.navbar')
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
                                    <h3 class="page-title">Generate Payslip</h3>
                                </div>
                            </div>
                        </div>
                        <!-- /Page Header -->
                        <form id="msform">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Select Month <span class="text-danger">*</span></label>
                                        <select name="month" id="month" class="select" required>
                                        <option selected disabled>Open this to select Month</option>
                                                <option value="1">January</option>
                                                <option value="2">February</option>
                                                <option value="3">March</option>
                                                <option value="4">April</option>
                                                <option value="5">May</option>
                                                <option value="6">June</option>
                                                <option value="7">July</option>
                                                <option value="8">August</option>
                                                <option value="9">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Enter Year <span class="text-danger">*</span></label>
                                        <select name="year" id="year" class="select" required>
                                        <option selected disabled>Open this to select Year</option>
                                                <option value="2024">2024</option>
                                                <option value="2025">2025</option>
                                                <option value="2026">2026</option>
                                                <option value="2027">2027</option>
                                                <option value="2028">2028</option>
                                                <option value="2029">2029</option>
                                                <option value="2030">2030</option>
                                                <option value="2031">2031</option>
                                                <option value="2032">2032</option>
                                                <option value="2033">2033</option>
                                                <option value="2034">2034</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn-lg" >Show Payslip</button>
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
                                        <th>Name</th>
                                        <th>Employee ID</th>
                                        <th>Salary</th>
                                        <th>Adjustment Amount</th>
                                        <th>Adjustment Reason</th>
                                        <th>After Adjustment Salary</th>
                                        <th>Status</th>
                                        <th>Action</th>
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


            <!-- Edit Department Modal -->
            <div id="adjustment" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Salary Adjustment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editSubmit">
                            @csrf
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Payslip ID</label>
                                    <input id="payslips_id" class="form-control" name="payslips_id" disabled>
                                    <label class="col-form-label">Employee ID</label>
                                    <input id ="emp_id" class="form-control" name="emp_id" type="text" disabled>
                                    <label class="col-form-label">Employee Name</label>
                                    <input id ="name" class="form-control" name="name" type="text" disabled>
                                    <label for="inputText4" class="col-form-label">Select Adjustment Type</label>
                                    <select name="adjustment_type" id="adjustment_type" class="select">
                                        <option selected disabled>Open this to select adjustment type</option>
                                        <option value="addition">Addition</option>
                                        <option value="deduction">Deduction</option>
                                    </select>
                                    <label class="col-form-label">Adjustment Amount</label>
                                    <input id ="adjusted_amount" class="form-control" name="adjusted_amount" type="text" >
                                    <label class="col-form-label">Reason</label>
                                    <input id ="adjustment_reason" class="form-control" name="adjustment_reason" type="text" >                                    
                                    
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
            
        </div>
        <!-- /Page Wrapper -->

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('#desigTable').DataTable();
    var jwtToken = "{{ $jwtToken }}";
    var baseUrl = "{{ $baseUrl }}";
    $('#msform').submit(function(e) {
        e.preventDefault();
        var month = $('#month').val();
        var year = $('#year').val();
        var data = {
            "month": month,
            "year":year,
        };
        $.ajax({
            url: baseUrl + '/create-payslip', 
            type: 'POST',
            contentType: 'application/json',
            headers: {
                'Authorization': 'Bearer ' + jwtToken
            },
            data: JSON.stringify(data),
            success: function(response) {
                $.ajax({
                    url: baseUrl + '/payslip-list-companywise/'+ month + '/'+year,
                    type: 'GET',
                    headers: {
                            'Authorization': 'Bearer ' + jwtToken
                        },
                    success: function(response) {
                        console.log(response);
                        var table = $('#desigTable').DataTable();
                        table.clear().draw();
                        var rowNum = 1;
                        // Iterate through the data and populate the table
                        response.data.forEach(function(item) {
                            var rowData = [
                                rowNum,
                                item.name, 
                                item.emp_id,
                                item.salary,
                                item.adjusted_amount,
                                item.adjustment_reason,
                                item.after_adjustment_salary,
                                item.payslips_status,
                                '<button class="btn btn-primary adjustment" data-bs-toggle="modal" data-bs-target="#adjustment" data-id="'+item.payslips_id+'">Adjustment</button>'
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
                                    title: 'Total Salary Break Down Can Not Be More Then 100%',
                                });
                        }
                        if(xhr.status == 422){
                            Swal.fire({
                                    icon: 'error',
                                    title: 'Total Salary Break Down Can Not Be More Then 100%',
                                });
                        }else {
                            console.log('Error in API call');
                        }
                        
                    }
                });
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: xhr.responseJSON.message
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
                }
            }
        });
    });

    $(document).on('click', '.adjustment', function(){
        var payslips_id = $(this).data('id');
        console.log(payslips_id);

        $.ajax({
            url: baseUrl + '/payslip-details/'+payslips_id, 
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + jwtToken
            },
            success: function(response) {
                console.log(response.data.Salary);
                $('#emp_id').val(response.data['employee details'][0].emp_id);
                $('#name').val(response.data['employee details'][0].name);
                $('#salary').val(response.data.Salary);
                $('#adjusted_amount').val(response.data.adjusted_amount);
                $('#adjustment_reason').val(response.data.adjustment_reason);
                $('#payslips_id').val(payslips_id)
                console.log(payslips_id);
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
        var payslips_id = $('#payslips_id').val();
        var formData = new FormData(this);
        $.ajax({
            url: baseUrl + '/adjust-payslip/'+payslips_id, 
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + jwtToken
            },
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#adjustment').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Salary Adjustment done successfully',
                        showConfirmButton: true, 
                        allowOutsideClick: false, 
                    }).then((result) => {
                        if (result.isConfirmed) {
                                $.ajax({
                                    url: baseUrl + '/payslip-list-companywise/'+ response.data.month + '/'+response.data.year,
                                    type: 'GET',
                                    headers: {
                                            'Authorization': 'Bearer ' + jwtToken
                                        },
                                    success: function(response) {
                                        console.log(response);
                                        var table = $('#desigTable').DataTable();
                                        table.clear().draw();
                                        var rowNum = 1;
                                        var totalPercentage = 0;
                                        response.data.forEach(function(item) {
                                            var rowData = [
                                                rowNum,
                                                item.name, 
                                                item.emp_id,
                                                item.salary,
                                                item.adjusted_amount,
                                                item.adjustment_reason,
                                                item.after_adjustment_salary,
                                                item.payslips_status,
                                                '<button class="btn btn-primary adjustment" data-bs-toggle="modal" data-bs-target="#adjustment" data-id="'+item.payslips_id+'">Adjustment</button>'
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
                                                    title: 'something went wrong',
                                                });
                                        }
                                        if(xhr.status == 422){
                                            Swal.fire({
                                                    icon: 'error',
                                                    title: 'something went wrong',
                                                });
                                        }else {
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