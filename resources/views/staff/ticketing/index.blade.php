@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content_header')
<h1 class="ml-1">Ticket</h1>
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
                            'Ticket #',
                            'Department',  
                            'Responsible Department',                        
                            'Concern Type',                                                  
                            'Image',
                            'Status',
                            'Date Request',
                            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
                        ];

                        $config = [
                            'data' => $data,
                            'order' => [[0, 'desc']],
                            'columns' => [null, null, null, ['orderable' => false]],
                        ];
                    @endphp

                    <x-adminlte-datatable id="table1" :heads="$heads" hoverable>
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

{{-- modal delete --}}
<div class="modal fade" id="deleteModalTicket" tabindex="-1" role="dialog" aria-labelledby="deleteModalTicket" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteTicket" action="" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Delete</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>Are you sure you want to delete <span id="ticketNameDisplay"></span>?</h3>
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
            const deleteValue = $(this).data('delete');
            $('#deleteId').val(deleteValue);
        });
    </script>
@endsection
