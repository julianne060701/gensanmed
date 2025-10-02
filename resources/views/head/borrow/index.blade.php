@extends('adminlte::page')

@section('title', 'Borrow Equipment')
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
    <h1 class="ml-1">Borrow Equipment</h1>
@stop

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('head.borrow.create') }}" class="btn btn-primary px-5">Borrow Request</a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    @php
                        $heads = [
                            'Borrow #',
                            'Borrower Name',  
                            'Purpose',                        
                            'Location',        
                            'Type of Equipment',                                          
                            'Date and Time Borrowed',
                            'Date and Time Returned',
                            'Total Duration',
                            'Status',
                            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
                        ];

                        $config = [
                            'order' => [[0, 'desc']], 
                            'columns' => [
                                null,
                                null,
                                null,
                                null,
                                null,
                                null, 
                                null, 
                                null,
                                null,
                                ['orderable' => false], 
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

{{-- Borrow Equipment Details Modal --}}
<div class="modal fade" id="borrowModal" tabindex="-1" role="dialog" aria-labelledby="borrowModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="borrowModalLabel">Borrow Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Borrow Number:</strong> <span id="borrowNumber"></span></p>
                <p><strong>Borrower Name:</strong> <span id="borrower_name"></span></p>
                <p><strong>Purpose:</strong> <span id="purpose"></span></p>
                <p><strong>Locatation:</strong> <span id="location"></span></p>
                <p><strong>Equipment Need:</strong> <span id="type_of_equipment"></span></p>
                <p><strong>Borrow Date and Time:</strong> <span id="borrowedAt"></span></p>
               <p><strong>Return Date and Time:</strong> <span id="returnedAt"></span></p>
               <p><strong>Total Duration:</strong> <span id="totalDuration"></span></p>
               <p><strong>Approved Date:</strong> <span id="borrowApproved"></span></p>
               <p><strong>Denied Remarks:</strong> <span id="borrowDeniedRemarks"></span></p>
            </div>
        </div>
    </div>
</div>

<!-- Deny Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Deny Borrow Request</h5>
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
                        <button type="submit" class="btn btn-danger">Deny</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Borrow Modal -->
<div class="modal fade" id="editBorrowModal" tabindex="-1" role="dialog" aria-labelledby="editBorrowModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editBorrowForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editBorrowModalLabel">Edit Borrow Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id">

                    <div class="form-group">
                        <label for="edit_borrower_name">Borrower Name</label>
                        <input type="text" class="form-control" id="edit_borrower_name" name="borrower_name" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_purpose">Purpose</label>
                        <textarea class="form-control" id="edit_purpose" name="purpose" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="edit_location">Location</label>
                        <input type="text" class="form-control" id="edit_location" name="location" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_type_of_equipment">Type of Equipment</label>
                        <input type="text" class="form-control" id="edit_type_of_equipment" name="type_of_equipment" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_borrowed_at">Borrow Date</label>
                        <input type="datetime-local" class="form-control" id="edit_borrowed_at" name="borrowed_at" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_returned_at">Return Date</label>
                        <input type="datetime-local" class="form-control" id="edit_returned_at" name="returned_at">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(document).on("click", ".view-borrow", function() {
    var borrowId = $(this).data("id");

    $.ajax({
        url: "/head/borrow/" + borrowId,
        type: "GET",
        success: function(response) {
            $("#borrowNumber").text(response.id);
            $("#borrower_name").text(response.borrower_name);
            $("#purpose").text(response.purpose);
            $("#location").text(response.location);
            $("#type_of_equipment").text(response.type_of_equipment);
            $("#borrowedAt").text(response.borrowedAt);
            $("#returnedAt").text(response.returnedAt);
            $("#totalDuration").text(response.totalDuration);
            $("#borrowApproved").text(response.borrowApproved || "N/A");
            $("#borrowDeniedRemarks").text(response.borrowDeniedRemarks || "N/A");

            if (response.approval_date) {
                var approvalDate = new Date(response.approval_date);
                var formattedDate = approvalDate.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                $("#borrowApproved").text(formattedDate);
            } else {
                $("#borrowApproved").text('N/A');
            }

            $("#borrowReturnedBy").text(response.returned_by || "N/A");

            $("#borrowModal .modal-content").css("animation", "spinIn 0.5s ease-out");

            $("#borrowModal").modal("show");
        }
    });
    // Open Edit Modal and Load Data
$(document).on("click", ".edit-borrow", function() {
    var borrowId = $(this).data("id");

    $.ajax({
        url: "/head/borrow/" + borrowId,
        type: "GET",
        success: function(response) {
            $("#edit_id").val(response.id);
            $("#edit_borrower_name").val(response.borrower_name);
            $("#edit_purpose").val(response.purpose);
            $("#edit_location").val(response.location);
            $("#edit_type_of_equipment").val(response.type_of_equipment);

            if (response.borrowedAt && response.borrowedAt !== "—") {
                let borrowedAt = new Date(response.borrowedAt);
                $("#edit_borrowed_at").val(borrowedAt.toISOString().slice(0,16));
            }

            if (response.returnedAt && response.returnedAt !== "—") {
                let returnedAt = new Date(response.returnedAt);
                $("#edit_returned_at").val(returnedAt.toISOString().slice(0,16));
            }

            $("#editBorrowModal").modal("show");
        }
    });
});

// Submit Edit Form via AJAX
$("#editBorrowForm").submit(function(e) {
    e.preventDefault();

    var borrowId = $("#edit_id").val();
    var formData = $(this).serialize();

    $.ajax({
        url: "/head/borrow/" + borrowId,
        type: "POST", // Laravel requires POST + _method=PUT
        data: formData,
        success: function(response) {
            $("#editBorrowModal").modal("hide");
            Swal.fire("Updated!", "Borrow record updated successfully!", "success")
                .then(() => location.reload());
        },
        error: function(xhr) {
            Swal.fire("Error", "Something went wrong while updating.", "error");
        }
    });
});

});

    
   </script>
@endsection
