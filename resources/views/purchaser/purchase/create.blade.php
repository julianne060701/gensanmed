@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="ml-1">Upload PO</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('purchaser.purchase.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-row align-items-center mb-3">
                        
                         <!-- PO Number  -->
                        <div class="col-md-4">
                            <label for="po_number">PO Number</label>
                            <input type="number" name="po_number" class="form-control" placeholder="Enter PO Number" min="1" required>
                        </div>
                    </div>

                    <!-- Name of User -->
                    <div class="form-group">
                        <label for="userName">Name of User</label>
                        <input type="text" name="name" id="userName" class="form-control" placeholder="Enter user name" required>
                    </div>
                    
                    {{-- Remarks --}}
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea name="description" id="remarks" class="form-control" rows="3" placeholder="Enter remarks"></textarea>
                    </div>

                    {{-- PDF Upload --}}
<div class="form-group">
    <label for="pdfUpload">Upload PO (PDF)</label>
    <div class="input-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" name="image_url" id="pdfUpload" accept="application/pdf">
            <label class="custom-file-label" for="pdfUpload">Choose PDF</label>
        </div>
        <div class="input-group-append">
            <span class="input-group-text">Upload</span>
        </div>
    </div>
</div>

<!-- Display selected file name -->
<div class="form-group">
    <p id="fileName" class="mt-2"></p>
</div>

                    <button type="submit" class="btn btn-success mt-4">Upload</button>
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
        const fileNameDisplay = document.getElementById('fileName');

        fileLabel.textContent = fileName;
        fileNameDisplay.textContent = `Selected file: ${fileName}`;
    });
</script>
@endsection
