@extends('adminlte::page')

@section('title', 'Dashboard')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
@section('content_header')
    <h1 class="ml-1">Upload PR</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('head.purchase_request.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-row align-items-center mb-3">
                        
                         <!-- PR Number  -->
                        <div class="col-md-4">
                            <label for="request_number">PR Number</label>
                            <input type="number" name="request_number" class="form-control" placeholder="Enter PR Number" min="1" required>
                        </div>
                    </div>

                    <!-- Name of Requester -->
                    <div class="form-group">
                        <label for="requester_name">Name of Requester</label>
                        <input type="text" name="requester_name" id="requester_name" class="form-control" placeholder="Enter requester name" required>
                    </div>
                    

                    {{-- Description --}}
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter description"></textarea>
                    </div>

                    {{-- PDF Upload --}}
                    <div class="form-group">
                        <label for="pdfUpload">Upload PR (PDF)</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="attachment_url" id="pdfUpload" accept="application/pdf">
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

                    <button type="submit" class="btn btn-success mt-4">Submit PR</button>
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
