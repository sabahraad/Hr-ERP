@include('frontend.header')
@include('frontend.navbar')

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
                            <h3 class="page-title">Expenses</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Expenses</li>
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
                                        <th>Expense Amount</th>
                                        <th>Expense Catagory</th>
                                        <th>Phone Number</th>
                                        <th>Attachment</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($result as $key => $raw)

                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ $raw['name'] }}</td>
                                        <td>{{$raw['total_amount']}}</td>
                                        <td>
                                            {{ $raw['catagory'] }}
                                        </td>
                                        <td>{{$raw['phone_number']}}</td>
                                        <td>
                                            @if($raw['attachment'] == Null)
                                                <div >
                                                    <h4>N/A</h4>
                                                </div>
                                            @else
                                                @php
                                                    $imageExtensions = ['jpeg', 'png', 'gif', 'svg', 'jpg'];
                                                    $pdfExtensions = ['pdf'];
                                                    $excelExtensions = ['xlsx','xls'];
                                                    $extension = pathinfo($raw['attachment'], PATHINFO_EXTENSION);
                                                @endphp

                                                @if (in_array(strtolower($extension), $imageExtensions))
                                                    <div>
                                                        <br>
                                                        <a class="btn btn-primary" href="{{ asset($raw['attachment']) }}" download>Download Image</a>
                                                    </div>
                                                @elseif (in_array(strtolower($extension), $pdfExtensions))
                                                    <br>
                                                    <a class="btn btn-primary" href="{{ url('/') . '/' . $raw['attachment'] }}" download>Download PDF</a>
                                                @elseif (in_array(strtolower($extension), $excelExtensions))
                                                    <a class="btn btn-primary" href="{{ url('/') . '/' . $raw['attachment'] }}" download>Download Excel File</a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($raw['status'] == "pending")
                                                <span style="color: gray;">Pending</span>
                                            @elseif($raw['status'] == "approved")
                                                <span class="badge badge-success">Approved</span>
                                            @else
                                                <span class="badge badge-danger">Declined</span>
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script>
    $(document).ready(function(){
        $('head title').text('Expense Report');
        $('#desigTable').DataTable({
        dom: 'Bfrtip', 
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Download Excel'
            }
            ]
        });
    });
</script>