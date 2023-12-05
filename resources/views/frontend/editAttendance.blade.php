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
                            <form method="post" action="" enctype="multipart/form-data">
                                @csrf
                                @foreach ($attendance as $raw)
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Employee Name</label>
                                    <input class="form-control" type="text" name= "employeeName" value="{{$raw->employee_name}}" disabled>
                                    <label class="col-form-label">Check In Time</label>
                                    <input class="form-control" type="text" name= "employeeName" value="{{$raw->created_at}}">
                                    <label class="col-form-label">Check Out Time</label>
                                    <input class="form-control" type="text" name= "employeeName" value="{{$raw->up}}">
                                    <!-- <input class="form-control" type="text" name="deptTitle" > -->
                                    <label class="col-form-label">Attachment</label>
                                    <input class="form-control" type="file" name= "attachment">
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