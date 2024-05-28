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
                <h3 class="page-title">Edit Category</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Edit Category</li>
                </ul>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="form-body">
                <form action="{{route('categoryUpdate',['id' => $data->requisition_categories_id])}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Category Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="category_name" id="category_name" value="{{$data->category_name}}">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Category Description<span class="text-danger">*</span></label>
                                <textarea id="category_description" name="category_description" >{{$data->category_description}}</textarea></br>
                            </div>
                        </div>

                    </div>
                    
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Edit Employee Modal -->
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

<script> 
$(document).ready(function() {
    CKEDITOR.replace('category_description');
})
</script>