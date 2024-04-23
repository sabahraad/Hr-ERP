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
      background-color: #ff9b44 !important;
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
                            <h3 class="page-title">Attendance</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Attendance</li>
                            </ul>
                        </div>
                        
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0" id="desigTable">
                                <thead>
                                    <tr>
                                        <th class="width-thirty">#</th>
                                        <th>Employee Name</th>
                                        <th>Check In Time</th>
                                        <th>Late Reason</th>
                                        <th>Check Out Time</th>
                                        <th>Early Out Reason</th>
                                        <th>Attendance Edited By</th>
                                        <th>Attendance Edit Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($result as $key => $raw)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ $raw['employee_name'] }}</td>
                                        <td>{{$raw['created_at']}}</td>
                                        <td>
                                            @if($raw['lateINreason'] == Null)
                                                <div >
                                                    <h4>N/A</h4>
                                                </div>
                                            @else
                                                <div >
                                                    <h4>{{ $raw['lateINreason'] }}</h4>
                                                </div>
                                            @endif
                                            
                                        </td>
                                        <td>{{$raw['updated_at']}}</td>
                                        <td>
                                            @if($raw['earlyOUTreason'] == Null)
                                                <div >
                                                    <h4>N/A</h4>
                                                </div>
                                            @else
                                                <div >
                                                    <h4>{{$raw['earlyOUTreason']}}</h4>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($raw['edited_by_name'] == Null)
                                                <div >
                                                    <h4>N/A</h4>
                                                </div>
                                            @else
                                                <div >
                                                    <h4>{{$raw['edited_by_name']}}</h4>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($raw['edit_reason'] == Null)
                                                <div >
                                                    <h4>N/A</h4>
                                                </div>
                                            @else
                                                <div >
                                                    <h4>{{$raw['edit_reason']}}</h4>
                                                </div>
                                            @endif
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
        
        </div>
        <!-- /Page Wrapper -->


<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready(function() {
        $('head title').text('Individual Attendance Report');
        $('#desigTable').DataTable({
        dom: 'Bfrtip', 
        buttons: [
            'excel'
            ]
        });
    });
</script>