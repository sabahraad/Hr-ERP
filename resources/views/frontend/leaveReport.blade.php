@include('frontend.header')
@include('frontend.navbar')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .table {
        --bs-table-bg: transparent;
        /* Set it to a transparent color */
    }

    .bg-danger td {
        color: white;
        /* Set text color to white */
    }

    .dt-button {
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
</style>
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
                        <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Leave Report</li>
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
                    <input type="text" id="date_range" class="form-control" name="date_range">
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
                    <table class="table table-bordered custom-table mb-0" id="desigTable">
                        <thead>
                            <tr>
                                <th class="width-thirty">#</th>
                                <th>Employee Name</th>
                                <th>Total Leave Count</th>
                                <th>Leave Type</th>
                                <th>Days</th>
                            </tr>
                        </thead>
                        <tbody id="empTableBody">
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
        // $('#desigTable').DataTable({
        //     dom: 'Bfrtip',
        //     buttons: [
        //         'excel'
        //     ]
        // });
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
                url: baseUrl + '/custom-leave-report',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // var table = $('#desigTable').DataTable();
                    // table.clear().draw();
                    console.log(response.data);

                    // RAFAT

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
                                console.log(key_index)
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

                    
                    $('#desigTable').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel'
                        ]
                    });

                    // RAFAT

                    // var key = 0;
                    // response.data.forEach(function(item) {
                    //     console.log(item);
                    //     table.row.add([
                    //         key + 1,
                    //         '<td>' + item.name + '</td>',
                    //         item.leaveApplication_count,
                    //         item.leave_type,
                    //         item.dateArray,
                    //     ]).draw(false);
                    //     key++;
                    // });
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
                    } else {
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