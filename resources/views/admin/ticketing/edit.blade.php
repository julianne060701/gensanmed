@extends('layouts.app')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
@section('content')
<div class="container">
    <h2 class="mb-4">Edit Ticket</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('staff.ticketing.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Ticket Number (Read-only) -->
        <div class="mb-3">
            <label for="ticket_number" class="form-label">Ticket Number</label>
            <input type="text" class="form-control" id="ticket_number" name="ticket_number" value="{{ $ticket->ticket_number }}" readonly>
        </div>

        <!-- Department -->
        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <input type="text" class="form-control" id="department" name="department" value="{{ old('department', $ticket->department) }}" required>
        </div>

        <!-- Responsible Department -->
        <div class="mb-3">
            <label for="responsible_department" class="form-label">Responsible Department</label>
            <input type="text" class="form-control" id="responsible_department" name="responsible_department" value="{{ old('responsible_department', $ticket->responsible_department) }}" required>
        </div>

        <!-- Concern Type -->
        <div class="mb-3">
            <label for="concern_type" class="form-label">Concern Type</label>
            <input type="text" class="form-control" id="concern_type" name="concern_type" value="{{ old('concern_type', $ticket->concern_type) }}" required>
        </div>

        <!-- Urgency -->
        <div class="mb-3">
            <label for="urgency" class="form-label">Urgency (1 - Low, 5 - High)</label>
            <input type="number" class="form-control" id="urgency" name="urgency" value="{{ old('urgency', $ticket->urgency) }}" min="1" max="5" required>
        </div>

        <!-- Serial Number -->
        <div class="mb-3">
            <label for="serial_number" class="form-label">Serial Number</label>
            <input type="text" class="form-control" id="serial_number" name="serial_number" value="{{ old('serial_number', $ticket->serial_number) }}" required>
        </div>

        <!-- Remarks -->
        <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="remarks" name="remarks">{{ old('remarks', $ticket->remarks) }}</textarea>
        </div>

        <!-- Current Image Preview -->
        @if($ticket->image_url)
        <div class="mb-3">
            <label class="form-label">Current Image</label>
            <br>
            <img src="{{ asset($ticket->image_url) }}" alt="Ticket Image" class="img-thumbnail" width="200">
        </div>
        @endif

        <!-- Upload New Image -->
        <div class="mb-3">
            <label for="image_url" class="form-label">Upload New Image (Optional)</label>
            <input type="file" class="form-control" id="image_url" name="image_url" accept="image/*">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Ticket</button>
        <a href="{{ route('staff.ticketing.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
