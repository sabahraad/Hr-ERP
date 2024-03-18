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
                            <h3 class="page-title">Weekend</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Weekend</li>
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
											<th>Days</th>
											<th class="text-center">Sunday</th>
											<th class="text-center">Monday</th>
											<th class="text-center">Tuesday</th>
											<th class="text-center">Wednesday</th>
											<th class="text-center">Thursday</th>
											<th class="text-center">Friday</th>
                                            <th class="text-center">Saturday</th>
                                            <th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>
										<tr>
                                            
											<td>Weekend</td>
                                            <form id="msform">
                                                @csrf
                                                @if($dataArray === null || empty($dataArray['data']))

                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="sundayCheckbox" type="checkbox" >													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                               
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="mondayCheckbox" type="checkbox">													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="tuesdayCheckbox"  type="checkbox" >													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="wednesdayCheckbox" type="checkbox">													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="thursdayCheckbox" type="checkbox" >													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="fridayCheckbox" type="checkbox" >													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="saturdayCheckbox" type="checkbox" >													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                        
                                                <td>
                                                    <button class="btn btn-primary " type="submit">save</button>
                                                </td> 

                                                @else
                                                @foreach ($dataArray['data'] as $weekend )  
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="sundayCheckbox" type="checkbox" {{ $weekend['Sunday'] == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                               
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="mondayCheckbox" type="checkbox" {{ $weekend['Monday'] == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="tuesdayCheckbox"  type="checkbox" {{ $weekend['Tuesday'] == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="wednesdayCheckbox" type="checkbox" {{ $weekend['Wednesday'] == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="thursdayCheckbox" type="checkbox" {{ $weekend['Thursday'] == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="fridayCheckbox" type="checkbox" {{ $weekend['Friday'] == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <label class="custom_check">
                                                        <input id="saturdayCheckbox" type="checkbox"  {{ $weekend['Saturday'] == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                @endforeach
                                                <td>
                                                    <button class="btn btn-primary " type="submit">save</button>
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
            var sunday = document.getElementById('sundayCheckbox').checked ? 1 : 0;
            var monday = document.getElementById('mondayCheckbox').checked ? 1 : 0;
            var tuesday = document.getElementById('tuesdayCheckbox').checked ? 1 : 0;
            var wednesday = document.getElementById('wednesdayCheckbox').checked ? 1 : 0;
            var thursday = document.getElementById('thursdayCheckbox').checked ? 1 : 0;
            var friday = document.getElementById('fridayCheckbox').checked ? 1 : 0;
            var saturday = document.getElementById('saturdayCheckbox').checked ? 1 : 0;
            console.log(sunday,friday);

            var data = {
                "sunday": sunday,
                "monday":monday,
                "tuesday":tuesday,
                "wednesday":wednesday,
                "thursday":thursday,
                "friday":friday,
                "saturday":saturday
            };
            console.log(data);
            
            $.ajax({
                url: baseUrl + '/add-weekend',
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
                        title: 'Weekend added successful',
                        text: 'Your weekend was successfully added!',
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