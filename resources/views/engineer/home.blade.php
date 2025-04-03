@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content_header')
@section('css')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<style>
#calendar {
    max-width: 1200px;
    margin: 20px auto;
}

.custom-event {
    background-color: rgba(40, 167, 69, 0.2) !important;
    /* Light Green Background */
    border-left: 4px solid #28a745 !important;
    /* Green Border */
    padding: 5px;
    border-radius: 5px;
    font-size: 14px;
    color: black !important;
    /* Dark text */
    font-weight: normal;
}

.custom-event strong {
    color: #155724;
    /* Darker Green for Time */
    font-weight: bold;
}
.content-wrapper {
    background: white !important;
}
.dashboard-card {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .card {
        flex: 1;
        min-width: 250px;
        margin: 10px;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        position: relative;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }
    .bg-primary { background: #17a2b8; }
    .bg-success { background: #28a745; }
    .bg-warning { background: #ffc107; }
    .bg-info { background: #007bff; }
    .bg-danger { background: #dc3545; }
    .card-icon {
        font-size: 50px;
        opacity: 0.3;
        position: absolute;
        right: 15px;
        bottom: 15px;
    }
    .card-content h4 {
        font-size: 20px;
        margin: 5px 0;
        font-style: bold;
    }
    .card-content {
    display: flex;
    flex-direction: column;
    align-items: flex-start; 
    width: 100%;
}
    .card-content h2 {
    align-self: center; 
    width: 100%;
    text-align: center;
}
</style>
@stop
<h1>Engineering Dashboard</h1>
@stop

@section('content')
<div class="dashboard-card">
    <div class="card bg-primary">
        <div class="card-content">
            <h2>150</h2>
            <h4>New Request Tickets</h4>
        </div>
        <i class="fas fa-users card-icon"></i>
    </div>
    <div class="card bg-warning text-dark">
        <div class="card-content">
            <h2>44</h2>
            <h4>Total Ticket Request</h4>
        </div>
        <i class="fas fa-ticket-alt card-icon"></i>
    </div>
    <div class="card bg-info">
        <div class="card-content">
            <h2>30</h2>
            <h4>Complete Ticket Request</h4>
        </div>
        <i class="fas fa-shopping-cart card-icon"></i>
    </div>
</div>
<!-- Button to trigger modal (for adding new event) -->
<!-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#eventModal">
    <i class="fas fa-plus"></i> Add Event
</button> -->

<!-- FullCalendar -->
<div id="calendar"></div>

<!-- Event Modal (Add/Edit Event) -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Add Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="eventForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="eventTitle">Event Title</label>
                        <input type="text" class="form-control" id="eventTitle" name="eventTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="eventDescription">Event Description</label>
                        <textarea class="form-control" id="eventDescription" name="eventDescription"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="fromDate">Start Date & Time</label>
                        <input type="datetime-local" class="form-control" id="fromDate" name="fromDate" required>
                    </div>
                    <div class="form-group">
                        <label for="toDate">End Date & Time</label>
                        <input type="datetime-local" class="form-control" id="toDate" name="toDate" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveEvent">Save Event</button>
            </div>
        </div>
    </div>
</div>

<!-- Event Details Modal (View Event Details & Update) -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailsModalLabel">Edit Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="eventDetailsForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="eventId" name="eventId">
                    <div class="form-group">
                        <label for="eventDetailsTitle">Event Title</label>
                        <input type="text" class="form-control" id="eventDetailsTitle" name="eventTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="eventDetailsDescription">Event Description</label>
                        <textarea class="form-control" id="eventDetailsDescription" name="eventDescription"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="eventDetailsStart">Start Date & Time</label>
                        <input type="datetime-local" class="form-control" id="eventDetailsStart" name="fromDate"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="eventDetailsEnd">End Date & Time</label>
                        <input type="datetime-local" class="form-control" id="eventDetailsEnd" name="toDate" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="deleteEvent">Delete</button>
                <button type="button" class="btn btn-primary" id="updateEvent">Update Event</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop


@section('js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function showSuccessMessage(title, message) {
        Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = "{{ route('admin.schedule.calendar') }}"; // Redirect after success
        });
    }


    document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    function formatDateTimeLocal(date) {
        if (!date) return '';
        let d = new Date(date);

        // Convert to Asia/Manila time zone
        let options = {
            timeZone: 'Asia/Manila',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false // Use 24-hour format to match input[type="datetime-local"]
        };

        let formatter = new Intl.DateTimeFormat('en-CA', options);
        let parts = formatter.formatToParts(d);
        
        let year = parts.find(p => p.type === 'year').value;
        let month = parts.find(p => p.type === 'month').value;
        let day = parts.find(p => p.type === 'day').value;
        let hour = parts.find(p => p.type === 'hour').value.padStart(2, '0');
        let minute = parts.find(p => p.type === 'minute').value.padStart(2, '0');

        return `${year}-${month}-${day}T${hour}:${minute}`; // Matches `datetime-local` input format
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        timeZone: 'local', // Ensure events are displayed in correct time zone
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
        },
        events: "{{ route('events.fetch') }}",
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        },
        eventClick: function(info) {
            let start = info.event.start;
            let end = info.event.end;

            document.getElementById('eventId').value = info.event.id;
            document.getElementById('eventDetailsTitle').value = info.event.title;
            document.getElementById('eventDetailsDescription').value = info.event.extendedProps.description;
            document.getElementById('eventDetailsStart').value = formatDateTimeLocal(start);
            document.getElementById('eventDetailsEnd').value = formatDateTimeLocal(end);

            $('#eventDetailsModal').modal('show');
        },
        eventDidMount: function(info) {
            let time = new Date(info.event.start).toLocaleTimeString([], { 
                hour: '2-digit', 
                minute: '2-digit', 
                hour12: true, 
                timeZone: 'Asia/Manila' 
            });

            info.el.innerHTML = `
                <strong style="color: white;">${time}</strong> &nbsp; 
                <span style="color: white;">${info.event.title}</span>`;
            info.el.style.backgroundColor = info.event.backgroundColor || '#28a745';
        }
    });

    calendar.render();

    // Handle saving new event
    document.getElementById('saveEvent').addEventListener('click', function(e) {
            e.preventDefault();

            var title = document.getElementById('eventTitle').value;
            var description = document.getElementById('eventDescription').value;
            var fromDate = document.getElementById('fromDate').value;
            var toDate = document.getElementById('toDate').value;

            if (title && description && fromDate && toDate) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to save this event?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, save it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('events.store') }}",
                            method: "POST",
                            data: {
                                eventTitle: title,
                                eventDescription: description,
                                fromDate: fromDate,
                                toDate: toDate,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                showSuccessMessage("Success!", response.message);
                                $('#eventModal').modal('hide');
                                document.getElementById('eventForm').reset();
                                calendar.refetchEvents();
                            },
                            error: function(xhr) {
                                Swal.fire("Error", "Failed to save event: " + xhr.responseJSON.message, "error");
                            }
                        });
                    }
                });
            } else {
                Swal.fire("Warning", "Please fill out all fields.", "warning");
            }
        });

    // Handle updating event
    document.getElementById('updateEvent').addEventListener('click', function(e) {
            e.preventDefault();

            var eventId = document.getElementById('eventId').value;
            var title = document.getElementById('eventDetailsTitle').value;
            var description = document.getElementById('eventDetailsDescription').value;
            var fromDate = document.getElementById('eventDetailsStart').value;
            var toDate = document.getElementById('eventDetailsEnd').value;

            if (title && description && fromDate && toDate) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to update this event?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, update it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/events/" + eventId,
                            method: "PUT",
                            data: {
                                eventTitle: title,
                                eventDescription: description,
                                fromDate: fromDate,
                                toDate: toDate,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                showSuccessMessage("Updated!", response.message);
                                $('#eventDetailsModal').modal('hide');
                                calendar.refetchEvents();
                            },
                            error: function(xhr) {
                                Swal.fire("Error", "Failed to update event: " + xhr.responseJSON.message, "error");
                            }
                        });
                    }
                });
            } else {
                Swal.fire("Warning", "Please fill out all fields.", "warning");
            }
        });

        // Handle deleting event with confirmation
        document.getElementById('deleteEvent').addEventListener('click', function(e) {
            e.preventDefault();

            var eventId = document.getElementById('eventId').value;

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/events/" + eventId,
                        method: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            showSuccessMessage("Deleted!", response.message);
                            $('#eventDetailsModal').modal('hide');
                            calendar.refetchEvents();
                        },
                        error: function(xhr) {
                            Swal.fire("Error", "Failed to delete event: " + xhr.responseJSON.message, "error");
                        }
                    });
                }
            });
        });

    });
    

</script>
@stop