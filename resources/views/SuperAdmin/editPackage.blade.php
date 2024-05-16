@include('SuperAdmin.header')
@include('SuperAdmin.navbar')
<!-- Page Wrapper -->
<div class="page-wrapper">

<!-- Page Content -->
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Edit Package</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Edit Package</li>
                </ul>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="form-body">
                <form action="{{route('super-admin.editPackage',['id' => $package->packages_id])}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Package Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="package_name" id="package_name" value="{{$package->package_name}}">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Package Price <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="package_price" id="package_price" value="{{$package->package_price}}">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Per User Price <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="per_user_price" id="per_user_price" value="{{$package->per_user_price}}">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Description<span class="text-danger">*</span></label>
                                <textarea id="description" name="description" >{{$package->description}}</textarea></br>
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
    CKEDITOR.replace('description');
})


</script>