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
                    <h3 class="page-title">Shift List</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Shift List</li>
                    </ul>
                </div>
                <div class="col-auto float-end ms-auto">
                    <a href="{{route('addShift')}}" class="btn add-btn" >
                        <i class="fa-solid fa-plus"></i> 
                        Add Shift
                    </a>

                    <a href="{{route('showAddEmployeeInShift')}}" class="btn add-btn" style="margin-right: 20px;">
                        <i class="fa-solid fa-plus"></i> 
                        Add Employee In Shift
                    </a>

                    
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <!-- table start -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table" id="empTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Shift Name</th>
                                <th>Check IN Time</th>
                                <th>Check Out Time</th>
                                <th>Grace Time</th>
                                <th>Weekend</th>
                                <th class="text-end no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key =>$shift)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$shift->shifts_title}}</td>
                                <td>{{$shift->shifts_start_time}}</td>
                                <td>{{$shift->shifts_end_time}}</td>
                                <td>{{$shift->shifts_grace_time}}</td>
                                <td>
                                    @foreach ($shift->weekend as $weekend)
                                    {{$weekend}} <br>
                                    @endforeach
                                </td>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item edit-employee" href="{{ route('showEditShift', ['id' => $shift->shifts_id]) }}">
                                            <i class="fa-solid fa-pencil m-r-5"></i> Edit
                                        </a>
                                            <a class="dropdown-item delete-employee" href="{{ route('deleteShift', ['id' => $shift->shifts_id]) }}" ><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                                            <a class="dropdown-item delete-employee" href="{{ route('showAddEmployeeInShift', ['id' => $shift->shifts_id]) }}" ><i class="fas fa-plus-circle m-r-5"></i> Add Employee To This Shift</a>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
				<!-- Delete Employee Modal -->
				<div class="modal custom-modal fade" id="delete_employee" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Delete Employee</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
                                        <form id="deptDelete">
                                            @csrf
                                            <input id ="emp_id" class="form-control" name="emp_id" type="hidden">
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
				<!-- /Delete Employee Modal -->
				
            </div>
			<!-- /Page Wrapper -->
			
        </div>
		<!-- /Main Wrapper -->

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#empTable').DataTable();

    });
</script>