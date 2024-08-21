@include('frontend.header')
@include('frontend.navbar')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
    .table {
            --bs-table-bg: transparent; /* Set it to a transparent color */
        }
    .bg-danger td {
            color: white; /* Set text color to white */
        }
    .dt-button{
      color: white !important;
      background-color: #6564ad !important;
      cursor: pointer;
      border-radius: 5px;
      border: none;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      /* margin: 4px 2px; */
    }
    .select2-container {
        width: 100% !important;
        z-index: 9999 !important;
    }

    .select2-selection {
        height: 38px !important; 
    }

    .select2-selection__arrow {
        height: 38px !important;
    }
    /* Custom class for clickable rows */
    .clickable-row {
        cursor: pointer;
        /* Add any additional styling as needed */
    }
</style>
<!-- Page Wrapper -->
<div class="page-wrapper">
			
            <!-- Page Content -->
            <div class="content container-fluid">
            
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Visit Report</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Visit Report</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="card" style="border: 0;box-shadow: 0 0 20px 0 rgba(76,87,125,.2);">
                    <div class="card-body ">
                            <form id="myForm" class="row">
                                @csrf
                                <div class="col-md-6">
                                    <label for="inputText4" class="col-form-label">Select Date Range:</label><br>
                                        <input type="text"  id="date_range" class="form-control" name="date_range">
                                    <br>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputText4" class="col-form-label">Select Employee</label><br>
                                    <select name="emp_id" id="emp_id" class="form-control select2 " required>
                                    <option selected disabled>Open this to select Employee</option>
                                        @foreach ($emp['data'] as $raw)
                                            <option value="{{$raw['emp_id']}}">{{$raw['name']}}</option>
                                        @endforeach
                                    </select>
                                    <br>
                                </div>
                                <div class="form-group" style="margin-top: 18px;">
                                    <input type="submit" style="display: block; width:250px" name="submit" value="Search" class="btn btn-primary mx-auto">
                                </div>
                            </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0" id="desigTable">
                                <thead>
                                    <tr>
                                        <th class="width-thirty">#</th>
                                        <th>Employee Name</th>
                                        <th>Visit Title</th>
                                        <th>Visit Time</th>
                                        <th>Visit DESC</th>
                                        <th>Visit Location</th>
                                        <th>Status</th>
                                        <th>Cancel Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->
             <!-- Map Modal -->
            <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapModalLabel">Map</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="map" style="height: 400px; width: 100%;"></div>
                </div>
                </div>
            </div>
            </div>
            <!-- /Map Modal -->
        </div>
        <!-- /Page Wrapper -->


<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>

    $(document).ready(function() {
        $('head title').text('Expense Report');
        $('#desigTable').DataTable({
        dom: 'Bfrtip', 
        buttons: [
            'excel'
            ]
        });
    });

    $(function() {
    $('input[name="date_range"]').daterangepicker({
        // opens: 'left',
        // autoApply: true,
        locale: {
        format: 'YYYY-MM-DD'
    }
    }, function(start, end, label) {
        console.log("A date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    });

    $(document).ready(function() {
        var jwtToken = "{{ $jwtToken }}";
        var baseUrl = "{{ $baseUrl }}";
        $('#myForm').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                    url: baseUrl + '/visit-report', 
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + jwtToken
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var table = $('#desigTable').DataTable();
                        table.clear().draw();
                        console.log(response.data);
                        var key = 0;
                        response.data.forEach(function(item) {
                            let statusClass = '';
                            let statusText = '';

                            if (item.status === 'complete') {
                                statusClass = 'badge badge-success';  // Green for completed
                                statusText = '<span class="' + statusClass + '">' + item.status + '</span>';
                            } else if (item.status === 'pending') {
                                // statusClass = 'badge badge-secondary';  // Gray for pending
                                statusText = '<span >' + "Pending" + '</span>';
                            } else if (item.status === 'cancel') {
                                statusClass = 'badge badge-danger';  // Red for cancelled
                                statusText = '<span class="' + statusClass + '">' + item.status + '</span>';
                            }
                            var rowNode = table.row.add([
                                key+1,
                                item.name,
                                item.title,
                                item.visit_time,
                                item.desc,
                                '<td><a href="#" class="btn btn-sm btn-info show-map-btn" data-bs-toggle="modal" data-bs-target="#mapModal" data-checkin-lat="' + item.latitude + '" data-checkin-long="' + item.longtitude +'">Show Map</a></td>',
                                statusText,
                                item.cancel_reason ?? "N/A",
                            ]).draw(false).node();
                            key++;
                        });
                    },
                    error: function(xhr, status, error) {
                        
                        if (xhr.status === 422 ) {
                            var errors = xhr.responseJSON.error;
                            var errorMessage = "<ul>";
                            for (var field in errors) {
                                errorMessage += "<li>" + errors[field][0] + "</li>";
                            }
                            errorMessage += "</ul>";
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: errorMessage
                            });
                        }else{
                            Swal.fire({
                                icon: 'error',
                                // title: 'Validation Error',
                                html: xhr.responseJSON.message
                            });
                        }
                    }
                });
        });
    });

    //map script
    $(document).ready(function() {
        var map;  // Declare map variable to be accessible globally

        // Function to initialize the map
        function initializeMap(checkIN_lat, checkIN_long) {
            // Initialize the map
            map = L.map('map').setView([checkIN_lat, checkIN_long], 13);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: 'Raad'
            }).addTo(map);

            // Add a marker for Check-IN location
            L.marker([checkIN_lat, checkIN_long]).addTo(map)
                .bindPopup('Visit Location')
                .openPopup();
        }

        // Handle the modal show event to load the map
        $('#mapModal').on('shown.bs.modal', function (e) {
            var button = $(e.relatedTarget);  // Button that triggered the modal
            var checkIN_lat = button.data('checkin-lat');
            var checkIN_long = button.data('checkin-long');

            // Initialize the map with the provided coordinates
            if (map) {
                map.remove(); // Remove previous map instance if already exists
            }
            console.log(checkIN_lat, checkIN_long);
            initializeMap(checkIN_lat, checkIN_long);
        });
    });

    //map script end
</script>