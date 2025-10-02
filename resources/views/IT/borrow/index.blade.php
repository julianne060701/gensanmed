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
        <!-- <a href="#" class="btn btn-primary px-5">Borrow Request</a> -->
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


@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(document).on("click", ".view-borrow", function() {
    var borrowId = $(this).data("id");

    $.ajax({
        url: "/IT/borrower/" + borrowId,
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
});


    $(document).on('click', '.Accept', function () {
        let borrowId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to approve this borrow request?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/IT/borrower/${borrowId}/accept`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function (response) {
                        Swal.fire('Approved!', 'The borrow request has been approved successfully.', 'success')
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
        $('#delete_id').val(id);
        $('#deleteModal').modal('show');
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
            text: "You are about to Deny this borrow request.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Deny',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/IT/borrower/${id}/deny`,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        remarks: remarks
                    },
                    success: function (response) {
                        Swal.fire({
                            title: 'Denied!',
                            text: 'The borrow request has been denied.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function () {
                        Swal.fire('Error', 'Something went wrong!', 'error');
                    }
                });
            }
        });
    });

    // Handle Return button click
    $(document).on('click', '.Return', function () {
        let borrowId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to mark this equipment as returned?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, mark as returned!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/IT/borrower/${borrowId}/return`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function (response) {
                        Swal.fire('Returned!', 'The equipment has been marked as returned successfully.', 'success')
                        .then(() => { location.reload(); });
                    },
                    error: function (xhr) {
                        Swal.fire('Error!', 'Something went wrong: ' + xhr.responseText, 'error');
                    }
                });
            }
        });
    });

</script>
@endsection
