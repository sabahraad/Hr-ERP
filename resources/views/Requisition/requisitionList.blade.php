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
                <h3 class="page-title">Requisition List</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Requisition List</li>
                </ul>
            </div>
            <div class="col-auto float-end ms-auto">
                <!-- <a href="#" class="btn add-btn add-employee" data-bs-toggle="modal" data-bs-target="#add_employee" id="addEmployeeButton">
                    <i class="fa-solid fa-plus"></i> 
                    Add Category
                </a> -->
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
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $key => $requisition)
                            
                            <tr>
                                <form action="{{route('approveRequisition')}}" id="dform{{$key+1}}" method="post">
                                    @csrf
                                    <td>{{$key+1}}</td>
                                    <td>{{$requisition->category_name ?? 'N/A'}}</td>
                                    <td>{{$requisition->product_name ?? 'N/A'}}</td>
                                    <td>{{$requisition->quantity ?? 'N/A'}}</td>
                                    <td>{{$requisition->reason ?? 'N/A'}}</td>
                                    <td> @if($requisition->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($requisition->status == 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-info">{{ $requisition->status ?? 'N/A' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input name="requisitions_id" value="{{$requisition->requisitions_id}}" hidden>
                                        <input name="action" id="action{{$key+1}}" value="" hidden>
                                        <button class="btn btn-outline-success btn-sm" type="button" value="approved" onclick="confirmSA('approved','{{$key+1}}')" >
                                        <i class="fa fa-check-circle"></i> Approve
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" type="button" value="rejected" onclick="confirmSA('rejected','{{$key+1}}')">
                                        <i class="fa fa-times-circle"></i> Reject
                                        </button>
                                    </td>
                                </form>
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

<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    function confirmSA(action,key){
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(action);
                $("#action" +key).val(action);
                $("#dform" +key).submit();
            }
        });
    }

</script>