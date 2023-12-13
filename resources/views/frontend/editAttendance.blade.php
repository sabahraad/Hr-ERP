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
                            <h3 class="page-title"> Edit Attendance </h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Edit Attendance</li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <a href="{{route('attendanceList')}}" class="btn add-btn" ><i class="fa-solid fa-list"></i>Attendance List</a>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body ">
                        @if ($errors->any())
                            <div class="alert alert-danger" id="errorAlert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    setTimeout(function() {
                                        $("#errorAlert").fadeOut(6000); // 1000 milliseconds = 1 second
                                    });
                                });
                            </script>
                        @endif
                            <form id="msform">
                                @csrf
                                @foreach ($attendance as $raw)
                                <div class="input-block mb-3">
                                    <input type="hidden" value="{{$raw->attendance_id}}" name="attendance_id">
                                    <label class="col-form-label">Employee ID</label>
                                    <input class="form-control" type="text" name= "emp_id" value="{{$raw->emp_id}}" disabled>
                                    <input class="form-control" type="hidden" name= "emp_id" value="{{$raw->emp_id}}">

                                    <label class="col-form-label">Employee Name</label>
                                    <input class="form-control" type="text" name= "employeeName" value="{{$raw->employee_name}}" disabled>
                                    <label class="col-form-label">Check In Time</label>
                                    <input class="form-control" type="datetime-local" name= "created_at" value="{{$raw->created_at}}">
                                    @php
                                        if($raw->OUT == Null){
                                            $raw->updated_at = Null;
                                        }
                                    @endphp
                                    <label class="col-form-label">Check Out Time</label>
                                    <input class="form-control" type="datetime-local" name= "updated_at" value="{{$raw->updated_at}}">
                                    <label class="col-form-label">Reason</label>
                                    <input class="form-control" type="text" name= "edit_reason" required>
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                                @endforeach
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>

<script>

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#msform').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/attendance-edited-by-HR', 
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
                        title: 'Attendance Edit Successful',
                        text: 'Your attendance edit was successful!',
                        showConfirmButton: true, // Enable the Confirm button
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/attendance-list';
                        }
                    });
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