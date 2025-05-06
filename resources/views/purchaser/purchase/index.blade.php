@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content_header')
<h1 class="ml-1">Purchase Order</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('purchaser.purchase.create') }}" class="btn btn-primary px-5">Upload PO</a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @php
                        $heads = [
                            'ID',
                            'PO #',
                            'Name',
                            'Remarks',
                            'Status',
                            'Attachment',
                            'My Attachment',
                            'Date Created',
                            'Total Duration',
                            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
                        ];

                        $config = [
                            'data' => $data,
                            'order' => [[7, 'desc']], // Sort by the 'Date Created' column (index 6) in descending order
                            'columns' => [null, null, null, null, null, null, null, null, null, ['orderable' => false]],
                        ];
                    @endphp

                    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" hoverable class="table-custom">
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

<!-- Confirmation Modal -->
{{-- modal delete --}}
<div class="modal danger" id="deleteModal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteForm" method="POST">
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
                    <h3>Are you sure you want to delete this PO?</h3>
                    <input type="hidden" name="id" id="deleteId">
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
document.addEventListener('DOMContentLoaded', function () {
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var purchaseId = button.data('delete');
        var purchaseName = button.data('name');

        var form = document.getElementById('deleteForm');
        form.action = "/purchaser/purchase/" + purchaseId;  // Adjust the route if needed
        document.getElementById('deleteId').value = purchaseId;
        document.querySelector('.modal-body h3').textContent = 'Are you sure you want to delete ' + purchaseName + '?';
    });
});
</script>
@endsection
