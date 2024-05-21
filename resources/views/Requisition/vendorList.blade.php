@include('frontend.header')
@include('Requisition.navbar')    
<!-- Page Wrapper -->
<div class="page-wrapper">

<!-- Page Content -->
<div class="content container-fluid">
    
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Vendor List</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Vendor List</li>
                </ul>
            </div>
            <div class="col-auto float-end ms-auto">
                <a href="#" class="btn add-btn add-employee" data-bs-toggle="modal" data-bs-target="#add_employee" id="addEmployeeButton">
                    <i class="fa-solid fa-plus"></i> 
                    Add Vendor
                </a>
            </div>
        </div>
    </div>
    <!-- /Page Header -->
    <!-- error show -->
    @if ($message = Session::get('success'))
                <div class="alert alert-success" role="alert">
                    <div class="txt-success">{{ $message }}</div>
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger" role="alert">
                    <div class="txt-danger">{{ $message }}</div>
                </div>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger" role="alert">
                    <div class="txt-danger">
                        <ul class="list-group">
                            @foreach ($errors->all() as $error)
                                <li><i class="icofont icofont-arrow-right"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        <!-- error show -->
        <div class="card" style="border: 0;box-shadow: 0 0 20px 0 rgba(76,87,125,.2);">
                    <div class="card-body ">
                        <form action="{{route('findVendor')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="inputText4" class="col-form-label">Select Category</label>
                                <select name="requisition_categories_id" id="requisition_categories_id" class="select" required>
                                    <option selected disabled>Open this to select Category</option>
                                    @foreach ($data as $category)
                                        <option value="{{$category->requisition_categories_id}}">{{$category->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin-top: 18px;">
                                <input type="submit" name="submit" value="Search" class="btn btn-primary">
                            </div>
                        </form>

                    </div>
                </div>
    <!-- table start -->
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped custom-table" id="empTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Vendor Name</th>
                            <th>Vendor Document</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($result as $key => $vendor)
                            
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$vendor->requisitionCategory->category_name }}</td>
                                <td>{{$vendor->vendor_name ?? 'N/A'}}</td>
                                <td>
                                    @if($vendor->agreement_attachment)
                                        <iframe src="{{ url('/') . '/' . $vendor->agreement_attachment }}" height="200" width="300"></iframe>
                                    @else
                                        <span>N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item edit-employee" href="{{route('vendorEdit',['id' => $vendor->vendors_id])}}">
                                                <i class="fa-solid fa-pencil m-r-5"></i> Edit
                                            </a>
                                            <a class="dropdown-item delete-employee" href="#" data-bs-toggle="modal" data-bs-target="#delete_employee" data-id="{{ $vendor->vendors_id }}"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
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
    <!-- Add Employee Modal -->
    <div id="add_employee" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('createVendor')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Select Category<span class="text-danger">*</span></label>
                                <select class="form-select" name="requisition_categories_id">
                                    <option selected disabled>Open this to select Category</option>
                                    @foreach ($data as $category)
                                    <option value="{{$category->requisition_categories_id}}">{{$category->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Vendor Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="vendor_name" required>
                            </div>
                        </div>
                       
                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Vendor Document <span class="text-danger">*</span></label>
                                <input class="form-control" type="file" name= "agreement_attachment" accept=".pdf" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
<!-- /Add Employee Modal -->
<!-- Delete Employee Modal -->
<div class="modal custom-modal fade" id="delete_employee" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Delete Vendor</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
                                        <form action="{{route('vendorDelete')}}" method="post">
                                            @csrf
                                            <input id ="vendors_id" class="form-control" name="vendors_id" type="hidden">
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
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

<script>
    CKEDITOR.replace('vendor_description');

    $(document).ready(function() {
        $('#empTable').DataTable({
            "order": [[ 0, "desc" ]] 
        });
    })

    $(document).on('click', '.delete-employee', function(){
        var vendors_id = $(this).data('id');
        $('#vendors_id').val(vendors_id);
    });

</script>