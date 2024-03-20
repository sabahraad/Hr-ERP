@include('frontend.header')
@include('frontend.navbar')
<!-- Page Wrapper -->
<div class="page-wrapper">
			
            <!-- Page Content -->
            <div class="content container-fluid">
            
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Attendance Type</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Attendance Type</li>
                            </ul>
                        </div>
                       
                    </div>
                </div>
                <!-- /Page Header -->
                <div class="col-sm-8 col-md-8 col-lg-8 col-xl-9">
							<div class="table-responsive">
								<table class="table table-striped custom-table">
									<thead>
										<tr>
											<th>Types</th>
											<th class="text-center">Location Based</th>
											<th class="text-center">Remote</th>
											<th class="text-center">Wifi Based</th>
											<th class="text-center">IOT Based</th>
                                            <th class="text-center">Action</th>
										</tr>
                                        
									</thead>
									<tbody>
										<tr>
                                            
											<td>Active Attendance Type</td>
                                            <form id="msform">
                                                @csrf
                                                @if($dataArray === null || empty($dataArray['data']))

                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="location_based" type="checkbox" >													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                               
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="remote" type="checkbox">													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="wifi_based"  type="checkbox" >													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="iot_based" type="checkbox">													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                               
                                        
                                                <td>
                                                    <button class="btn btn-primary " type="submit">save</button>
                                                </td> 

                                                @else
                                                @foreach ($dataArray['data'] as $type )  
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="location_based" type="checkbox" {{ $type['location_based'] == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                               
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="remote" type="checkbox" {{ $type['remote'] == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="wifi_based"  type="checkbox" {{ $type['wifi_based'] == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="iot_based" type="checkbox" {{ $type['iot_based'] == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                
                                                @endforeach
                                                <td>
                                                    <button class="btn btn-primary" type="submit">save</button>
                                                </td> 
                                                @endif
                                                
                                            </form>
											
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
                </div>
				<!-- /Page Content -->
                </div>
            </div>
            <!-- /Page Content -->
          
        </div>
        <!-- /Page Wrapper -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>

<script>

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
        var baseUrl = "{{ $baseUrl }}";
    $('#msform').submit(function(e) {
        e.preventDefault();
        var location_based = document.getElementById('location_based').checked ? 1 : 0;
        var remote = document.getElementById('remote').checked ? 1 : 0;
        var wifi_based = document.getElementById('wifi_based').checked ? 1 : 0;
        var iot_based = document.getElementById('iot_based').checked ? 1 : 0;
        
        console.log(location_based,remote);

        var data = {
            "location_based": location_based,
            "remote":remote,
            "wifi_based":wifi_based,
            "iot_based":iot_based,
        };
        console.log(data);
        
        $.ajax({
                url: baseUrl + '/add-attendance-type',
                type: 'POST',
                contentType: 'application/json',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                data: JSON.stringify(data),
                success: function(response) {
                    console.log(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Attendance Type added successful',
                        text: 'Your Attendance Type was successfully added!',
                        showConfirmButton: false, // Hide the OK button
                        }); 
                        setTimeout(function() {
                            location.reload(); // This will refresh the current page
                        }, 100);
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
    
</script>