@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content_header')
@section('css')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<style>
#calendar {
    max-width: 1200px;
    margin: 20px auto;
}

.custom-event {
    background-color: rgba(40, 167, 69, 0.2) !important;
    border-left: 4px solid #28a745 !important;
    padding: 5px;
    border-radius: 5px;
    font-size: 14px;
    color: black !important;
    font-weight: normal;
}

.custom-event strong {
    color: #155724;
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
<h1>Pharmacy Purchaser Dashboard</h1>
@stop

@section('content')
<div class="dashboard-card">
    <div class="card bg-primary">
        <div class="card-content">
            <h2>{{ $newPRCount }}</h2>
            <h4>New Purchase Request</h4>
        </div>
        <i class="fas fa-ticket-alt card-icon"></i>
    </div>
    <div class="card bg-danger">
        <div class="card-content">
            <h2>{{ $newPOCount }}</h2>
            <h4>Request Purchase Order</h4>
        </div>
        <i class="fas fa-ticket-alt card-icon"></i>
</div>
    <div class="card bg-warning text-dark">
        <div class="card-content">
            <h2>{{ $totalPRCount }}</h2>
            <h4>Total Purchase Request</h4>
        </div>
        <i class="fas fa-ticket-alt card-icon"></i>
    </div>
    <div class="card bg-info">
        <div class="card-content">
            <h2>{{ $totalPOCount }}</h2>
            <h4>Total Purchase Order</h4>
        </div>
        <i class="fas fa-ticket-alt card-icon"></i>
    </div>
</div>

<!-- FullCalendar -->
<div id="calendar"></div>

<!-- Event Details Modal (View Event Details & Update) -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailsModalLabel">Details of the Event</h5>
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
                        <input type="text" class="form-control" id="eventDetailsTitle" name="eventTitle" readonly>
                    </div>
                    <div class="form-group">
                        <label for="eventDetailsDescription">Event Description</label>
                        <textarea class="form-control" id="eventDetailsDescription" name="eventDescription" readonly></textarea>
                    </div>
                    <div class="form-group">
                        <label for="eventDetailsDepartment">From Department</label>
                        <textarea class="form-control" id="eventDetailsDepartment" name="fromDepartment" readonly></textarea>
                    </div>
                    <div class="form-group">
                        <label for="eventDetailsStart">Start Date & Time</label>
                        <input type="datetime-local" class="form-control" id="eventDetailsStart" name="fromDate"
                        readonly>
                    </div>
                    <div class="form-group">
                        <label for="eventDetailsEnd">End Date & Time</label>
                        <input type="datetime-local" class="form-control" id="eventDetailsEnd" name="toDate" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
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
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    function formatDateTimeLocal(date) {
        if (!date) return '';
        let d = new Date(date);

        let options = {
            timeZone: 'Asia/Manila',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        };

        let formatter = new Intl.DateTimeFormat('en-CA', options);
        let parts = formatter.formatToParts(d);
        
        let year = parts.find(p => p.type === 'year').value;
        let month = parts.find(p => p.type === 'month').value;
        let day = parts.find(p => p.type === 'day').value;
        let hour = parts.find(p => p.type === 'hour').value.padStart(2, '0');
        let minute = parts.find(p => p.type === 'minute').value.padStart(2, '0');

        return `${year}-${month}-${day}T${hour}:${minute}`;
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        timeZone: 'local',
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
            document.getElementById('eventDetailsDepartment').value = info.event.extendedProps.department;

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
  });
</script>
@stop

