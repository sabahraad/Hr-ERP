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
                <h3 class="page-title">Edit Vendor</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Edit Vendor</li>
                </ul>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="form-body">
                <form action="{{route('super-admin.vendorUpdate',['id' => $data->vendors_id])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Vendor Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="vendor_name" id="vendor_name" value="{{$data->vendor_name}}">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Vendor Document<span class="text-danger">*</span></label>
                                <input class="form-control" type="file" name= "agreement_attachment" accept=".pdf" >
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
