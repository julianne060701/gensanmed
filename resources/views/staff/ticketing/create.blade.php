@extends('adminlte::page')

@section('title', 'Ticket Request')

@section('content_header')
<h1 class="ml-1">Ticket Request</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('staff.ticketing.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Ticket Number & Serial Number --}}
                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="ticket_number">Ticket Number</label>
                            <input type="text" name="ticket_number" class="form-control" placeholder="Enter Ticket Number"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="serial_number">Serial No.</label>
                            <input type="text" name="serial_number" class="form-control" placeholder="Enter Serial Number"
                                required>
                        </div>
                    </div>

                    {{-- Department & Responsible Department --}}
                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="department">Department</label>
                            <input type="text" name="department" class="form-control" placeholder="Enter Department"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="responsible_department">Responsible Department</label>
                            <select name="responsible_department" class="form-control" required>
                                <option value="">Select Responsible Department</option>
                                <option value="HIMS">HIMS</option>
                                <option value="Engineer">Engineer</option>
                            </select>
                        </div>
                    </div>

                    {{-- Concern Type & Urgency --}}
                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="concern_type">Concern Type</label>
                            <select name="concern_type" class="form-control" required>
                                <option value="">Select Concern Type</option>
                                <option value="repair">Repair</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="urgency">Urgency</label>
                            <select name="urgency" class="form-control" required>
                                <option value="">Select Urgency</option>
                                <option value="urgent">Urgent</option>
                                <option value="not urgent">Not Urgent</option>
                            </select>
                        </div>
                    </div>

                    {{-- Remarks --}}
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea name="remarks" id="remarks" class="form-control" rows="3"
                            placeholder="Enter remarks"></textarea>
                    </div>

                    {{-- Supporting Document Upload --}}
                    <div class="form-group">
                        <label for="imageUpload">Image</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image_url" id="imageUpload"
                                    accept="image/*">
                                <label class="custom-file-label" for="imageUpload">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <!-- Image Preview Section -->
                    <div class="form-group">
                        <p id="fileName" class="mt-2"></p>
                        <img id="imagePreview" src="#" alt="Image Preview" style="max-width: 200px; display: none;" />
                    </div>

                    <button type="submit" class="btn btn-success mt-4">Submit Request</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.getElementById('imageUpload').addEventListener('change', function (event) {
            const inputFile = event.target;
            const file = inputFile.files[0];
            const fileLabel = inputFile.closest('.custom-file').querySelector('.custom-file-label');
            const fileNameDisplay = document.getElementById('fileName');
            const imagePreview = document.getElementById('imagePreview');

            if (file) {
                fileLabel.textContent = file.name;
                fileNameDisplay.textContent = `Selected file: ${file.name}`;

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }
            } else {
                fileLabel.textContent = 'Choose file';
                fileNameDisplay.textContent = '';
                imagePreview.style.display = 'none';
            }
        });
    </script>
@endsection