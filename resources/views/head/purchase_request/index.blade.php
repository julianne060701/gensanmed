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
        <a href="{{ route('head.purchase_request.create') }}" class="btn btn-primary px-5">Upload PR</a>
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
                            'Description',
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
<!-- Purchase Modal -->
<div class="modal fade" id="purchaseModal" tabindex="-1" role="dialog" aria-labelledby="purchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="purchaseModalLabel">Purchase Request Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Purchase request details will be loaded here dynamically -->
                <p><strong>Request Number:</strong> <span id="modalRequestNumber"></span></p>
                <p><strong>Requester Name:</strong> <span id="modalRequesterName"></span></p>
                <p><strong>Description:</strong> <span id="modalDescription"></span></p>
                <p><strong>Remarks From Admin:</strong> <span id="modalRemarks"></span></p>
                <p><strong>Approved Date:</strong> <span id="modalApprovedDate"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

$(document).on('click', '.view-purchase', function() {
        var purchaseId = $(this).data('id');
        
        // Make an AJAX request to fetch the purchase request details
        $.get('/purchase_requests/' + purchaseId, function(data) {
            // Populate the modal with the fetched data
            $('#modalRequestNumber').text(data.request_number);
            $('#modalRequesterName').text(data.requester_name);
            $('#modalDescription').text(data.description);
            $('#modalRemarks').text(data.remarks);

            // Format the date
            var updatedAt = new Date(data.updated_at);
            var options = { year: 'numeric', month: 'long', day: 'numeric' };
            var formattedDate = updatedAt.toLocaleDateString('en-US', options);
            $('#modalApprovedDate').text(formattedDate);
        });
    });
 
</script>
@endsection
