@extends('adminlte::page')

@section('title', 'Create User')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">

@section('content_header')
    <h1 class="ml-1">Create User</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form id="createUserForm" action="{{ route('admin.user.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
                        @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
                        @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        @error('password')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        @error('password_confirmation')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="role">User Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="Administrator" {{ old('role') == 'Administrator' ? 'selected' : '' }}>Administrator</option>
                            <option value="HIMS" {{ old('role') == 'HIMS' ? 'selected' : '' }}>HIMS</option>
                            <option value="Purchaser" {{ old('role') == 'Purchaser' ? 'selected' : '' }}>Purchaser</option>
                            <option value="Engineer" {{ old('role') == 'Engineer' ? 'selected' : '' }}>Engineer</option>
                            <option value="Staff" {{ old('role') == 'Staff' ? 'selected' : '' }}>Staff</option>
                            <option value="Employee" {{ old('role') == 'Employee' ? 'selected' : '' }}>Employee</option>
                            <option value="Head" {{ old('role') == 'Head' ? 'selected' : '' }}>Head</option> 
                            <option value="mmo" {{ old('role') == 'mmo' ? 'selected' : ''}}>MMO</option>
                        </select>
                        @error('role')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="button" id="createUserBtn" class="btn btn-success">Create User</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Add SweetAlert confirmation for form submission
    $('#createUserBtn').on('click', function(e) {
        e.preventDefault(); // Prevent immediate form submission

        // Trigger SweetAlert for confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to create this user?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, create it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if user confirms
                $('#createUserForm').submit();
            }
        });
    });
</script>
@endsection
