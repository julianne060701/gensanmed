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
                        <!-- Status -->
                        <!-- <div class="col-md-4">
                            <label for="status">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="0" class="text-danger">Unavailable</option>
                                <option value="1" class="text-success" selected>Available</option>
                            </select>
                        </div> -->
                        
                        {{-- PO Number --}}
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

                    {{-- Image Upload --}}
                    <div class="form-group">
                        <label for="imageUpload"> Image</label>
                        <div class="input-group">
                            <div class="custom-file">
                            <input type="file" class="custom-file-input" name="image_url" id="imageUpload" accept="image/*">

                                <label class="custom-file-label" for="imageUpload">Choose file</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">Upload</span>
                            </div>
                        </div>
                    </div>

                    <!-- Image Preview Section -->
                    <div class="form-group">
                        <p id="fileName" class="mt-2"></p>
                        <img id="imagePreview" src="#" alt="Image Preview" style="max-width: 200px; display: none;" />
                    </div>

                    <button type="submit" class="btn btn-success mt-4">Upload</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    document.getElementById('imageUpload').addEventListener('change', function(event) {
        const inputFile = event.target;
        const fileName = inputFile.files[0]?.name || 'Choose file';
        const fileLabel = inputFile.nextElementSibling; // Label element
        const fileNameDisplay = document.getElementById('fileName');
        const imagePreview = document.getElementById('imagePreview');

        fileLabel.textContent = fileName;
        fileNameDisplay.textContent = `Selected file: ${fileName}`;

        const file = inputFile.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };

            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    });
</script>
@endsection
