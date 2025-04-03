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
            <textarea class="form-control" id="message" name="message" rows="4"></textarea>
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
                <tbody>
                    @foreach($users as $user)
                        <tr>
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
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#recipientsTable').DataTable();

        // Select All Checkbox Functionality
        $('#selectAll').on('click', function() {
            $('.recipient-checkbox').prop('checked', this.checked);
        });

        // Send SMS Function
        $('#sendSMSBtn').on('click', function() {
            let message = $('#message').val();
            let recipients = [];

            // Collect selected phone numbers
            $('.recipient-checkbox:checked').each(function() {
                recipients.push($(this).val());
            });

            if (message.trim() === '' || recipients.length === 0) {
                alert('Please enter a message and select at least one recipient.');
                return;
            }

            $.ajax({
    url: "{{ route('admin.schedule.send_sms') }}",
    type: "POST",
    data: {
        _token: "{{ csrf_token() }}",
        message: message,
        recipients: recipients
    },
    success: function(response) {
        alert(response.message);
    },
    error: function(error) {
        alert('Failed to send SMS. Check the console for errors.');
        console.log(error);
    }
});

        });
    });
</script>
@stop
