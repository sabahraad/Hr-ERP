@include('frontend.header')
@include('frontend.navbar')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- Page Wrapper -->
<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Leave Report</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Leave Report</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Date Range Form -->
        <div class="card" style="border: 0;box-shadow: 0 0 20px 0 rgba(76,87,125,.2);">
            <div class="card-body">
                <form id="myForm">
                    @csrf
                    <label for="inputText4" class="col-form-label">Select Date Range:</label><br>
                    <input type="text" id="date_range" class="form-control" name="date_range" required>
                    <br>
                    <div class="form-group" style="margin-top: 18px;">
                        <input type="submit" name="submit" value="Search" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>

        <!-- Export Button -->
        <button id="exportButton" class="btn btn-success mb-5">Download as Excel</button>

        <!-- Table Section -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered custom-table mb-0" id="desigTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Total Leave Count</th>
                                <th>Leave Type</th>
                                <th>Days</th>
                            </tr>
                        </thead>
                        <tbody id="empTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
</div>
<!-- /Page Wrapper -->

<!-- jQuery and SheetJS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
<!-- Date Range Picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize the date range picker
        $('input[name="date_range"]').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        // Base variables
        var jwtToken = "{{ $jwtToken }}";
        var baseUrl = "{{ $baseUrl }}";

        // Form Submission Handler
        $('#myForm').submit(function(e) {
            e.preventDefault();
            
            // Get the date range value
            var dateRange = $('#date_range').val();
            var formData = new FormData(this);
            formData.append('date_range', dateRange);

            // AJAX request
            $.ajax({
                url: baseUrl + '/custom-leave-report',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Log response for debugging
                    console.log(response);

                    // Clear the table body before appending new data
                    $('#empTableBody').empty();

                    let emp_list = [];
                    response.data.forEach(function(item) {
                        if (emp_list[item.emp_id]) {
                            emp_list[item.emp_id]['total_leave'] = emp_list[item.emp_id]['total_leave'] + parseInt(item.leaveApplication_count),
                            emp_list[item.emp_id]['leave_type'].push({
                                'type': item.leave_type,
                                'dates': JSON.parse(item.dateArray)
                            });
                        } else {
                            emp_list[item.emp_id] = {
                                'name': item.name,
                                'total_leave': parseInt(item.leaveApplication_count),
                                'leave_type': [{
                                    'type': item.leave_type,
                                    'dates': JSON.parse(item.dateArray)
                                }]
                            };
                        }
                    });

                    let key = 1;
                    emp_list.forEach(function(item) {
                        $("#empTableBody").append(`
                        <tr>
                        <td rowspan="${item['leave_type'].length}">${key}</td>
                        <td rowspan="${item['leave_type'].length}">${item['name']}</td>
                        <td rowspan="${item['leave_type'].length}">${item.total_leave}</td>
                        <td>${item['leave_type'][0].type}</td>
                        <td>${item['leave_type'][0].dates}</td>
                        </tr>
                        `);
                        if(item['leave_type'].length > 1){
                            item['leave_type'].forEach(function(leave, key_index) {
                                if(key_index > 0){
                                    $("#empTableBody").append(`
                                    <tr>
                                    <td>${leave.type}</td>
                                    <td>${leave.dates}</td>
                                    </tr>
                                    `);
                                }
                            });
                        } 
                        key += 1;
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message
                    });
                }
            });
        });

        // Excel Export Button
        document.getElementById('exportButton').addEventListener('click', function() {
            // Get the table element
            var table = document.getElementById('desigTable');
            
            // Convert the table data to a workbook using SheetJS
            var workbook = XLSX.utils.table_to_book(table, {sheet: "Leave Report"});
            
            // Export the workbook as an Excel file
            XLSX.writeFile(workbook, 'leave_report.xlsx');
        });
    });
</script>
