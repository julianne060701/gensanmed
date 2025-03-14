@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
@section('content_header')
<h1 class="ml-1">Ticketing</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    @php
                        $heads = [
                            'Ticket #',
                            'Department',  
                            'Responsible Department',                        
                            'Concern Type',                                                  
                            'Image',
                            'Status',
                            'Date Request',
                            'Total Duration',
                            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
                        ];

                        $config = [
                            'order' => [[6, 'desc']],
                            'columns' => [
                                null, null, null, null,
                                ['orderable' => false],
                                null, null, null,
                                ['orderable' => false],
                            ],
                        ];

                        $data = [
                            ['TKT001', 'IT', 'Support', 'Network Issue', '<img src="image1.jpg" width="50">', 'Pending', '2025-03-10', '2 days', '<button class="btn btn-primary btn-sm">View</button>'],
                            ['TKT002', 'HR', 'Maintenance', 'System Error', '<img src="image2.jpg" width="50">', 'Resolved', '2025-03-08', '5 days', '<button class="btn btn-primary btn-sm">View</button>'],
                            ['TKT003', 'Finance', 'Support', 'Login Issue', '<img src="image3.jpg" width="50">', 'In Progress', '2025-03-12', '1 day', '<button class="btn btn-primary btn-sm">View</button>'],
                        ];
                    @endphp

                    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" hoverable class="table-custom">
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
@stop
