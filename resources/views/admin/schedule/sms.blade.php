@extends('adminlte::page')

@section('title', 'Bulk SMS Messaging')
@section('plugins.Datatables', true)

@section('content_header')
    <h1>Bulk SMS Messaging</h1>
@stop

@section('css')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
<style>
    .sms-container {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(214, 7, 7, 0.1);
    }
    .btn-send {
        background: #28a745;
        color: #fff;
        font-weight: bold;
    }
</style>
@stop

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.schedule.create_sms') }}" class="btn btn-primary px-5">Add User SMS</a>
</div>

<div class="sms-container">
    <form id="smsForm">
        @csrf

        <div class="form-group">
            <label for="message">Message:</label>
            <textarea class="form-control" id="message" name="message" rows="4" placeholder="Enter your SMS message..."></textarea>
        </div>

        <!-- Group Selection (if applicable) -->
        <div class="form-group">
            <label for="group">Filter by Group (optional):</label>
            <select class="form-control" id="group">
                <option value="">-- All Groups --</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="recipients">Recipients:</label>
            <table id="recipientsTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Name</th>
                        <th>Phone Number</th>
                    </tr>
                </thead>
                <tbody id="recipientsList">
                    @foreach($users as $user)
                        <tr class="recipient-row" data-group="{{ $user->group_id }}">
                            <td><input type="checkbox" class="recipient-checkbox" value="{{ $user->phone }}"></td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->phone }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="button" class="btn btn-send" id="sendSMSBtn">Send SMS</button>
    </form>
</div>
@stop

@section('js')
<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#recipientsTable').DataTable();

    // Select All functionality
    $('#selectAll').on('click', function() {
        $('.recipient-checkbox').prop('checked', this.checked);
    });

    // Group filter
    $('#group').on('change', function() {
        const selectedGroup = $(this).val();
        $('.recipient-row').each(function() {
            if (!selectedGroup || $(this).data('group') == selectedGroup) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Send SMS with SweetAlert confirmation
    $('#sendSMSBtn').on('click', function() {
        const message = $('#message').val().trim();
        const recipients = $('.recipient-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (!message || recipients.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Info',
                text: 'Please enter a message and select at least one recipient.'
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "This will send the SMS to the selected recipients.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, send it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.schedule.send_sms') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        message: message,
                        recipients: recipients
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message
                        });
                        $('#smsForm')[0].reset();
                        $('#selectAll').prop('checked', false);
                    },
                    error: function(xhr) {
                        let msg = 'Failed to send SMS.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: msg
                        });
                        console.log(xhr);
                    }
                });
            }
        });
    });
});
</script>
@stop
