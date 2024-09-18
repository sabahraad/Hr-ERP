<!-- role = 2 = hr -->
@if (session('role') == 2)
    @include('frontend.header')
    @include('frontend.navbar')
@else
    @include('Director.header')
    @include('Director.navbar')
@endif
<!-- Page Wrapper -->
<div class="page-wrapper">
			
    <!-- Page Content -->
    <div class="content container-fluid">
    
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Absent Employee</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Absent Employee</li>
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
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $employeeName => $employeeId)
                            <tr>
                                <td>{{ $employeeId }}</td>
                                <td>{{ $employeeName }}</td>
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
			
        </div>
		<!-- /Main Wrapper -->
<script>
    $(document).ready(function() {
        $('#empTable').DataTable({
        dom: 'Bfrtip'
        });
    });
</script>