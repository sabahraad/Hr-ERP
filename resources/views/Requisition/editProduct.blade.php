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
                <h3 class="page-title">Edit Product</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Edit Product</li>
                </ul>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="form-body">
                <form action="{{route('productUpdate',['id' => $data->products_id])}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Product Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="product_name" id="product_name" value="{{$data->product_name}}">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Product Description<span class="text-danger">*</span></label>
                                <textarea id="product_description" name="product_description" >{{$data->product_description}}</textarea></br>
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
    CKEDITOR.replace('product_description');
})
</script>