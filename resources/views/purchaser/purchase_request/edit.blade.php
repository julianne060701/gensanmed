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
            <form id="editForm" action="{{ route('purchaser.purchase_request.update', $purchase->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- PR Number -->
                <div class="form-group">
                    <label for="request_number">PR Number</label>
                    <input type="number" name="request_number" class="form-control" placeholder="Enter PR Number" value="{{ old('request_number', $purchase->request_number ?? '') }}" min="1" required readonly>
                </div>

                <!-- PO Number -->
                <div class="form-group">
                    <label for="po_number">PO Number</label>
                    <input type="text" name="po_number" class="form-control" placeholder="Enter PO Number" value="{{ old('po_number', $purchase->po_number ?? '') }}" required>
                </div>
                
                <!-- Requester Name -->
                <div class="form-group">
                    <label for="requester_name">Requester Name</label>
                    <input type="text" name="requester_name" class="form-control" placeholder="Enter Requester Name" value="{{ old('requester_name', $purchase->requester_name ?? '') }}" required readonly>
                </div>
                
                <!-- Remarks -->
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" class="form-control" placeholder="Enter Remarks (Optional)" readonly>{{ old('remarks', $purchase->remarks ?? '') }}</textarea>
                </div>
                
                <!-- Attachment -->
                <div class="form-group">
                    <label for="attachment_url">Attachment (PDF, Max: 5MB)</label>
                    <input type="file" name="attachment_url" class="form-control" accept=".pdf">
                    @if(isset($purchase) && $purchase->attachment_url)
                        <p><a href="{{ asset($purchase->attachment_url) }}" target="_blank">View Current Attachment</a></p>
                    @endif
                </div>
                
                <!-- Submit Button -->
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('purchaser.purchase_request.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.getElementById('pdfUpload')?.addEventListener('change', function(event) {
        const inputFile = event.target;
        const fileName = inputFile.files[0]?.name || 'Choose file';
        const fileLabel = inputFile.nextElementSibling; // Label element
        fileLabel.textContent = fileName;
    });
</script>
@endsection
