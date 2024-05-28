@include('frontend.header')
@include('frontend.navbar')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Page Wrapper -->
<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Office Notice</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Office Notice</li>
                    </ul>
                </div>
                <div class="col-auto float-end ms-auto">
                    <a href="{{route('addOfficeNotice')}}" class="btn add-btn"><i class="fa-solid fa-plus"></i> Add office Notice</a>
                </div>
            </div>
        </div>
        @if (session('success'))
        <div class="alert alert-success" id="successAlert">
            {{ session('success') }}
        </div>
        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $("#successAlert").fadeOut(6000); // 1000 milliseconds = 1 second
                });
            });
        </script>
        @endif
        <!-- /Page Header -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0" id="deptTable">
                        <thead>
                            <tr>
                                <th class="width-thirty">#</th>
                                <th>Notice Description</th>
                                <th>Attachment</th>
                                <th>Action</th>
                                <!-- class="text-end" -->
                            </tr>
                        </thead>
                        <tbody>
                            @if($dataArray === null || empty($dataArray['data']))
                            <tr>
                                <td colspan="4" class="text-center">No Office Notice is available</td>
                            </tr>
                            @else
                            @foreach($dataArray['data'] as $key =>$officeNotice)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>
                                    <div>
                                        {!! $officeNotice['notice'] !!}
                                    </div>
                                </td>
                                <td>
                                    @if($officeNotice['attachment'] == Null)
                                        <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
                                            <h4>N/A</h4>
                                        </div>
                                    @else
                                        @php
                                            $imageExtensions = ['jpeg', 'png', 'gif', 'svg', 'jpg'];
                                            $excelExtensions = ['xlsx','xls'];
                                            $extension = pathinfo($officeNotice['attachment'], PATHINFO_EXTENSION);
                                        @endphp

                                        @if (strtolower($extension) === 'pdf')
                                            <iframe src="{{ url('/') . '/' . $officeNotice['attachment'] }}" height="400" width="500"></iframe>
                                        @elseif (in_array(strtolower($extension), $imageExtensions))
                                            <img src="{{ url('/') . '/' . $officeNotice['attachment'] }}" alt="Image" height="100" width="200">
                                            <br>
                                            <a style="margin: 27px;" class="btn btn-primary" href="{{ url('/') . '/' . $officeNotice['attachment'] }}" download>Download Image</a>
                                        @elseif (in_array(strtolower($extension), $excelExtensions))
                                            <a class="btn btn-primary" href="{{ url('/') . '/' . $officeNotice['attachment'] }}" download>Download The Excel File</a>
                                        @endif
                                    @endif
                                </td>
                                
                                <td>
                                    <div class="dropdown dropdown-action">
                                            <a class="btn btn-primary" href="{{route('showEditOfficeNotice',$officeNotice['office_notices_id'])}}"><i class="fa-solid fa-pencil m-r-5" data-id="{{ $officeNotice['office_notices_id'] }}"></i> Edit</a>
                                            <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#delete_department"><i class="fa-regular fa-trash-can m-r-5" data-id="{{ $officeNotice['office_notices_id'] }}"></i> Delete</a>
                                            <!-- <a class="dropdown-item " href="#" data-bs-toggle="modal" data-bs-target="#delete_department"><i class="fa-regular fa-trash-can m-r-5" data-id="{{ $officeNotice['office_notices_id'] }}"></i> Delete</a> -->
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <thead>
                            <tr>
                                <th class="width-thirty">#</th>
                                <th>Notice Description</th>
                                <th>Attachment</th>
                                <th>Action</th>
                                <!-- class="text-end" -->
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->

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
                                    <input id="officeNoticeId" class="form-control" name="officeNoticeId" type="hidden">
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

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

<script>
    CKEDITOR.replace('notice');
    $(document).ready(function() {
        $('#deptTable').DataTable();
        $('.btn.btn-primary[data-bs-target="#delete_department"]').click(function(e) {
            e.preventDefault();
            var officeNoticeId = $(this).find('.fa-regular').data('id');
            $('#officeNoticeId').val(officeNoticeId);
        });
        var baseUrl = "{{ $baseUrl }}";
        var jwtToken = "{{ $jwtToken }}";
        $('#deptDelete').submit(function(e) {
            e.preventDefault();
            var officeNoticeId = $('#officeNoticeId').val();
            console.log(officeNoticeId);

            $.ajax({
                url: baseUrl + '/delete/notice/' + officeNoticeId,
                type: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + jwtToken
                },
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Notice successfully deleted',
                        text: 'You have successfully deleted a office notice',
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