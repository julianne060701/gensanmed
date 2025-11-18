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
        <a href="{{ route('pharmpurch.purchase.create') }}" class="btn btn-primary px-5">Upload PO</a>
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
                            'Admin Attachment',
                            'Date Created',
                            'Total Duration',
                            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
                        ];

                        $config = [
                            'data' => $data,
                            'order' => [[7, 'desc']], // Sort by the 'Date Created' column in descending order
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
@stop

@section('js')
<script>
    // Any additional JavaScript if needed
</script>
@endsection

