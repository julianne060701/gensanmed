@extends('adminlte::page')

@section('title', 'PMS Aircon Schedule')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
@section('content_header')
    <h1 class="ml-1">PMS Aircon Schedule Form</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action=" " method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Schedule Number -->
                    <div class="form-row align-items-center mb-3">
                        <div class="col-md-4">
                            <label for="schedule_number">Schedule Number</label>
                            <input type="number" name="schedule_number" class="form-control" placeholder="Enter Schedule Number" min="1" required>
                        </div>
                    </div>

                    <!-- Department -->
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select name="department" id="department" class="form-control" required>
                            <option value="" disabled selected>Select Department</option>
                            <option value="Engineering">Engineering</option>
                            <option value="HR">Human Resources</option>
                            <option value="IT">Information Technology</option>
                            <option value="Finance">Finance</option>
                            <option value="Maintenance">Maintenance</option>
                            <!-- Add other departments as needed -->
                        </select>
                    </div>

                    <!-- Requested By -->
                    <div class="form-group">
                        <label for="requester_name">Requested By</label>
                        <input type="text" name="requester_name" id="requester_name" class="form-control" placeholder="Enter requester's name" required>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Work Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter details of PMS work"></textarea>
                    </div>

                    

                    <!-- Display selected file name -->
                    <div class="form-group">
                        <p id="fileName" class="mt-2"></p>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Submit Schedule</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>

</script>
@endsection
