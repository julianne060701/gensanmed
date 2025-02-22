@extends('adminlte::page')

@section('title', 'Ticket Request')

@section('content_header')
    <h1 class="ml-1">Ticket Request</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.ticketing.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-row align-items-center mb-3">
                        {{-- Ticket Number --}}
                        <div class="col-md-4">
                            <label for="ticket_number">Ticket Number</label>
                            <input type="text" name="ticket_number" class="form-control" placeholder="Enter Ticket Number" required>
                        </div>
                    </div>

                    {{-- Department --}}
                    <div class="form-group">
                        <label for="department">Department</label>
                        <input type="text" name="department" class="form-control" placeholder="Enter Department" required>
                    </div>

                    {{-- Responsible Department --}}
                    <div class="form-group">
                        <label for="responsible_department">Responsible Department</label>
                        <select name="responsible_department" class="form-control" required>
                            <option value="">Select Responsible Department</option>
                            <option value="HIMS">HIMS</option>
                            <option value="Engineer">Engineer</option>
                        </select>
                    </div>
                    
                    {{-- Concern Type --}}
                    <div class="form-group">
                        <label for="concern_type">Concern Type</label>
                        <select name="concern_type" class="form-control" required>
                            <option value="repair">Repair</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="medsys">Medsys</option>
                        </select>
                    </div>
                    
                    {{-- Urgency --}}
                    <div class="form-group">
                        <label for="urgency">Urgency</label>
                        <select name="urgency" class="form-control" required>
                            <option value="">Select Urgency</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>

                    {{-- Serial Number --}}
                    <div class="form-group">
                        <label for="serial_number">Serial No.</label>
                        <input type="text" name="serial_number" class="form-control" placeholder="Enter Serial Number" required>
                    </div>

                    {{-- Remarks --}}
                    <div class="form-group">
                        <label for="remarks">Remarks (Input anydesk number if the concern is medsys)</label>
                        <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter remarks"></textarea>
                    </div>

                    {{-- Supporting Document Upload --}}
                    <div class="form-group">
                        <label for="imageUpload">Image</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image_url" id="imageUpload" accept="image/*">
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
    document.getElementById('imageUpload').addEventListener('change', function(event) {
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
                reader.onload = function(e) {
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
