@extends('adminlte::page')

@section('title', 'Edit PO')

@section('content_header')
<h1 class="ml-1">Edit PO</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
            <form action="{{ route('admin.purchase.update', $purchase->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-row align-items-center mb-3">
                        <!-- Status Dropdown -->
                        <div class="col-md-4">
                            <label for="status">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Active" {{ $purchase->status === 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ $purchase->status === 'Inactive' ? 'selected' : '' }}>Inactive
                                </option>
                                <option value="Declined" {{ $purchase->status === 'Declined' ? 'selected' : '' }}>Declined
                                </option>
                            </select>


                        </div>



                        <!-- PO Number -->
                        <div class="col-md-4">
                            <label for="po_number">PO Number</label>
                            <input type="number" name="po_number" class="form-control" value="{{ $purchase->po_number }}"
                                min="1" readonly required>
                        </div>
                    </div>

                    <!-- Name of User -->
                    <div class="form-group">
                        <label for="userName">Name of User</label>
                        <input type="text" name="name" id="userName" class="form-control" value="{{ $purchase->name }}" readonly
                            required>
                    </div>

                    <!-- Remarks -->
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea name="description" id="remarks" class="form-control" readonly
                            rows="3">{{ $purchase->description }}</textarea>
                    </div>

                    <!-- Image Upload -->
                    <div class="form-group">
                        <label for="imageUpload">Image</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image_url" id="imageUpload"
                                    accept="image/*">
                                <label class="custom-file-label" for="imageUpload">Choose file</label>
                            </div>
                        </div>
                        <small id="fileName" class="text-muted mt-2">No file selected</small>
                    </div>

                    <!-- Existing Image Preview -->
                    @if($purchase->image_url)
                        <div class="form-group">
                            <p>Current Image:</p>
                            <img src="{{ asset('storage/' . $purchase->image_url) }}" id="imagePreview" alt="PO Image"
                                style="max-width: 200px;">
                        </div>
                    @endif

                    <button type="submit" class="btn btn-success mt-4">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.getElementById('imageUpload').addEventListener('change', function (event) {
            const inputFile = event.target;
            const fileName = inputFile.files[0]?.name || 'Choose file';
            const fileLabel = inputFile.nextElementSibling; // Label element
            const fileNameDisplay = document.getElementById('fileName');
            const imagePreview = document.getElementById('imagePreview');

            fileLabel.innerHTML = fileName;
            fileNameDisplay.textContent = `Selected file: ${fileName}`;

            const file = inputFile.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function (e) {
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