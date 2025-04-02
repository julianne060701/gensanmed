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
</style>
@stop
<h1>Admin Calendar</h1>
@stop

@section('content')

<!-- Button to trigger modal (for adding new event) -->
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#eventModal">
    <i class="fas fa-plus"></i> Add Event
</button>

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
        return d.toISOString().slice(0, 16); // Converts to YYYY-MM-DDTHH:MM
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        timeZone: 'local', // Ensure it follows user local time
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
        },
        events: "{{ route('events.fetch') }}",
        eventTimeFormat: { // Ensures correct AM/PM format
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