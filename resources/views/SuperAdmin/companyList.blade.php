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
                <h3 class="page-title">Company</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Company</li>
                </ul>
            </div>
            <div class="col-auto float-end ms-auto">
                <!-- <a href="#" class="btn add-btn add-employee" data-bs-toggle="modal" data-bs-target="#add_employee" id="addEmployeeButton">
                    <i class="fa-solid fa-plus"></i> 
                    Add Employee
                </a> -->
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
                            <th>Company ID</th>
                            <th>Company Name</th>
                            <th>Contact Number</th>
                            <th>Company Details</th>
                            <th>Address</th>
                            <th class="text-end no-sort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $company)
                            
                            <tr>
                                <td>{{$company->company_id}}</td>
                                <td>
                                    <h2 class="table-avatar">
                                        <a href="#" class="avatar"><img src="{{asset($company->logo)}}" alt="User Image"></a>
                                        <a href="#">{{$company->companyName}}</a>
                                    </h2>
                                </td>
                                <td>{{$company->contactNumber ?? 'N/A'}}</td>
                                <td>{{$company->companyDetails ?? 'N/A'}}</td>
                                <td>{{$company->address ?? 'N/A'}}</td>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item edit-employee" href="#" data-bs-toggle="modal" data-bs-target="#edit_employee" id="editEmployeeButton" data-id="{{ $company->company_id }}">
                                                <i class="fa-solid fa-pencil m-r-5"></i> Edit
                                            </a>
                                            <a class="dropdown-item delete-employee" href="#" data-bs-toggle="modal" data-bs-target="#delete_employee" data-id="{{ $company->company_id }}"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
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
<script> 
$(document).ready(function() {
    $('#empTable').DataTable({
        "order": [[ 0, "desc" ]] 
    });
})

</script>