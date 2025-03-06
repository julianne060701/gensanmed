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
            <form id="editForm" action="{{ route('admin.purchase.update', $purchase->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-row align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="Pending" {{ $purchase->status == 'Pending' ? 'selected' : ''}}>Pending</option> 
                            <option value="Approved" {{ $purchase->status === 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Denied" {{ $purchase->status === 'Denied' ? 'selected' : '' }}>Denied</option>
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
<form id="deleteBed" method="POST">
    @csrf
    @method('DELETE') <!-- Ensure DELETE method is used -->
    <div class="modal-content">
        <div class="modal-header bg-danger">
            <h4 class="modal-title">Delete</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <h3>Are you sure you want to delete <span id="userNameDisplay"></span>?</h3>
            <input type="hidden" name="deleteId" id="deleteId">
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Delete</button>
        </div>
    </div>
</form>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).on('click', '.Delete', function () {
    let purchaseId = $(this).data('delete');
    let purchaseName = $(this).data('name');
    
    $('#deleteId').val(purchaseId);
    $('#userNameDisplay').text(purchaseName);
    
    // Update form action dynamically
    let deleteUrl = "{{ route('purchaser.purchase.destroy', ':id') }}".replace(':id', purchaseId);
    $('#deleteBed').attr('action', deleteUrl);
});

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
