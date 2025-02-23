@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content_header')
<h1>Engineer Dashboard</h1>
@stop

@section('content')
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

@section('css')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
        },
        events: "{{ route('events.fetch') }}",
        eventClick: function(info) {
            // Populate the event details modal with data
            document.getElementById('eventId').value = info.event.id;
            document.getElementById('eventDetailsTitle').value = info.event.title;
            document.getElementById('eventDetailsDescription').value = info.event.extendedProps
                .description;
            document.getElementById('eventDetailsStart').value = info.event.start.toISOString()
                .slice(0, 16);
            document.getElementById('eventDetailsEnd').value = info.event.end ? info.event.end
                .toISOString().slice(0, 16) : '';

            // Show the event details modal
            $('#eventDetailsModal').modal('show');
        },
        eventDidMount: function (info) {
    // Apply background color
    if (info.event.backgroundColor) {
        info.el.style.backgroundColor = info.event.backgroundColor;
    }

    // Ensure the event title is visible with proper color
    let eventContent = info.el.querySelector('.fc-event-title');
    if (eventContent) {
        eventContent.style.color = '#ffffff'; // White text for readability
    }

    // Format time with AM/PM
    if (info.event.start) {
        let time = new Date(info.event.start).toLocaleTimeString([], { 
            hour: '2-digit', 
            minute: '2-digit', 
            hour12: true // Ensures AM/PM format
        });

        // Modify the event display with formatted time & title, adding a space
        info.el.innerHTML = `<strong style="color: white;">${time}</strong> &nbsp; <span style="color: white;">${info.event.title}</span>`;
    }

    // Set text color properly
    info.el.style.color = '#ffffff'; // Ensures visibility
    info.el.style.borderColor = info.event.backgroundColor || '#28a745';
}


    });

    calendar.render();

    // Handle saving new event
    document.getElementById('saveEvent').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent form submission

        var title = document.getElementById('eventTitle').value;
        var description = document.getElementById('eventDescription').value;
        var fromDate = document.getElementById('fromDate').value;
        var toDate = document.getElementById('toDate').value;

        // Validate form inputs
        if (title && description && fromDate && toDate) {
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
                    alert(response.message);
                    $('#eventModal').modal('hide');
                    document.getElementById('eventForm').reset();
                    calendar.refetchEvents();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        } else {
            alert('Please fill out all fields.');
        }
    });

    // Handle updating event
    document.getElementById('updateEvent').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent form submission

        var eventId = document.getElementById('eventId').value;
        var title = document.getElementById('eventDetailsTitle').value;
        var description = document.getElementById('eventDetailsDescription').value;
        var fromDate = document.getElementById('eventDetailsStart').value;
        var toDate = document.getElementById('eventDetailsEnd').value;

        // Validate form inputs
        if (title && description && fromDate && toDate) {
            $.ajax({
                url: "/events/" + eventId, // Route for updating event
                method: "PUT",
                data: {
                    eventTitle: title,
                    eventDescription: description,
                    fromDate: fromDate,
                    toDate: toDate,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert(response.message);
                    $('#eventDetailsModal').modal('hide');
                    calendar.refetchEvents();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        } else {
            alert('Please fill out all fields.');
        }
    });

    // Handle deleting event
    document.getElementById('deleteEvent').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent form submission

        var eventId = document.getElementById('eventId').value;

        // Confirm delete action
        if (confirm("Are you sure you want to delete this event?")) {
            $.ajax({
                url: "/events/" +
                eventId, // Route for updating event (for inactivating/deleting)
                method: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert(response.message);
                    $('#eventDetailsModal').modal('hide');
                    calendar.refetchEvents(); // Refresh calendar events
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        }
    });
});
</script>
@stop