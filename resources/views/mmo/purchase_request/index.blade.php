@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content_header')
<h1 class="ml-1">Purchase Request</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <!-- <a href="{{ route('head.purchase_request.create') }}" class="btn btn-primary px-5">Upload PR</a> -->
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @php
                        $heads = [
                            'ID',
                            'PR #',
                            'PO #', // Add PO # column header
                            'Name',
                            'Description',
                            'Status',
                            'Image',
                          
                            'Date Requested',
                            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
                        ];

                        $config = [
                            'data' => $data,
                            'order' => [[7, 'desc']], // Sort by the 'Date Created' column (index 7) in descending order
                            'columns' => [null, null, null, null, null, null, null, null, ['orderable' => false]],
                        ];
                    @endphp

                    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" hoverable class="table-custom">
                        @foreach ($config['data'] as $row)
                            <tr>
                                @foreach ($row as $cell)
                                    <td>{!! $cell !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Hold Remarks Modal -->
<div class="modal fade" id="holdModal" tabindex="-1" aria-labelledby="holdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="holdModalLabel">Enter Remarks for Hold</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="hold_id">
                <textarea class="form-control" id="hold_remarks" rows="3" placeholder="Type your remarks here..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="saveHoldBtn">Hold Request</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Denied Purchase Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="deleteForm">
                    @csrf
                    <input type="hidden" id="delete_id">
                    
                    <div class="form-group">
                        <label for="remarks">Enter Remarks:</label>
                        <textarea class="form-control" id="remarks" rows="3" required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Deny</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Purchase Modal -->
<div class="modal fade" id="purchaseModal" tabindex="-1" role="dialog" aria-labelledby="purchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="purchaseModalLabel">Purchase Request Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Purchase request details will be loaded here dynamically -->
                <p><strong>Request Number:</strong> <span id="modalRequestNumber"></span></p>
                <p><strong>Requester Name:</strong> <span id="modalRequesterName"></span></p>
                <p><strong>Description:</strong> <span id="modalDescription"></span></p>
                <p><strong>Admin Remarks:</strong> <span id="modalRemarks"></span></p>
                <p><strong>Approved Date:</strong> <span id="modalApprovedDate"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).on('click', '.Hold', function () {
    var id = $(this).data('id');
    $('#hold_id').val(id); // Store ID in hidden input
    $('#hold_remarks').val(''); // Clear previous input
    $('#holdModal').modal('show'); // Show modal
});

// Handle Hold Request Submission
$('#saveHoldBtn').click(function () {
    var id = $('#hold_id').val();
    var remarks = $('#hold_remarks').val().trim();

    if (remarks === '') {
        Swal.fire('Error', 'Remarks cannot be empty!', 'error');
        return;
    }

    $.ajax({
        url: '/purchase_requests/' + id + '/hold',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            remarks: remarks
        },
        success: function (response) {
            $('#holdModal').modal('hide'); // Hide modal
            Swal.fire(
                'Held!',
                'The purchase request has been put on hold.',
                'success'
            ).then(() => {
                location.reload();
            });
        },
        error: function () {
            Swal.fire('Error', 'Something went wrong!', 'error');
        }
    });
});

$(document).on('click', '.view-purchase', function() { 
    var purchaseId = $(this).data('id');

    // Make an AJAX request to fetch the purchase request details
    $.get('/purchase_requests/' + purchaseId, function(data) {
        // Populate the modal with the fetched data
        $('#modalRequestNumber').text(data.request_number);
        $('#modalRequesterName').text(data.requester_name);
        $('#modalDescription').text(data.description);
        $('#modalRemarks').text(data.remarks);

        // Check if approval date is null
        var formattedDate = data.approval_date ? new Date(data.approval_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A';

        $('#modalApprovedDate').text(formattedDate);
    });
});

    $(document).on('click', '.Accept', function () {
        var id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to accept this purchase request.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Accept',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('purchase.accept') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Accepted!',
                            text: 'The purchase request has been accepted.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Reload page after action
                        });
                    },
                    error: function(response) {
                        Swal.fire('Error', 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });
    $(document).on('click', '.Delete', function () {
        var id = $(this).data('id');
        $('#delete_id').val(id); // Set the ID in the modal
        $('#deleteModal').modal('show'); // Show the modal
    });

    $('#deleteForm').submit(function (e) {
        e.preventDefault();

        var id = $('#delete_id').val();
        var remarks = $('#remarks').val();

        if (remarks.trim() === "") {
            Swal.fire("Error", "Remarks cannot be empty!", "error");
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to Deny this purchase request.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Deny',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('purchase.delete') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        remarks: remarks
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Deny!',
                            text: 'The purchase request has been Denied.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Reload page after deletion
                        });
                    },
                    error: function () {
                        Swal.fire('Error', 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });
</script>
@endsection

