@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">

@section('content_header')
<h1 class="ml-1">Purchase Order</h1>
@stop

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-end mb-3">
        <!-- <a href="{{ route('admin.purchase.create') }}" class="btn btn-primary px-5">Upload PO</a> -->
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @php
                        $heads = [
                            'ID',
                            'PO #',
                            'Name',
                            'Remarks',
                            'Status',
                            'Attachment',
                            'My Attachment',
                            'Date Created',
                            'Total Duration',
                            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
                        ];

                        $config = [
                            'data' => $data,
                            'order' => [[7, 'desc']], // Sort by the 'Date Created' column (index 6) in descending order
                            'columns' => [null, null, null, null, null, null, null, null, null, ['orderable' => false]],
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
<!-- Upload PDF Modal -->
<div class="modal fade" id="uploadPdfModal" tabindex="-1" aria-labelledby="uploadPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="uploadPdfForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadPdfModalLabel">Upload PDF before Acceptance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="upload_id" name="upload_id">
                    <div class="form-group">
                        <label for="pdfFile">Select PDF File</label>
                        <input type="file" name="pdf_file" id="pdfFile" class="form-control-file" accept="application/pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Upload & Accept</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
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
                        <button type="submit" class="btn btn-danger">Denied</button>
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
                <h5 class="modal-title" id="purchaseModalLabel">Purchase Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Purchase request details will be loaded here dynamically -->             
                <p><strong>PO Number:</strong> <span id="modalPoNumber"></span></p>
                <p><strong>Name:</strong> <span id="modalName"></span></p>
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
            text: "You are about to Deny this purchase order.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Deny',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.purchase.delete') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        remarks: remarks
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Deny!',
                            text: 'The purchase order has been Denied.',
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

// Handle Hold Request Submission
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
        url: '{{ url("admin/purchase") }}/' + id + '/hold',
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

    $.get('/admin/purchase/' + purchaseId, function(data) {
        if (data.error) {
            Swal.fire('Error', data.error, 'error');
            return;
        }

        // Populate the modal with the fetched data
        $('#modalPoNumber').text(data.po_number);
        $('#modalName').text(data.name);
        $('#modalDescription').text(data.description);
        $('#modalRemarks').text(data.remarks);
        
        // Format approval date if available
        var formattedDate = data.approval_date ? new Date(data.approval_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A';
        $('#modalApprovedDate').text(formattedDate);

        // Show the modal
        $('#purchaseModal').modal('show');
    }).fail(function() {
        Swal.fire('Error', 'Failed to fetch purchase details.', 'error');
    });
});

          
$(document).on('click', '.Accept', function () {
    const id = $(this).data('id');
    $('#upload_id').val(id);
    $('#pdfFile').val(''); // clear file input
    $('#uploadPdfModal').modal('show');
});

$('#uploadPdfForm').submit(function (e) {
    e.preventDefault();

    Swal.fire({
        title: 'Are you sure?',
        text: 'Are you sure you want to accept and upload this Purchase Order?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, upload and accept',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');

            Swal.fire({
                title: 'Uploading...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('purchase.uploadAndAcceptOrder') }}", // Make sure this route exists
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    Swal.fire({
                        title: 'Accepted!',
                        text: response.message || 'PDF uploaded and request accepted.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong!', 'error');
                }
            });
        }
    });
});
</script>
@endsection


