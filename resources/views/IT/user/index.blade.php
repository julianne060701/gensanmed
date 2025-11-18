@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">

@section('content_header')
    <h1 class="ml-1">Users</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('IT.user.create') }}" class="btn btn-primary px-5">Create User</a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @php
                        $heads = [
                            'ID',
                            'User Name',
                            'Email',
                            'Role',
                            'Date Created',
                            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
                        ];
                    @endphp

                    <x-adminlte-datatable id="table1" :heads="$heads" hoverable class="table-custom">
                        @foreach ($data as $row)
                            <tr>
                                <td>{{ $row[0] }}</td>
                                <td>{{ $row[1] }}</td>
                                <td>{{ $row[2] }}</td>
                                <td>{{ $row[3] }}</td>
                                <td>{{ $row[4] }}</td>
                                <td>
                                    <nobr>
                                        <button
                                            class="btn btn-xs btn-default text-primary mx-1 shadow Edit"
                                            title="Edit"
                                            data-toggle="modal"
                                            data-target="#editModal"
                                            data-id="{{ $row[0] }}"
                                            data-name="{{ $row[1] }}"
                                            data-email="{{ $row[2] }}"
                                            data-role="{{ $row[3] }}"
                                        >
                                            <i class="fa fa-lg fa-fw fa-pen"></i>
                                        </button>
                                        <button
                                            class="btn btn-xs btn-default text-danger mx-1 shadow Delete"
                                            title="Delete"
                                            data-toggle="modal"
                                            data-target="#deleteModal"
                                            data-delete="{{ $row[0] }}"
                                            data-name="{{ $row[1] }}"
                                        >
                                            <i class="fa fa-lg fa-fw fa-trash"></i>
                                        </button>
                                    </nobr>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editUserForm" method="post">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h4 class="modal-title">Edit User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editUserName">User Name</label>
                        <input type="text" class="form-control" id="editUserName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="editRole">Role</label>
                        <select class="form-control" id="editRole" name="role" required>
                            <option value="Administrator">Administrator</option>
                            <option value="IT">IT</option>
                            <option value="Purchaser">Purchaser</option>
                            <option value="Engineer">Engineer</option>
                            <option value="Staff">Staff</option>
                            <option value="Employee">Employee</option>
                            <option value="Head">Head</option>
                            <option value="mmo">MMO</option>
                            <option value="PharmPurch">Pharmacy Purchaser</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editPassword">Reset Password (optional)</label>
                        <input type="password" class="form-control" id="editPassword" name="password" placeholder="Leave blank to keep current password">
                    </div>
                    <input type="hidden" name="userId" id="editUserId">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // SweetAlert for session messages
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    // Delete user confirmation
    $(document).on('click', '.Delete', function (e) {
    e.preventDefault();
    const userId = $(this).data('delete');
    const userName = $(this).data('name');

    Swal.fire({
        title: `Delete ${userName}?`,
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit form dynamically
            const form = $('<form>', {
                method: 'POST', // Send POST request
                action: `/IT/user/${userId}/delete` // Ensure it targets the correct route
            });

            const token = $('meta[name="csrf-token"]').attr('content');
            const hiddenMethod = $('<input>', {
                type: 'hidden',
                name: '_method',
                value: 'DELETE' // Set the method to DELETE
            });

            const csrf = $('<input>', {
                type: 'hidden',
                name: '_token',
                value: token // Add the CSRF token
            });

            form.append(hiddenMethod, csrf).appendTo('body').submit(); // Submit form
        }
    });
});


    // Populate Edit Modal
    $(document).on('click', '.Edit', function () {
        const userId = $(this).data('id');
        const userName = $(this).data('name');
        const userEmail = $(this).data('email');
        const userRole = $(this).data('role');

        $('#editUserId').val(userId);
        $('#editUserName').val(userName);
        $('#editEmail').val(userEmail);
        $('#editRole').val(userRole);
        $('#editUserForm').attr('action', `/IT/user/${userId}/update`);
    });

    // Confirm before submitting Edit form
    $('#editUserForm').on('submit', function (e) {
        e.preventDefault(); // stop immediate submission

        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure you want to change this user's details?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, save changes!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit(); // submit form if confirmed
            }
        });
    });
</script>
@endsection
