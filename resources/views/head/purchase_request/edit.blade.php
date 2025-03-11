@extends('adminlte::page')

@section('title', 'Edit/View PR')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">

@section('content_header')
    <h1 class="ml-1">{{ isset($purchase) ? 'Edit Purchase Request' : 'Upload PR' }}</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="" 
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($purchase))
                        @method('PUT')
                    @endif
                    
                    <div class="form-row align-items-center mb-3">
                        <!-- PR Number -->
                        <div class="col-md-4">
                            <label for="request_number">PR Number</label>
                            <input type="number" name="request_number" class="form-control" 
                                   value="{{ old('request_number', $purchase->request_number ?? '') }}" 
                                   placeholder="Enter PR Number" min="1" required>
                        </div>

                        <!-- PO Number -->
                        <div class="col-md-4">
                            <label for="po_number">PO Number</label>
                            <input type="text" name="po_number" class="form-control" 
                                   value="{{ old('po_number', $purchase->po_number ?? '') }}" 
                                   placeholder="Enter PO Number" required>
                        </div>
                    </div>

                    <!-- Name of Requester -->
                    <div class="form-group">
                        <label for="requester_name">Name of Requester</label>
                        <input type="text" name="requester_name" id="requester_name" class="form-control" 
                               value="{{ old('requester_name', $purchase->requester_name ?? '') }}" 
                               placeholder="Enter requester name" required>
                    </div>
                    
                    {{-- Remarks --}}
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter remarks">{{ old('remarks', $purchase->remarks ?? '') }}</textarea>
                    </div>

                    {{-- PDF Upload --}}
                    <div class="form-group">
                        <label for="pdfUpload">Upload PR (PDF)</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="attachment_url" id="pdfUpload" accept="application/pdf">
                                <label class="custom-file-label" for="pdfUpload">Choose PDF</label>
                            </div>
                        </div>
                    </div>

                    <!-- Display existing PDF if available -->
                    @if(isset($purchase) && $purchase->attachment_url)
                        <div class="form-group">
                            <label>Current PR Document:</label>
                            <br>
                            <a href="{{ asset($purchase->attachment_url) }}" target="_blank" class="btn btn-primary btn-sm">View PR (PDF)</a>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-success mt-4">{{ isset($purchase) ? 'Update PR' : 'Submit PR' }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    document.getElementById('pdfUpload').addEventListener('change', function(event) {
        const inputFile = event.target;
        const fileName = inputFile.files[0]?.name || 'Choose file';
        const fileLabel = inputFile.nextElementSibling; // Label element

        fileLabel.textContent = fileName;
    });
</script>
@endsection
