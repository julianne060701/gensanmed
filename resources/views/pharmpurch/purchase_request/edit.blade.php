@extends('adminlte::page')

@section('title', 'Edit PR')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
@section('content_header')
<h1 class="ml-1">Edit PR</h1>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form id="editForm" action="{{ route('pharmpurch.purchase_request.update', $purchase->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- PR Number -->
                <div class="form-group">
                    <label for="request_number">PR Number</label>
                    <input type="text" name="request_number" class="form-control" placeholder="Enter PR Number" value="{{ old('request_number', $purchase->request_number ?? '') }}" required readonly>
                </div>

                <!-- PO Number -->
                <div class="form-group">
                    <label for="po_number">PO Number</label>
                    <input type="text" name="po_number" class="form-control" placeholder="Enter PO Number" value="{{ old('po_number', $purchase->po_number ?? '') }}">
                    <small class="form-text text-muted">Enter PO number to approve this purchase request.</small>
                </div>
                
                <!-- Requester Name -->
                <div class="form-group">
                    <label for="requester_name">Requester Name</label>
                    <input type="text" name="requester_name" class="form-control" placeholder="Enter Requester Name" value="{{ old('requester_name', $purchase->requester_name ?? '') }}" required readonly>
                </div>
                
                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" placeholder="Enter description (Optional)" rows="3">{{ old('description', $purchase->description ?? '') }}</textarea>
                </div>
                
                <!-- PDF Upload (Optional) -->
                <div class="form-group">
                    <label for="attachment_url">Update PR (PDF) - Optional</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="attachment_url" id="pdfUpload" accept="application/pdf">
                            <label class="custom-file-label" for="pdfUpload">Choose PDF (Optional)</label>
                        </div>
                    </div>
                    <small class="form-text text-muted">Leave blank to keep current file. Maximum file size: 20MB. Only PDF files are allowed.</small>
                    @if($purchase->attachment_url)
                        <p class="mt-2">
                            <small>Current file: <a href="{{ asset($purchase->attachment_url) }}" target="_blank">{{ basename($purchase->attachment_url) }}</a></small>
                        </p>
                    @endif
                </div>
                
                <!-- Submit Button -->
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('pharmpurch.purchase_request.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Show selected file name
    document.getElementById('pdfUpload')?.addEventListener('change', function(event) {
        const inputFile = event.target;
        const file = inputFile.files[0];
        const fileLabel = inputFile.nextElementSibling;

        if (file) {
            const fileName = file.name;
            const fileExtension = fileName.split('.').pop().toLowerCase();

            if (fileExtension !== 'pdf') {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Only PDF files are allowed!',
                });
                inputFile.value = '';
                fileLabel.textContent = 'Choose PDF (Optional)';
                return;
            }

            // Check file size (20MB = 20971520 bytes)
            if (file.size > 20971520) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'File size must be less than 20MB!',
                });
                inputFile.value = '';
                fileLabel.textContent = 'Choose PDF (Optional)';
                return;
            }

            fileLabel.textContent = fileName;
        } else {
            fileLabel.textContent = 'Choose PDF (Optional)';
        }
    });
</script>
@endsection

