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
                            <h3 class="page-title">Office Location</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Office Location</li>
                            </ul>
                        </div>
                        @if($dataArray === null || empty($dataArray['data']))
                        <div class="col-auto float-end ms-auto">
                            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_department"><i class="fa-solid fa-plus"></i> Add Office Location</a>
                        </div>
                        @endif
                    </div>
                </div>
                <!-- /Page Header -->
                
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <table class="table table-striped custom-table mb-0 datatable">
                                <thead>
                                    <tr>
                                        <th class="width-thirty">#</th>
                                        <th>Office Location</th>
                                        <th>longitude</th>
                                        <th>latitude</th>
                                        <th>radius</th>
                                        <th >Action</th>
                                        <!-- class="text-end" -->
                                    </tr>
                                </thead>
                                <tbody>
                                @if($dataArray === null || empty($dataArray['data']))
                                <tr><td colspan="6" class="text-center">No department is available</td></tr>
                                @else
                                @foreach ($dataArray['data'] as $key =>$officeLocation)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$officeLocation['location_name']}}</td>
                                        <td>{{$officeLocation['longitude']}}</td>
                                        <td>{{$officeLocation['latitude']}}</td>
                                        <td>{{$officeLocation['radius']}}</td>
                                        <td>
                                        <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_department"><i class="fa-solid fa-pencil m-r-5" data-id="{{$officeLocation['office_locations_id']}}"></i> Edit</a>
                                            </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->
            
            <!-- Add Department Modal -->
            <div id="add_department" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Office Location</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <button onclick="getLocation()" class="btn btn-info btn-sm " style="margin-bottom: 13px;">Get Current Location</button>

                            <form id="msform">
                                @csrf
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Office Location Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name= "location_name" >
                                    <label class="col-form-label">longitude</label>
                                    <input class="form-control" type="text" id="longitude" name= "longitude">
                                    <label class="col-form-label">latitude</label>
                                    <input class="form-control" type="text" id="latitude" name= "latitude">
                                    <label class="col-form-label">Radius<span class="text-danger">(In kilometer)</span></label>
                                    <input class="form-control" type="text" name= "radius">
                                    <input class="form-control" type="text" name= "status" value="1" hidden>
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Add Department Modal -->
            
            <!-- Edit Department Modal -->
            <div id="edit_department" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Office Location</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <button onclick="getLocation()" class="btn btn-info btn-sm " style="margin-bottom: 13px;">Get Current Location</button>

                            <form id="editSubmit">
                            @csrf
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Office Location Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name= "location_name" id="locationName">
                                    <label class="col-form-label">longitude</label>
                                    <input class="form-control" type="text" id="longitude1" name= "longitude">
                                    <label class="col-form-label">latitude</label>
                                    <input class="form-control" type="text" id="latitude1" name= "latitude">
                                    <label class="col-form-label">Radius<span class="text-danger">(In kilometer)</span></label>
                                    <input class="form-control" type="text" name= "radius" id= "radius">
                                    <input class="form-control" type="text" name= "status" value="1" hidden>
                                    <input class="form-control" type="text" id= "locationID" name="office_locations_id" hidden>

                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Edit Department Modal -->

            <!-- Delete Department Modal -->
            <div class="modal custom-modal fade" id="delete_department" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Department</h3>
                                <p>Are you sure want to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                    <form id="deptDelete">
                                        @csrf
                                        <input id ="dept_id" class="form-control" name="dept_id" type="hidden">
                                        <button style="padding: 10px 74px;" type="submit" class="btn btn-primary continue-btn">Delete</button>
                                    </form>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Delete Department Modal -->
            
        </div>
        <!-- /Page Wrapper -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>

<script>
    function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        document.getElementById("demo").innerHTML = "Geolocation is not supported by this browser.";
    }
    }

    function showPosition(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    console.log(latitude,longitude);
    $('#latitude').val(latitude);
    $('#longitude').val(longitude);
    $('#latitude1').val(latitude);
    $('#longitude1').val(longitude);
    }


    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
        var baseUrl = "{{ $baseUrl }}";
        $('#msform').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: baseUrl + '/add-office-location', 
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Office Location added successful',
                        text: 'Your Office Location created successful!',
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

        $('.dropdown-item[data-bs-target="#edit_department"]').click(function() {
            // Get the dept_id from the clicked element's data-id attribute
            var locationID = $(this).find('.fa-pencil').data('id');
            var trElement = $(this).closest('tr');
            // Find the 'td' elements within the 'tr'
            var location_name = trElement.find('td:eq(1)').text();
            var longitude = trElement.find('td:eq(2)').text();
            var latitude = trElement.find('td:eq(3)').text();
            var radius = trElement.find('td:eq(4)').text();
            $('#locationName').val(location_name);
            $('#latitude1').val(latitude);
            $('#longitude1').val(longitude);
            $('#radius').val(radius);
            $('#locationID').val(locationID);
            // Show the modal
            $('#edit_department').modal('show');
        });

        $('#editSubmit').submit(function(e) {
            e.preventDefault();
            var locationID = $('#locationID').val();
            console.log(locationID);

            var formData = new FormData(this);

            $.ajax({
                url: baseUrl + '/edit/office-location/'+locationID, 
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Office Location Edited successfully',
                        text: 'Your Office Location edit was successful!',
                        showConfirmButton: false, 
                    });
                    setTimeout(function() {
                            location.reload(); // This will refresh the current page
                        }, 200);
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