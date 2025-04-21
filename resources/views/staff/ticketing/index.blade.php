@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
@section('content_header')
    <h1 class="ml-1">Ticket</h1>
@stop

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('staff.ticketing.create') }}" class="btn btn-primary px-5">Make Request</a>
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
                            'Urgency',                                      
                            'Image',
                            'Status',
                            'Date Request',
                            'Total Duration',
                            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
                        ];

                        $config = [
                            'order' => [[7, 'desc']], // Sort by Date Request (column index 6)
                            'columns' => [
                                null, // Ticket #
                                null, // Department
                                null, // Responsible Department
                                null, // Concern Type
                                null, // Urgency
                                ['orderable' => false], // Image (disable sorting)
                                null, // Status
                                null, // Date Request (Ensure this is sortable)
                                null,
                                ['orderable' => false], // Actions (disable sorting)
                            ],
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
@if(isset($tickets))
    <div class="ticket-container">
        <div class="ticket-header">
            <h2>Ticket Details</h2>
        </div>
        <div class="ticket-details">
            <p><strong>Ticket Number:</strong> {{ $ticket->ticket_number }}</p>
            <p><strong>Department:</strong> {{ $ticket->department }}</p>
            <p><strong>Responsible Department:</strong> {{ $ticket->responsible_department }}</p>
            <p><strong>Concern Type:</strong> {{ $ticket->concern_type }}</p>
            <p><strong>Urgency:</strong> {{ $ticket->urgency }}</p>
            <p><strong>Remarks:</strong> {{ $ticket->remarks }}</p>
            <p><strong>Approved Date:</strong> {{ $ticket->approval_date ? \Carbon\Carbon::parse($ticket->approval_date)->format('F j, Y') : 'N/A' }}</p>
            <p><strong>Completed By:</strong> {{ $ticket->completed_by ?? 'N/A' }}</p>
            <p><strong>Denied Remarks By Admin:</strong> {{$ticket->remarks_by ?? 'N/A'}}</p>
            <p><strong>Defective Remarks:</strong> {{$ticket->responsible_remarks ?? 'N/A'}}</p>

        </div>
    </div>
@endif

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

<!-- Modal -->
<div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="ticketModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ticketModalLabel">Ticket Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <p><strong>Ticket Number:</strong> <span id= "modalTicketNumber"></span></p>
    <p><strong>Department:</strong> <span id= "modalDepartment"></span></p>
    <p><strong>Responsible Department:</strong> <span id= "modalResponsibleDepartment"></span></p>
    <p><strong>Concern Type:</strong> <span id= "modalConcernType"></span></p>
    <p><strong>Equipment:</strong> <span id= "modalEquipment"></span></p>
    <p><strong>Urgency:</strong> <span id= "modalUrgency"></span></p>
    <p><strong>Remarks:</strong> <span id="modalRemarks"></span></p>
    <p><strong>Approved Date by Hopss:</strong> <span id= "modalApprovedDate"></span></p>
    <p><strong>Complete By:</strong> <span id="modalCompletedBy"></span></p>
    <p><strong>Denied Remarks:</strong> <span id="modalDeniedRemarks"></span></p>
    <p><strong>Defective Remarks:</strong> <span id="modalDefectiveRemarks"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@stop

@section('js')
    <script>
        $(document).on('click', '.Delete', function () {
            const deleteValue = $(this).data('delete');
            $('#deleteId').val(deleteValue);
        });
        $(document).on('click', '.view-ticket', function() {
    var ticketId = $(this).data('id');

    $.get('/tickets/' + ticketId, function(data) {
        if (data) {
            $('#modalTicketNumber').text(data.ticket_number);
            $('#modalDepartment').text(data.department);
            $('#modalResponsibleDepartment').text(data.responsible_department);
            $('#modalConcernType').text(data.concern_type);
            $("#modalEquipment").text(data.equipment || 'N/A'); 
            $('#modalUrgency').text(data.urgency);
            $('#modalRemarks').text(data.remarks);
            $('#modalCompletedBy').text(data.completed_by || 'N/A');
            $('#modalDeniedRemarks').text(data.remarks_by || 'N/A');
            $('#modalDefectiveRemarks').text(data.responsible_remarks || 'N/A');

            // Format the approved date
            if (data.approval_date) {
                var approvalDate = new Date(data.approval_date);
                var formattedDate = approvalDate.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                $("#modalApprovedDate").text(formattedDate);
            } else {
                $("#modalApprovedDate").text('N/A'); // Fix: Correct selector ID
            }

            // Show the modal
            $('#ticketModal').modal('show');
        } else {
            alert('Ticket details not found!');
        }
    }).fail(function() {
        alert('Error fetching ticket details.');
    });
});
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.print-ticket').forEach(button => {
        button.addEventListener('click', function () {
            let ticketId = this.getAttribute('data-id');
            let printUrl = `/staff/ticketing/print/${ticketId}`;
            let printWindow = window.open(printUrl, '_blank');
            if (printWindow) {
                printWindow.focus();
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.print-ticket').forEach(button => {
        button.addEventListener('click', function () {
            let ticketId = this.getAttribute('data-id');
            let printUrl = `/staff/ticketing/print/${ticketId}`;
            let printWindow = window.open(printUrl, '_blank');
            if (printWindow) {
                printWindow.focus();
            }
        });
    });
});

    </script>
@endsection
