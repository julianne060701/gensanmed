@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content_header')
<h1 class="ml-1">Users</h1>
@stop

@section('content')

<div class="container-fluid">

    <!-- Button to Create New User -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.user.create') }}" class="btn btn-primary px-5">Create User</a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    @php
                        // Columns for the DataTable
                        $heads = [
                            'ID',
                            'User Name',
                            'Email',    
                            'Role',                       
                            'Date Created',
                            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
                        ];

                        // Configuration for the DataTable
                        $config = [
                            'data' => $data,  // $data should contain user data
                            'order' => [[1, 'desc']],
                            'columns' => [
                                null, // ID column
                                null, // User Name column
                                null, // Email column
                                null, // Role column
                                null, // Date Created column
                                ['orderable' => false] // Actions column (not orderable)
                            ],
                        ];
                    @endphp

                    <!-- Display DataTable with User Data -->
                    <x-adminlte-datatable id="table1" :heads="$heads" hoverable class="table-custom">
                        @foreach ($config['data'] as $row)
                            <tr>
                                @foreach ($row as $cell)
                                    <td>{!! $cell !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal for Deleting User --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteUserForm" action="" method="post">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Delete</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>Are you sure you want to delete <span id="userNameDisplay"></span>?</h3>
                    <input type="hidden" name="deleteId" id="deleteId">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

@stop

@section('js')

<script>
    // Handle delete button click to pass the user ID and name to the modal form
    $(document).on('click', '.Delete', function () {
        const userId = $(this).data('delete');
        const userName = $(this).data('name');
        $('#deleteId').val(userId);
        $('#userNameDisplay').text(userName);
    });
</script>

@endsection
