@extends('adminlte::page')

@section('title', 'Edit PO')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
@section('content_header')
<h1 class="ml-1">Edit PO</h1>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form id="editForm" action="{{ route('pharmpurch.purchase.update', $purchase->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-row align-items-center mb-3">
                    <div class="col-md-4">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="Send to Supplier" {{ $purchase->status === 'Send to Supplier' ? 'selected' : '' }}>Send to Supplier</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="po_number">PO Number</label>
                        <input type="text" name="po_number" class="form-control" value="{{ $purchase->po_number }}" readonly required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="userName">Name of User</label>
                    <input type="text" name="name" id="userName" class="form-control" value="{{ $purchase->name }}" readonly required>
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="description" id="remarks" class="form-control" readonly rows="3">{{ $purchase->description }}</textarea>
                </div>

                <button type="button" class="btn btn-success mt-4" id="confirmEdit">Update</button>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to update this Purchase Order?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitEditForm">Yes, Update</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Show confirmation modal before submitting
    document.getElementById('confirmEdit').addEventListener('click', function () {
        $('#confirmModal').modal('show');
    });

    // Submit form when user confirms
    document.getElementById('submitEditForm').addEventListener('click', function () {
        $('#confirmModal').modal('hide');
        document.getElementById('editForm').submit();
    });

    // Show success alert if redirected with success message
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Updated Successfully',
            text: '{{ session("success") }}',
            confirmButtonColor: '#28a745'
        });
    @endif
</script>
@endsection

