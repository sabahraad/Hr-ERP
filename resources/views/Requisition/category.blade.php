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
                <h3 class="page-title">Category List</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Category List</li>
                </ul>
            </div>
            <div class="col-auto float-end ms-auto">
                <a href="#" class="btn add-btn add-employee" data-bs-toggle="modal" data-bs-target="#add_employee" id="addEmployeeButton">
                    <i class="fa-solid fa-plus"></i> 
                    Add Category
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
    <!-- table start -->
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped custom-table" id="empTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Category Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $key => $category)
                            
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$category->category_name ?? 'N/A'}}</td>
                                <td>{!!$category->category_description ?? 'N/A'!!}</td>
                                <td>
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item edit-employee" href="{{route('categoryEdit',['id'=>$category->requisition_categories_id])}}">
                                                <i class="fa-solid fa-pencil m-r-5"></i> Edit
                                            </a>
                                            <a class="dropdown-item delete-employee" href="#" data-bs-toggle="modal" data-bs-target="#delete_employee" data-id="{{ $category->requisition_categories_id }}"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
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
                <h5 class="modal-title">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('createCategory')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Category Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="category_name" required>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Category Description <span class="text-danger">*</span></label>
                                <textarea id="category_description" name="category_description"></textarea></br>
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
									<h3>Delete Category</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
                                        <form action="{{route('categoryDelete')}}" method="post">
                                            @csrf
                                            <input id ="requisition_categories_id" class="form-control" name="requisition_categories_id" type="hidden">
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
    CKEDITOR.replace('category_description');

    $(document).ready(function() {
        $('#empTable').DataTable({
            "order": [[ 0, "desc" ]] 
        });
    })

    $(document).on('click', '.delete-employee', function(){
        var requisition_categories_id = $(this).data('id');
        $('#requisition_categories_id').val(requisition_categories_id);
    });

</script>