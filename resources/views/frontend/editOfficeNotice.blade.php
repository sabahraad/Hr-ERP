@include('frontend.header')
@include('frontend.navbar')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
<!-- Page Wrapper -->
<div class="page-wrapper">
			
            <!-- Page Content -->
            <div class="content container-fluid">
            
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title"> Edit Office Notice</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Edit Office Notice</li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <a href="{{route('officeNotice')}}" class="btn add-btn" ><i class="fa-solid fa-list"></i>Office Notice List</a>
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
                            <form method="post" action="{{route('editOfficeNotice',$data->office_notices_id)}}" enctype="multipart/form-data">
                                @csrf
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Notice Description<span class="text-danger">*</span></label>
                                    <textarea id="notice" name="notice">{!!$data->notice!!}</textarea></br>
                                    <!-- <input class="form-control" type="text" name="deptTitle" > -->
                                    <label class="col-form-label">Attachment</label>
                                    <input class="form-control" type="file" name= "attachment">
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

<script>

    CKEDITOR.replace('notice');
</script>