@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content_header')
<h1 class="ml-1">Purchaser</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('purchaser.purchase.create') }}" class="btn btn-primary px-5">Add Purchaser</a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @php
                        $heads = [
                            'ID',
                            'Name',
                            'Contact Number',
                            'Total Purchases',
                            'Status',
                            'Date Created',
                            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
                        ];

                        $data = [
                            [1, 'John Doe', '+63 9123456789', '5 purchases', 'Active', '2025-02-10', '<button class="btn btn-danger Delete" data-delete="1">Delete</button>'],
                            [2, 'Jane Smith', '+63 9876543210', '10 purchases', 'Inactive', '2025-01-15', '<button class="btn btn-danger Delete" data-delete="2">Delete</button>'],
                            [3, 'Michael Lee', '+63 9129876543', '3 purchases', 'Active', '2024-12-20', '<button class="btn btn-danger Delete" data-delete="3">Delete</button>'],
                        ];
                    @endphp

                    <x-adminlte-datatable id="table1" :heads="$heads" hoverable>
                        @foreach ($data as $row)
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

{{-- Modal delete --}}
<div class="modal fade" id="deleteModalBed" tabindex="-1" role="dialog" aria-labelledby="deleteModalBed" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteBed" action="#" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Delete</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
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
        const deleteValue = $(this).data('delete');
        $('#deleteId').val(deleteValue);
    });
</script>
@stop
