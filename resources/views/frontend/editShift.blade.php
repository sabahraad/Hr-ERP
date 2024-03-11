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
                            <h3 class="page-title">Edit Shift</h3>
                            <p><span style="color:red;font-weight: bold;">Note :</span> Coordinated Universal Time (UTC) [24-hour clock format]</p>
                        </div>
                    </div>
                </div>
            
                <!-- /Page Header -->
                <form action="{{ route('editShift')}}" method="post">
                    @csrf
                    <div class="row">
                        @foreach ($data as $shift)
                        <div class="col-sm-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label"><b>Shift Name</b></label>
                                        <input class="form-control" type="text"  name="shifts_title" value="{{$shift->shifts_title}}" required>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label"><b>Shift Start Time</b></label>
                                        <input class="form-control timepicker" type="text" name="shifts_start_time" value="{{$shift->shifts_start_time}}"  required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label"><b>Shift End Time</b></label>
                                        <input class="form-control timepicker" type="text" name="shifts_end_time" value="{{$shift->shifts_end_time}}"  required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label"><b>Shift Grace Time</b></label>
                                        <input class="form-control timepicker" type="text" name="shifts_grace_time" value="{{$shift->shifts_grace_time}}"  required>
                                </div>
                            </div>
                            <input class="form-control" type="text" name="shifts_id" value="{{$shift->shifts_id}}"  hidden>
                  
                            <div class="col-sm-12">
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Save</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
    
</div>
        <!-- /Page Wrapper -->
