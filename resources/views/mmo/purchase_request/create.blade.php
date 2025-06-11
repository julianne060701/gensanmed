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
                <form action="{{ route('mmo.purchase_request.store') }}" method="POST" enctype="multipart/form-data">
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
                        <label for="requester_name">Name of Requester and Department</label>
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

                    <button type="button" id="submitBtn" class="btn btn-success mt-4">Submit PR</button>

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

            fileLabel.textContent = fileName;
            fileNameDisplay.textContent = `Selected file: ${fileName}`;
        } else {
            fileLabel.textContent = 'Choose PDF';
            fileNameDisplay.textContent = '';
        }
    });

    // Handle submit with confirmation
    document.getElementById('submitBtn').addEventListener('click', function () {
        const fileInput = document.getElementById('pdfUpload');
        const file = fileInput.files[0];

        // Optional: check again before submission
        if (file && file.type !== "application/pdf") {
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
                fileInput.closest('form').submit();
            }
        });
    });
</script>
@endsection
