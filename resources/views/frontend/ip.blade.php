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
                            <h3 class="page-title">IP/Wifi</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">IP</li>
                            </ul>
                        </div>
                        @if($dataArray === null || empty($dataArray['data']))
                        <div class="col-auto float-end ms-auto">
                            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_department"><i class="fa-solid fa-plus"></i> Add IP/Wifi</a>
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
                                        <th>IP</th>
                                        <th>Wifi Name</th>
                                        <th >Action</th>
                                        <!-- class="text-end" -->
                                    </tr>
                                </thead>
                                <tbody>
                                @if($dataArray === null || empty($dataArray['data']))
                                <tr><td colspan="4" class="text-center">No IP/Wifi details is available</td></tr>
                                @else
                                    @foreach($dataArray['data'] as $key =>$ipDetail)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>@foreach(json_decode($ipDetail['ip']) as $ip)
                                        {{$ip}}<br>
                                        @endforeach</td>
                                        <td>{{$ipDetail['wifiName'] ?: 'N/A'}}</td>
                                        <td>
                                        <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_department"><i class="fa-solid fa-pencil m-r-5" data-id="{{$ipDetail['ip_id']}}"></i> Edit</a>
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
                            <h5 class="modal-title">Add IP/Wifi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="msform">
                                @csrf
                                <div class="input-block mb-3">
                                    <div id="ipInputs">
                                        <!-- IP address input fields will be dynamically added here -->
                                    </div>
                                    <button type="button" class="btn btn-primary btn" style="margin: 15px;" onclick="addIpInput()">Add IP</button>
                                    <button type="button" class="btn btn-primary btn" onclick="removeIpInput()">Cancel</button><br>
                                    <label class="col-form-label">Wifi Name</label>
                                    <input class="form-control" type="text" name= "wifiName">
                                    <input class="form-control" type="text" name= "status" value=1 hidden>
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
                            <h5 class="modal-title">Edit Department</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editSubmit">
                            @csrf
                                <div class="input-block mb-3">
                                    <div id="container"></div>
                                    <div id="ipInputsEdit">
                                        <!-- IP address input fields will be dynamically added here -->
                                    </div>
                                    <button type="button" class="btn btn-primary btn" style="margin: 15px;" onclick="addIpInput()">Add IP</button>
                                    <button type="button" class="btn btn-primary btn" onclick="removeIpInput()">Cancel</button><br>

                                    <label class="col-form-label">Wifi Name</label>
                                    <input id ="wifiName" class="form-control" name="wifiName" type="text">
                                    <input class="form-control" type="text" name= "status" value="1" hidden>
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


        </div>
        <!-- /Page Wrapper -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>

<script>

    function addIpInput() {
        const ipInputs = document.getElementById('ipInputs');
        const ipInputsEdit = document.getElementById('ipInputsEdit');

        const input = document.createElement('input');
        input.classList.add('form-control'); // Replace 'yourClassName' with the desired class name
        input.type = 'text';
        input.name = 'ip[]';
        input.placeholder = 'Enter IP address';
        const inputCopy = input.cloneNode(true); // Create a copy of the input element
        ipInputs.appendChild(input);
        ipInputsEdit.appendChild(inputCopy);
    }

    function removeIpInput() {
        const ipInputs = document.getElementById('ipInputs');
        const ipInputsEdit = document.getElementById('ipInputsEdit');

        // Check if there are any input fields to remove
        if (ipInputs.children.length > 0) {
            ipInputs.removeChild(ipInputs.lastChild);
        }

        if (ipInputsEdit.children.length > 0) {
            ipInputsEdit.removeChild(ipInputsEdit.lastChild);
        }
    }

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#msform').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/add-IP', 
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
                        title: 'IP/WIfi added successful',
                        text: 'Your IP/Wifi was successfully added!',
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



    $(document).ready(function() {
    $('.dropdown-item[data-bs-target="#edit_department"]').click(function() {
        // Get the dept_id from the clicked element's data-id attribute
            var deptId = $(this).find('.fa-pencil').data('id');

            // Log the dept_id to the console
            console.log(deptId);
            var trElement = $(this).closest('tr');

            // Find the 'td' elements within the 'tr'
            var ipList = trElement.find('td:eq(1)').text();
            var ipArray = ipList.split('\n');
            // Assuming `container` is the element where you want to add the input fields
            var container = document.getElementById('container'); 

            ipArray.forEach(function(ip) {
                if (ip && ip.trim() !== "") {
                var input = document.createElement('input');
                input.type = 'text';
                input.name = 'ip[]';
                input.classList.add('form-control'); // Replace 'yourClassName' with the desired class name
                input.value = ip.trim();
                container.appendChild(input);
                }
            });


            var wifiName = trElement.find('td:eq(2)').text();

            // Log the data to the console
            console.log('ipList:', ipList);
            console.log('wifiName:', wifiName);
            $('#ipList').val(ipList);
            $('#wifiName').val(wifiName);
            $('#dept_id').val(deptId);
            // Show the modal
            $('#edit_department').modal('show');
        });
    });



    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#editSubmit').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/add-IP', 
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
                        title: 'IP/Wifi Edited successfully',
                        text: 'Your Ip address are edited successfully!',
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