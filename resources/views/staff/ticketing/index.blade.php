@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content_header')
<h1 class="ml-1">Tickets</h1>
@stop

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('staff.ticketing.create') }}" class="btn btn-primary px-5">Create Ticket</a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @php
                        $heads = [
                            'ID',
                            'Ticket Number',
                            'Department',
                            'Responsible Department',
                            'Concern Type',
                            'Urgency',
                            'Serial Number',
                            'Remarks',
                            'Status',
                            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
                        ];
                    @endphp

                    <x-adminlte-datatable id="table1" :heads="$heads" hoverable>
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->ticket_number }}</td>
                                <td>{{ $ticket->department }}</td>
                                <td>{{ $ticket->responsible_department }}</td>
                                <td>{{ $ticket->concern_type }}</td>
                                <td>{{ $ticket->urgency }}</td>
                                <td>{{ $ticket->serial_number }}</td>
                                <td>{{ $ticket->remarks }}</td>
                                <td>{{ $ticket->status }}</td>
                                <td>
                                    <a href="{{ route('staff.tickets.edit', $ticket->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <button class="btn btn-sm btn-danger Delete" data-delete="{{ $ticket->id }}" data-name="{{ $ticket->ticket_number }}" data-toggle="modal" data-target="#deleteModal">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
            </div>
        </div>
    </div>
</div>

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
    $(document).on('click', '.Delete', function () {
        const ticketId = $(this).data('delete');
        const ticketNumber = $(this).data('name');
        $('#deleteId').val(ticketId);
        $('#userNameDisplay').text(ticketNumber);
    });
</script>

@endsection
