@extends('adminlte::page')

@section('title', 'Dashboard')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
@section('css')
<style>
/* Modal Styling */
.modal-content {
    animation: spinIn 0.5s ease-out;
}

.modal-header {
    background: #007bff;
    color: white;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    padding: 15px;
    text-align: center;
}

.modal-body {
    padding: 20px;
    font-size: 16px;
}

/* Buttons */
.modal-footer .btn {
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
}

.btn-danger {
    background: #dc3545;
    border: none;
}

.btn-danger:hover {
    background: #c82333;
}
.btn-primary:hover {
    animation: spin 0.5s linear;
}
/* Spin-in Animation for Modal */
@keyframes spinIn {
    from {
        opacity: 0;
        transform: rotate(-360deg) scale(0.5);
    }
    to {
        opacity: 1;
        transform: rotate(0) scale(1);
    }
}
/* Spin on Hover for Button */
@keyframes spin {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}
/* Fade-in and Scale Animation */
@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Shake Animation for Errors */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
    75% { transform: translateX(-5px); }
}

.shake {
    animation: shake 0.3s ease-in-out;
}
</style>
@endsection
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content_header')
    <h1 class="ml-1">Ticket</h1>
@stop

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.ticketing.create') }}" class="btn btn-primary px-5">Create Ticket</a>
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
                            'order' => [[0, 'desc']], // Sort by Date Request (column index 6)
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

{{-- Ticket Details Modal --}}
<div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ticketModalLabel">Ticket Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Ticket Number:</strong> <span id="ticketNumber"></span></p>
                <p><strong>Serial Number:</strong> <span id="ticketSerial"></span></p>
                <p><strong>Department:</strong> <span id="ticketDepartment"></span></p>
                <p><strong>Responsible Department:</strong> <span id="ticketResponsibleDept"></span></p>
                <p><strong>Concern Type:</strong> <span id="ticketConcern"></span></p>
                <p><strong>Equipment:</strong> <span id="ticketEquipment"></span></p>
                <p><strong>Status:</strong> <span id="ticketStatus"></span></p>
               <p><strong>Urgency:</strong> <span id="ticketUrgency"></span></p>
               <p><strong>Remarks:</strong> <span id="ticketRemarks"></span></p>
               <p><strong>Approved Date:</strong> <span id="ticketApproved"></span></p>
               <p><strong>Completed By:</strong> <span id="ticketCompletedBy"></span></p>

            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Denied Purchase Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="deleteForm">
                    @csrf
                    <input type="hidden" id="delete_id">
                    
                    <div class="form-group">
                        <label for="remarks">Enter Remarks:</label>
                        <textarea class="form-control" id="remarks" rows="3" required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Denied</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(document).on("click", ".view-ticket", function() {
    var ticketId = $(this).data("id");

    $.ajax({
        url: "/admin/ticketing/" + ticketId,
        type: "GET",
        success: function(response) {
            $("#ticketNumber").text(response.ticket_number);
            $("#ticketSerial").text(response.serial_number);
            $("#ticketDepartment").text(response.department);
            $("#ticketResponsibleDept").text(response.responsible_department);
            $("#ticketConcern").text(response.concern_type);
            $("#ticketEquipment").text(response.equipment);
            $("#ticketUrgency").text(response.urgency);
            $("#ticketStatus").text(response.status);
            $("#ticketRemarks").text(response.remarks);

            // Format the approval date using JavaScript
            if (response.approval_date) {
                var approvalDate = new Date(response.approval_date);
                var formattedDate = approvalDate.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                $("#ticketApproved").text(formattedDate);
            } else {
                $("#ticketApproved").text('N/A');
            }

            $("#ticketCompletedBy").text(response.completed_by);

            // Add spin effect before showing modal
            $("#ticketModal .modal-content").css("animation", "spinIn 0.5s ease-out");

            $("#ticketModal").modal("show");
        }
    });
});


    $(document).on('click', '.Accept', function () {
        let ticketId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to approve this ticket?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/tickets/${ticketId}/accept`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function (response) {
                        Swal.fire('Approved!', 'The ticket has been approved successfully.', 'success')
                        .then(() => { location.reload(); });
                    },
                    error: function (xhr) {
                        Swal.fire('Error!', 'Something went wrong: ' + xhr.responseText, 'error');
                    }
                });
            }
        });
    });
    
    $(document).on('click', '.Delete', function () {
        var id = $(this).data('id');
        $('#delete_id').val(id); // Set the ID in the modal
        $('#deleteModal').modal('show'); // Show the modal
    });

    $('#deleteForm').submit(function (e) {
        e.preventDefault();

        var id = $('#delete_id').val();
        var remarks = $('#remarks').val();

        if (remarks.trim() === "") {
            Swal.fire("Error", "Remarks cannot be empty!", "error");
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to Deny this purchase request.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Deny',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('ticketing.delete') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        remarks_by: remarks
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Deny!',
                            text: 'The purchase request has been Denied.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Reload page after deletion
                        });
                    },
                    error: function () {
                        Swal.fire('Error', 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });



</script>
@endsection
