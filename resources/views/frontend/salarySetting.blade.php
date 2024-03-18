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
                                    <h3 class="page-title">Add Salary Break Down</h3>
                                </div>
                            </div>
                        </div>
                        <!-- /Page Header -->
                        <form id="msform">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                        <input class="form-control" id="name" type="text" name= "name" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Percentage<span class="text-danger">*</span></label>
                                        <input class="form-control" id="percentage" type="number" name= "percentage" min="1"  max="100" required>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="submit-section">
                                        <button class="btn btn-info submit-btn-md" >Add More</button>
                                    </div>
                                </div>  
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 " >
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0" id="desigTable">
                                <thead>
                                    <tr>
                                        <th class="width-thirty">#</th>
                                        <th>Name</th>
                                        <th>Percentage</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                    $totalPercentage = 0;
                                @endphp
                                    @foreach ($data as $key => $raw)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$raw->name}}</td>
                                        <td>{{$raw->percentage}}</td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_designation">
                                                        <i class="fa-regular fa-trash-can m-r-5" data-id="{{$raw->temp_salary_settings_id}}"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        @php
                                            $totalPercentage += $raw->percentage;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="2"><b>Total:</b></td>
                                        <td><b>{{$totalPercentage}}</b></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <a id ="saveAll" class="btn btn-primary btn-lg">Save Salary Breakdown</a>
                        </div>

                    </div>
                </div>
            </div>
            <!-- /Page Content -->
            
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
            var baseUrl = "{{ $baseUrl }}";
            $('#msform').submit(function(e) {
                e.preventDefault();
                var name = $('#name').val();
                var percentage = $('#percentage').val();

                var data = {
                    "name": name,
                    "percentage":percentage,
                };

                $.ajax({
                        url: baseUrl + '/temp-salary-setting', 
                        type: 'POST',
                        contentType: 'application/json',
                        headers: {
                            'Authorization': 'Bearer ' + jwtToken
                        },
                        data: JSON.stringify(data),
                        success: function(response) {
                            // var company_id = response.data[0].company_id;
                            console.log(response)
                            Swal.fire({
                                icon: 'success',
                                title: 'Salary Breakdown successfully added',
                                text: 'You have successfully added a Salary Breakdown',
                                showConfirmButton: true,
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: baseUrl + '/temp-salary-setting-list',
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
                                            // table.row.add(totalRowData);
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

    $(document).on('click', '.dropdown-item[data-bs-target="#delete_designation"]', function() {
        var tempSalarySettingID = $(this).find('.fa-regular.fa-trash-can').data('id');
        console.log(tempSalarySettingID);
        $('#tempSalarySettingID').val(tempSalarySettingID);
    });

  
    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
        var baseUrl = "{{ $baseUrl }}";
    $('#delete_desig').submit(function(e) {
        e.preventDefault();
        var tempSalarySettingID = $('#tempSalarySettingID').val();
        var formData = new FormData(this);
        $.ajax({
                url: baseUrl + '/delete-temp-salary-setting/'+tempSalarySettingID, 
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
                                url: baseUrl + '/temp-salary-setting-list',
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
        var baseUrl = "{{ $baseUrl }}";
    $('#saveAll').click(function(e) {
        e.preventDefault();
        console.log('ok');
       
        $.ajax({
                url: baseUrl + '/create-salary-setting', 
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
                                url: baseUrl + '/temp-salary-setting-list',
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