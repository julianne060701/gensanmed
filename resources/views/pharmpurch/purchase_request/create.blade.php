@extends('adminlte::page')

@section('title', 'Dashboard')
@section('content_header')
    <h1 class="ml-1">Upload PR</h1>
@stop

@push('meta')
    <link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
@endpush

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('pharmpurch.purchase_request.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                <!-- PR Number -->
                <div class="form-row align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="request_number">PR Number</label>
                        <input type="text" name="request_number" class="form-control" placeholder="Enter PR Number" required>
                    </div>
                </div>

                <!-- Name of Requester -->
                <div class="form-group">
                    <label for="requester_name">Name of Requester and Department</label>
                    <input type="text" name="requester_name" id="requester_name" class="form-control" placeholder="Enter requester name and department" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter description"></textarea>
                </div>

                <!-- PDF Upload -->
                <div class="form-group">
                    <label for="pdfUpload">Upload PR (PDF)</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="attachment_url" id="pdfUpload" accept="application/pdf" required>
                            <label class="custom-file-label" for="pdfUpload">Choose PDF</label>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text">Upload</span>
                        </div>
                    </div>
                    <small class="form-text text-muted">Maximum file size: 20MB. Only PDF files are allowed.</small>
                </div>

                <!-- Display selected file name -->
                <div class="form-group">
                    <p id="fileName" class="mt-2 text-muted"></p>
                </div>

                <button type="submit" class="btn btn-success mt-4" id="submitBtn">Submit PR</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // PDF validation and filename display
  document.getElementById('pdfUpload').addEventListener('change', function(event) {
        const inputFile = event.target;
        const file = inputFile.files[0];
        const fileNameDisplay = document.getElementById('fileName');
        const fileLabel = inputFile.nextElementSibling; // Label

        if (file) {
            const fileName = file.name;
            const fileExtension = fileName.split('.').pop().toLowerCase();

            if (fileExtension !== 'pdf') {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Only PDF files are allowed!',
                });

                inputFile.value = ''; // Clear file input
                fileLabel.textContent = 'Choose PDF';
                fileNameDisplay.textContent = '';
                return;
            }

            // Check file size (20MB = 20971520 bytes)
            if (file.size > 20971520) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'File size must be less than 20MB!',
                });

                inputFile.value = ''; // Clear file input
                fileLabel.textContent = 'Choose PDF';
                fileNameDisplay.textContent = '';
                return;
            }

            fileLabel.textContent = fileName;
            fileNameDisplay.textContent = `Selected file: ${fileName} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        } else {
            fileLabel.textContent = 'Choose PDF';
            fileNameDisplay.textContent = '';
        }
    });

    // Handle submit with confirmation
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const fileInput = document.getElementById('pdfUpload');
        const file = fileInput.files[0];

        // Check if file is selected
        if (!file) {
            Swal.fire({
                icon: 'error',
                title: 'No File Selected',
                text: 'Please select a PDF file to upload!',
            });
            return;
        }

        // Optional: check again before submission
        if (file.type !== "application/pdf") {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File',
                text: 'Only PDF files are allowed!',
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to submit this Purchase Request?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
</script>
@endsection

