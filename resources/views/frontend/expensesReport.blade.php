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
    /* Custom class for clickable rows */
    .clickable-row {
        cursor: pointer;
        /* Add any additional styling as needed */
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
                            <h3 class="page-title">Expense Report</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Expense Report</li>
                            </ul>
                        </div>
                        <!-- <div class="col-auto float-end ms-auto">
                            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_department"><i class="fa-solid fa-plus"></i> Add Attendance</a>
                        </div> -->
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="card" style="border: 0;box-shadow: 0 0 20px 0 rgba(76,87,125,.2);">
                    <div class="card-body ">
                        <form id="myForm">
                            @csrf
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
                                        <th>Employee ID</th>
                                        <th>Department</th>
                                        <th>Designation</th>
                                        <th>Total Expense</th>
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
        </div>
        <!-- /Page Wrapper -->


<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>

    $(document).ready(function() {
        $('head title').text('Expense Report');
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
        $('#myForm').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                    url: baseUrl + '/expense-report-details', 
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
                        console.log(response.data);
                        var key = 0;
                        response.data.forEach(function(item) {
                            var rowNode = table.row.add([
                                key+1,
                                item.name,
                                item.emp_id,
                                item.department,
                                item.designation,
                                item.total_amount_sum
                            ]).draw(false).node();
                            $(rowNode).addClass('clickable-row'); // Add clickable-row class to the row
                            key++;
                        });

                        // Add click event listener to rows with class 'clickable-row'
                        $('#desigTable tbody').off('click').on('click', 'tr.clickable-row', function () {
                            console.log('ok'); // Check if this logs 'ok' when clicking a row
                            var rowData = table.row(this).data();
                            var emp_id = rowData[2]; // Assuming employee ID is in the third column
                            // Define the base URL in JavaScript
                            var baseUrl = "{{ url('/') }}"; // Or use "{{ env('APP_URL') }}" if available

                            window.location.href = baseUrl + '/individual-expense-report/' + emp_id + '/' + response.startDate + '/' + response.endDate;
                        });
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422 ) {
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
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: xhr.responseJSON.message
                            });
                        }
                    }
                });
        });
    });
</script>