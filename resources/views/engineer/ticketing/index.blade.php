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
        <!-- <a href="#" class="btn btn-primary px-5">Add Purchase</a> -->
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
                            'order' => [[8, 'desc']], // Sort by Date Request (column index 6)
                            'columns' => [
                                null, // Ticket #
                                null, // Department
                                null, // Responsible Department
                                null, // Concern Type
                                null, // Urgency
                                ['orderable' => false], // Image (disable sorting)
                                null, // Status
                                null, // Date Request (Ensure this is sortable)
                                ['orderable' => false], // Actions (disable sorting)
                                null,
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

<!-- Complete By Modal -->
<div class="modal fade" id="completeByModal" tabindex="-1" aria-labelledby="completeByModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeByModalLabel">Complete By</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <textarea id="completedByName" class="form-control" rows="4" placeholder="Enter Full Name..."></textarea>
                <input type="hidden" id="ticketId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="submitCompletion">Submit</button>
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
                <p><strong>Status:</strong> <span id="ticketStatus"></span></p>
               <p><strong>Urgency:</strong> <span id="ticketUrgency"></span></p>
               <p><strong>Remarks:</strong> <span id="ticketRemarks"></span></p>
               <p><strong>Approved Date:</strong> <span id="ticketApproved"></span></p>
               <p><strong>Completed By:</strong> <span id="ticketCompletedBy"></span></p>

            </div>
        </div>
    </div>
</div>
<!-- Delete/Defective  Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Defective Request</h5>
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
                        <button type="submit" class="btn btn-danger">Defective</button>
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
$(document).ready(function () {
    // Open modal on "Completed" button click
    $('.complete-ticket').on('click', function () {
        let ticketId = $(this).data('id');
        $('#ticketId').val(ticketId);
        $('#completeByModal').modal('show');
    });

    // Submit completion with Complete By using SweetAlert
    $('#submitCompletion').on('click', function () {
        let ticketId = $('#ticketId').val();
        let completedBy = $('#completedByName').val().trim();

        if (completedBy === '') {
            Swal.fire({
                title: 'Warning!',
                text: 'Please enter your name before submitting.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to mark this ticket as completed?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/engineer/tickets/' + ticketId + '/complete',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        completed_by: completedBy
                    },
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Submitting completion details, please wait...',
                            icon: 'info',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'The ticket has been marked as completed.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong: ' + xhr.responseText,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
});
$(document).on("click", ".view-ticket", function() {
    var ticketId = $(this).data("id");

    $.ajax({
        url: "/admin/ticketing/" + ticketId,
        type: "GET",
        success: function(response) {
            if (response) {
                $("#ticketNumber").text(response.ticket_number || "N/A");
                $("#ticketSerial").text(response.serial_number || "N/A");
                $("#ticketDepartment").text(response.department || "N/A");
                $("#ticketResponsibleDept").text(response.responsible_department || "N/A");
                $("#ticketConcern").text(response.concern_type || "N/A");
                $("#ticketStatus").text(response.status || "N/A");
                $("#ticketUrgency").text(response.urgency || "N/A");
                $("#ticketRemarks").text(response.remarks || "N/A");

                // Format approval date
                var approvalDate = "N/A";
                if (response.approval_date) {
                    var date = new Date(response.approval_date);
                    var options = { year: "numeric", month: "long", day: "numeric" };
                    approvalDate = date.toLocaleDateString("en-US", options);
                }
                
                $("#ticketApproved").text(approvalDate);
                $("#ticketCompletedBy").text(response.completed_by || "N/A");

                // Show modal
                $("#ticketModal").modal("show");
            } else {
                alert("No data found for this ticket.");
            }
        },
        error: function(xhr, status, error) {
            console.log("Error: " + error);
            alert("Failed to fetch ticket details.");
        
        }
    });
});

 

$(document).on('click', '.Accept', function () {
    let ticketId = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to accept this ticket?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, accept it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/engineer/tickets/${ticketId}/accept`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'POST'
                },
                success: function (response) {
                    Swal.fire('Accepted!', 'The ticket is now In Progress.', 'success')
                    .then(() => { location.reload(); });
                },
                error: function (xhr) {
                    Swal.fire('Error!', 'Something went wrong: ' + xhr.responseText, 'error');
                }
            });
        }
    });
});

$(document).ready(function () {
    $('.Delete').on('click', function () {
        let ticketId = $(this).data('id'); // Get ticket ID from button
        $('#delete_id').val(ticketId); // Set ID in hidden input field
        $('#deleteModal').modal('show'); // Show the modal
    });

    // Handle form submission when "Defective" is clicked
    $('#deleteForm').on('submit', function (e) {
        e.preventDefault();
        
        let ticketId = $('#delete_id').val();
        let remarks = $('#remarks').val();
        
        if (!remarks.trim()) {
            Swal.fire('Error', 'Remarks cannot be empty!', 'error');
            return;
        }

        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to mark this ticket as defective?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, Defective!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('engineer.tickets.delete') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: ticketId,
                        responsible_remarks: remarks
                    },
                    success: function (response) {
                        Swal.fire('Updated!', response.success, 'success').then(() => {
                            location.reload(); // Reload the page
                        });
                    },
                    error: function (xhr) {
                        Swal.fire('Error!', xhr.responseJSON.error || 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });
});

</script>
@stop