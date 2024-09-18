<!-- role = 2 = hr -->
@if (session('role') == 2)
    @include('frontend.header')
    @include('frontend.navbar')
@else
    @include('Director.header')
    @include('Director.navbar')
@endif

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
</style>
<!-- Page Wrapper -->
<div class="page-wrapper">
			
    <!-- Page Content -->
    <div class="content container-fluid">
    
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Present Employee</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Present Employee</li>
                    </ul>
                </div>
                <div class="col-auto float-end ms-auto">
                    
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <!-- table start -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table" id="empTable">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Check In Time</th>
                                <th>Check Out Time</th>
                                <th>late Check In Reason</th>
                                <th>Early Out Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $employee)
                            @if ($employee->INstatus == 2)
                            <tr >
                                <td style="background: red;color: white;">{{$employee->emp_id}}</td>
                                <td style="background: red;color: white;">{{$employee->employee_name}}</td>
                                <td style="background: red;color: white;">{{$employee->created_at}}</td>
                                <td style="background: red;color: white;">{{$employee->updated_at ?? 'N/A'}}</td>
                                <td style="background: red;color: white;">{{$employee->lateINreason ?? 'N/A'}}</td>
                                <td style="background: red;color: white;">{{$employee->earlyOUTreason ?? 'N/A'}}</td>
                            </tr>
                            @else
                            <tr>
                                <td>{{$employee->emp_id}}</td>
                                <td>{{$employee->employee_name}}</td>
                                <td>{{$employee->created_at}}</td>
                                <td>{{$employee->updated_at ?? 'N/A'}}</td>
                                <td>{{$employee->lateINreason ?? 'N/A'}}</td>
                                <td>{{$employee->earlyOUTreason ?? 'N/A'}}</td>
                            </tr>
                            @endif
                            
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
	
            </div>
			<!-- /Page Wrapper -->
			
        </div>
		<!-- /Main Wrapper -->
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#empTable').DataTable({
        dom: 'Bfrtip', 
        buttons: [
            'excel'
            ]
        });
    });
</script>