@extends('adminlte::page')

@section('title', 'Create User')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">

@section('content_header')
    <h1 class="ml-1">Add User SMS</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.schedule.store_sms') }}" method="POST">
                    @csrf

                    <!-- Name Input -->
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required 
                               value="{{ old('name') }}" placeholder="Enter full name">
                        @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone Input -->
                    <div class="form-group">
                        <label for="phone">Phone Number <small>(Format: +639123456789)</small></label>
                        <input type="text" name="phone" id="phone" class="form-control" required 
                               pattern="^\+?[1-9]\d{1,14}$"
                               title="Phone number must be in E.164 format (e.g., +639123456789)" 
                               value="{{ old('phone') }}" placeholder="+639123456789">
                        @error('phone')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success">Add User</button>
                </form>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('admin.schedule.create_group') }}">

    @csrf
    <label>Group Name:</label>
    <input type="text" name="name" class="form-control" required>

    <label>Assign Users:</label>
    @foreach($users as $user)
    <div>
        <input type="checkbox" name="users[]" value="{{ $user->id }}">
        {{ $user->name }} ({{ $user->phone }})
    </div>
@endforeach


    <button class="btn btn-success mt-3">Create Group</button>
</form>

    <!-- JavaScript for Phone Number Formatting -->
    <script>
    document.getElementById("phone").addEventListener("blur", function() {
        let phoneInput = this.value.trim();

        // Auto-convert "09123456789" → "+639123456789"
        if (phoneInput.startsWith("0")) {
            phoneInput = "+63" + phoneInput.substring(1);
        }

        this.value = phoneInput; 
    });
    </script>
@endsection