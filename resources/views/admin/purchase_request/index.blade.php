@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content_header')
<h1 class="ml-1">Purchase Request</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <!-- <a href="{{ route('head.purchase_request.create') }}" class="btn btn-primary px-5">Upload PR</a> -->
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @php
                        $heads = [
                            'ID',
                            'PR #',
                            'PO #', // Add PO # column header
                            'Name',
                            'Remarks',
                            'Status',
                            'Image',
                            'Date Created',
                            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
                        ];

                        $config = [
                            'data' => $data,
                            'order' => [[7, 'desc']], // Sort by the 'Date Created' column (index 7) in descending order
                            'columns' => [null, null, null, null, null, null, null, null, ['orderable' => false]],
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).on('click', '.Accept', function () {
        var id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to accept this purchase request.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Accept',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('purchase.accept') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Accepted!',
                            text: 'The purchase request has been accepted.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Reload page after action
                        });
                    },
                    error: function(response) {
                        Swal.fire('Error', 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });
</script>
@endsection

