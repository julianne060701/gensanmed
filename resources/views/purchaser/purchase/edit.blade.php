@extends('adminlte::page')

@section('title', 'Edit PO')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
@section('content_header')
<h1 class="ml-1">Edit PO</h1>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <form id="editForm" action="{{ route('purchaser.purchase.update', $purchase->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-row align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" required>
                            <!-- <option value="Pending" {{ $purchase->status == 'Pending' ? 'selected' : ''}}>Pending</option> 
                            <option value="Approved" {{ $purchase->status === 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Denied" {{ $purchase->status === 'Denied' ? 'selected' : '' }}>Denied</option> -->
                            <option value="Send to Supplier" {{ $purchase->status === 'Send to Supplier' ? 'selected' : '' }}>Send to Supplier</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="po_number">PO Number</label>
                        <input type="number" name="po_number" class="form-control" value="{{ $purchase->po_number }}" min="1" readonly required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="userName">Name of User</label>
                    <input type="text" name="name" id="userName" class="form-control" value="{{ $purchase->name }}" readonly required>
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="description" id="remarks" class="form-control" readonly rows="3">{{ $purchase->description }}</textarea>
                </div>

                <div class="form-group">
                    <label for="fileUpload">Upload PO Document (PDF)</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="image_url" id="fileUpload" accept=".pdf">
                            <label class="custom-file-label" for="fileUpload">Choose file</label>
                        </div>
                    </div>
                    <small id="fileName" class="text-muted mt-2">No file selected</small>
                </div>

                @if($purchase->image_url)
                    <div class="form-group">
                        <p>Current Document:</p>
                        <a href="{{ asset($purchase->image_url) }}" target="_blank" class="btn btn-primary">
                            View PDF
                        </a>
                    </div>
                @endif

                <button type="button" class="btn btn-success mt-4" id="confirmEdit">Update</button>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to update this Purchase Order?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitEditForm">Yes, Update</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('fileUpload').addEventListener('change', function (event) {
        const inputFile = event.target;
        const fileName = inputFile.files[0]?.name || 'Choose file';
        const fileLabel = inputFile.nextElementSibling;
        const fileNameDisplay = document.getElementById('fileName');

        fileLabel.innerHTML = fileName;
        fileNameDisplay.textContent = `Selected file: ${fileName}`;
    });

    // Show confirmation modal before submitting
    document.getElementById('confirmEdit').addEventListener('click', function () {
        $('#confirmModal').modal('show');
    });

    // Submit form when user confirms
    document.getElementById('submitEditForm').addEventListener('click', function () {
        $('#confirmModal').modal('hide');
        document.getElementById('editForm').submit();
    });

    // Show success alert if redirected with success message
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Updated Successfully',
            text: '{{ session("success") }}',
            confirmButtonColor: '#28a745'
        });
    @endif
</script>
@endsection
