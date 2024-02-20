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
                                        <input class="form-control" type="number" id="year" name="year" min="1900" max="2100" required>
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
                                        <th>Number</th>
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
            
          <!-- Delete Employee Modal -->
				<div class="modal custom-modal fade" id="delete_designation" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Delete Salary Breakdown</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
                                        <form id="delete_desig">
                                            @csrf
                                            <input id ="tempSalarySettingID" class="form-control" name="tempSalarySettingID" type="hidden">
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

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>

<script>
        $(document).ready(function(){
                $('#desigTable').DataTable();
            });

        $(document).ready(function() {
            var jwtToken = "{{ $jwtToken }}";
            $('#msform').submit(function(e) {
                e.preventDefault();

                var month = $('#month').val();
                var year = $('#year').val();
                // console.log(month,year);
                var data = {
                    "month": month,
                    "year":year,
                };

                $.ajax({
                        url: 'https://hrm.aamarpay.dev/api/create-payslip', 
                        type: 'POST',
                        contentType: 'application/json',
                        headers: {
                            'Authorization': 'Bearer ' + jwtToken
                        },
                        data: JSON.stringify(data),
                        success: function(response) {
                            $.ajax({
                                url: 'https://hrm.aamarpay.dev/api/payslip-list-companywise/'+ month + '/'+year,
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
                                            item.phone_number,
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
        });

        $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
        $(document).on('click', '.adjustment', function(){
            var payslips_id = $(this).data('id');
            console.log(payslips_id);

            $.ajax({
                url: 'https://hrm.aamarpay.dev/api/payslip-details/'+payslips_id, 
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
    });

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#editSubmit').submit(function(e) {
        e.preventDefault();
        var payslips_id = $('#payslips_id').val();
        console.log(payslips_id);

        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/adjust-payslip/'+payslips_id, 
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log('ok raad');
                    $('#adjustment').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Salary Adjustment done successfully',
                            showConfirmButton: true, 
                            allowOutsideClick: false, 
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // console.log(response.data.month);
                                    $.ajax({
                                        url: 'https://hrm.aamarpay.dev/api/payslip-list-companywise/'+ response.data.month + '/'+response.data.year,
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
                                                    item.phone_number,
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


    

    $(document).on('click', '.dropdown-item[data-bs-target="#delete_designation"]', function() {
        var tempSalarySettingID = $(this).find('.fa-regular.fa-trash-can').data('id');
        console.log(tempSalarySettingID);
        $('#tempSalarySettingID').val(tempSalarySettingID);
    });

  
    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#delete_desig').submit(function(e) {
        e.preventDefault();
        var tempSalarySettingID = $('#tempSalarySettingID').val();
        console.log(tempSalarySettingID);

        var formData = new FormData(this);
        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/delete-temp-salary-setting/'+tempSalarySettingID, 
                type: 'DELETE',
                data: formData,
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log('ok')
                    $('#delete_designation').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Salary Breakdown successfully deleted',
                        text: 'You have successfully deleted a Salary Breakdown',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'https://hrm.aamarpay.dev/api/temp-salary-setting-list',
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
                                    // Iterate through the data and populate the table
                                    response.data.forEach(function(item) {
                                        var rowData = [
                                            rowNum,
                                            item.name, 
                                            item.percentage, 
                                            '<div class="dropdown dropdown-action"><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_designation"><i class="fa-regular fa-trash-can m-r-5" data-id="'+item.temp_salary_settings_id+'"></i></a></div></div>'
                                        ];
                                        table.row.add(rowData);
                                        totalPercentage += parseFloat(item.percentage);
                                        rowNum++;
                                    });
                                    table.draw();

                                    var totalRowData = [
                                                '<td colspan="2"><b>Total:</b></td>',
                                                '<td><b>' + totalPercentage.toFixed(2) + '</b></td>',
                                                '<td></td>'
                                            ];
                                    $('#desigTable tbody').append('<tr>' + totalRowData.join('') + '</tr>');
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

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#saveAll').click(function(e) {
        e.preventDefault();
        console.log('ok');
       
        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/create-salary-setting', 
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log('ok')
                    Swal.fire({
                        icon: 'success',
                        title: 'Salary Breakdown successfully added',
                        text: 'You have successfully added a Salary Breakdown',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'https://hrm.aamarpay.dev/api/temp-salary-setting-list',
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
                                    // Iterate through the data and populate the table
                                    response.data.forEach(function(item) {
                                        var rowData = [
                                            rowNum,
                                            item.name, 
                                            item.percentage, 
                                            '<div class="dropdown dropdown-action"><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_designation"><i class="fa-regular fa-trash-can m-r-5" data-id="'+item.temp_salary_settings_id+'"></i></a></div></div>'
                                        ];
                                        table.row.add(rowData);
                                        totalPercentage += parseFloat(item.percentage);
                                        rowNum++;
                                    });
                                    table.draw();
                                    var totalRowData = [
                                                '<td colspan="2"><b>Total:</b></td>',
                                                '<td><b>' + totalPercentage.toFixed(2) + '</b></td>',
                                                '<td></td>'
                                            ];
                                    $('#desigTable tbody').append('<tr>' + totalRowData.join('') + '</tr>');
                                    
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
                    }else if (xhr.status === 403) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Total Percentage Have To Be 100.'
                        });
                    }
                }
            });
        });
    });

</script>