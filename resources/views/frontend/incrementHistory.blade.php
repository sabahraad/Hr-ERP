@include('frontend.header')
@include('frontend.navbar')
<style>
    .select2-container {
        width: 100% !important;
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
                            <h3 class="page-title">Increment History</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Increment History</li>
                            </ul>
                        </div>
                        
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="card" style="border: 0;box-shadow: 0 0 20px 0 rgba(76,87,125,.2);">
                    <div class="card-body ">
                        <form id="myForm">
                            @csrf
                            <div class="form-group">
                                <label for="inputText4" class="col-form-label">Select Employee</label>
                                <select name="emp_id" id="emp_id" class="select">
                                    <option selected disabled>Open this to select Employee</option>
                                    @foreach ($dataArray['data'] as $emp)
                                        <option value="{{$emp['emp_id']}}">{{$emp['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
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
                                        <th>Name</th>
                                        <th>Employee ID</th>
                                        <th>Salary</th>
                                        <th>Joining Date</th>
                                        <th>last Increment Date</th>
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>


<script>
    $(document).ready(function() {
        $('#desigTable').DataTable();
        $('#emp_id').select2();
        var jwtToken = "{{ $jwtToken }}";
        var baseUrl = "{{ $baseUrl }}";
		$('#myForm').submit(function(event) {
			event.preventDefault();    
            var emp_id = $('#emp_id').val();
			$.ajax({
                url: baseUrl + '/employee-salary-history/'+emp_id,
                type: 'GET',
                headers: {
                        'Authorization': 'Bearer ' + jwtToken
                    },
                success: function(response) {
                    console.log(response.message)
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
                            item.joining_date,
                            item.salary_update_date 
                        ];
                        table.row.add(rowData);
                        rowNum++;
                    });
                    
                    table.draw();
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.status == 404) {
                        var table = $('#desigTable').DataTable();
                        table.clear().draw();
                        Swal.fire({
                                icon: 'error',
                                title: 'NO DATA FOUND',
                            });
                    } else {
                        console.log('Error in API call');
                    }
                }
			});
		});
	});
</script>