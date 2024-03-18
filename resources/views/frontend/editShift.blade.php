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
                            <input class="form-control" type="text" name="shift_weekends_id" value="{{$shift->shift_weekends_id}}"  hidden>

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
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Weekend</td>
                                                <td class="text-center">
                                                    <input name="Sunday" value="0" hidden>
                                                    <label class="custom_check">
                                                        <input id="sundayCheckbox" name="Sunday" type="checkbox" value="1" {{ $shift->Sunday == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                               
                                                <td class="text-center">
                                                    <input name="Monday" value="0" hidden>
                                                    <label class="custom_check">
                                                        <input id="mondayCheckbox" name="Monday" type="checkbox" value="1" {{ $shift->Monday == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <input name="Tuesday" value="0" hidden>
                                                    <label class="custom_check">
                                                        <input id="tuesdayCheckbox" name="Tuesday" type="checkbox" value="1" {{ $shift->Tuesday == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <input name="Wednesday" value="0" hidden>
                                                    <label class="custom_check">
                                                        <input id="wednesdayCheckbox" name="Wednesday" type="checkbox" value="1" {{ $shift->Wednesday == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <input name="Thursday" value="0" hidden>
                                                    <label class="custom_check">
                                                        <input id="thursdayCheckbox" name="Thursday" type="checkbox" value="1" {{ $shift->Thursday == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <input name="Friday" value="0" hidden>
                                                    <label class="custom_check">
                                                        <input id="fridayCheckbox" name="Friday" type="checkbox" value="1" {{ $shift->Friday == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
                                                <td class="text-center">
                                                    <input name="Saturday" value="0" hidden>
                                                    <label class="custom_check">
                                                        <input id="saturdayCheckbox" name="Saturday" type="checkbox" value="1" {{ $shift->Saturday == 1 ? 'checked' : '' }}>													
                                                        <span class="checkmark"></span>
                                                    </label>																
                                                </td>
										</tr>
									</tbody>
								</table>
							</div>

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
