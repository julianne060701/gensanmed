@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
    <h1 class="ml-1">Upload PO</h1>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('purchaser.purchase.store') }}" method="POST" enctype="multipart/form-data" id="poForm">
                @csrf

                <!-- PO Number -->
                <div class="form-row align-items-center mb-3">
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

                <!-- Remarks -->
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="description" id="remarks" class="form-control" rows="3" placeholder="Enter remarks"></textarea>
                </div>

                <!-- PDF Upload -->
                <div class="form-group">
                    <label for="pdfUpload">Upload PO (PDF)</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="image_url" id="pdfUpload" accept="application/pdf" required>
                            <label class="custom-file-label" for="pdfUpload">Choose PDF</label>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text">Upload</span>
                        </div>
                    </div>
                </div>

                <!-- Display selected file name -->
                <div class="form-group">
                    <p id="fileName" class="mt-2 text-muted"></p>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success mt-4" id="uploadBtn">
                    <span id="btnText">Upload</span>
                    <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const fileInput = document.getElementById('pdfUpload');
    const fileNameDisplay = document.getElementById('fileName');
    const fileLabel = document.querySelector('.custom-file-label');

    // Show selected file name & validate extension
    fileInput.addEventListener('change', function () {
        const file = fileInput.files[0];

        if (file) {
            const fileName = file.name;
            const fileExtension = fileName.split('.').pop().toLowerCase();

            if (fileExtension !== 'pdf') {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Only PDF files are allowed!',
                });
                fileInput.value = '';
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

    // Confirmation on form submit
    document.getElementById('poForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Stop regular form submission

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to submit this PO?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show spinner and disable button
                const btn = document.getElementById('uploadBtn');
                document.getElementById('btnText').textContent = 'Uploading...';
                document.getElementById('btnSpinner').classList.remove('d-none');
                btn.disabled = true;

                e.target.submit(); // Submit the form
            }
        });
    });
</script>
@endsection
