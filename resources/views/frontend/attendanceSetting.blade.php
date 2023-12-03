@include('frontend.header')
@include('frontend.navbar')
<!-- Page Wrapper -->
<div class="page-wrapper">	
    <!-- Page Content -->
    <div class="content container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
            
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Attendance Settings</h3>
                        </div>
                    </div>
                </div>
            
                <!-- /Page Header -->
                <form id="msform">
                    <div class="row">
                    @forelse ($dataArray['data'] as $setting) 
                        <div class="col-sm-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Office Hour</label>
                                    <input class="form-control timepicker" type="text"  id="office_hour" value="{{$setting['office_hour']}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Start Time</label>
                                    <input class="form-control timepicker" type="text" id="start_time" value="{{$setting['start_time']}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">End Time</label>
                                    <input class="form-control timepicker" type="text" id="end_time" value="{{$setting['end_time']}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Grace Time</label>
                                    <input class="form-control timepicker" type="text" id="grace_time" value="{{ $setting['grace_time'] }}">
                            </div>
                        </div>
                        
                        <input class="form-control" type="text" id="office_hour_type" value="fixed" hidden>
                        
                        <div class="col-sm-12">
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="col-sm-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Office Hour</label>
                                        <input class="form-control timepicker" type="text"  id="office_hour">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Start Time</label>
                                        <input class="form-control timepicker" type="text" id="start_time" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">End Time</label>
                                        <input class="form-control timepicker" type="text" id="end_time" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Grace Time</label>
                                        <input class="form-control timepicker" type="text" id="grace_time" >
                                </div>
                            </div>
                            
                            <input class="form-control" type="text" id="office_hour_type" value="fixed" hidden>
                            
                            <div class="col-sm-12">
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Save</button>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </form>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
    
</div>
        <!-- /Page Wrapper -->

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>

<script>
        $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
    $('#msform').submit(function(e) {
        e.preventDefault();

        var office_hour = $('#office_hour').val();
        var start_time = $('#start_time').val();
        var end_time = $('#end_time').val();
        var grace_time = $('#grace_time').val();
        var office_hour_type = $('#office_hour_type').val();

        var data = {
            "office_hour": office_hour,
            "start_time":start_time,
            "end_time":end_time,
            "grace_time":grace_time,
            "office_hour_type":office_hour_type
        };
        console.log(data);

        $.ajax({
                url: 'https://hrm.aamarpay.dev/api/add-office-hour', 
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
                        title: 'Office Hour Settings added successful',
                        text: 'Your Office Hour Settings was successfully added!',
                        showConfirmButton: false, // Hide the OK button
                        }); 
                        setTimeout(function() {
                            location.reload(); // This will refresh the current page
                        }, 300);
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