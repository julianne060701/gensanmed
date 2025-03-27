@extends('adminlte::page')

@section('title', 'Dashboard')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content_header')
    <h1 class="ml-1">Ticket Report</h1>
@stop

@section('content')
<style>
@media print {
    @page {
        size: A4 landscape;
    }
    body * {
        visibility: hidden;
    }
    #report-content, #report-content * {
        visibility: visible;
    }
    #report-content {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
    .dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate, .btn-group, .row.mb-3 {
        display: none !important;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
}
</style>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="date_filter_type">Filter By</label>
            <select id="date_filter_type" class="form-control">
                <option value="7">Date Request</option>
                <option value="8">Date Approval</option>
                <option value="9">Date Completed</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="end_date">End Date</label>
            <input type="date" id="end_date" class="form-control">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary" onclick="filterTable()">Apply Filter</button>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-download"></i> Export Report
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#" onclick="downloadPDF()">
                    <i class="fas fa-file-pdf"></i> Download as PDF
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Ticket Report Table</h3>
                </div>
                <div class="card-body" id="report-content">
                    @php
                        $heads = [
                            'Ticket #',
                            'Department',  
                            'Responsible Department',                        
                            'Concern Type', 
                            'Serial No.',  
                            'Remarks',  
                            'Urgency',                               
                            'Status',
                            'Date Request',
                            'Date Approval',
                            'Date Completed',
                            'Total Duration',
                            'Remarks By Admin',
                            'Official Remarks',
                            'Completed By',
                        ];
                        
                        $config = [
                            'order' => [[0, 'desc']], // Default sort by Date Request
                            'columns' => array_fill(0, count($heads), null),
                        ];
                    @endphp
                    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" hoverable class="table table-striped table-bordered">
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
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

<script>
function printPage() {
    window.print();
}

function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
        orientation: "landscape", // Landscape mode
        unit: "mm", // Use millimeters for precise scaling
        format: [216, 330], // Long bond paper size in mm (8.5 x 13 inches)
    });

    doc.setFont("helvetica", "bold");
     // Center the "Ticket Report" title
     const title = "Ticket Report";
    const pageWidth = doc.internal.pageSize.getWidth(); // Get the width of the page
    const textWidth = doc.getTextWidth(title); // Get the width of the text
    const centerX = (pageWidth - textWidth) / 2; // Calculate center position
    doc.text(title, centerX, 10); // Set the title at the center

    const table = document.querySelector("#table1");
    const headers = [];
    const data = [];

    // Extract only the visible headers
    table.querySelectorAll("thead tr th").forEach(th => {
        if (th.offsetParent !== null) { // Check if header is visible
            headers.push(th.innerText.trim());
        }
    });

    // Extract only the visible rows
    table.querySelectorAll("tbody tr").forEach(tr => {
        if (tr.offsetParent !== null) { // Check if row is visible
            const row = [];
            tr.querySelectorAll("td").forEach(td => {
                if (td.offsetParent !== null) { // Check if cell is visible
                    row.push(td.innerText.trim());
                }
            });
            data.push(row);
        }
    });

    // Generate table in PDF
    doc.autoTable({
        head: [headers],
        body: data,
        startY: 20,
        theme: 'grid',
        styles: { fontSize: 8, cellPadding: 2, halign: 'center' }, // Adjusted for better fit
        headStyles: { 
            fillColor: [255, 255, 255], // No background color for headers
            textColor: 0, 
            fontStyle: 'bold',
            lineWidth: 0.5, // Header border
            lineColor: [0, 0, 0] // Black border
        },
        alternateRowStyles: { fillColor: [245, 245, 245] }, // Light gray alternate rows
        margin: { top: 15, left: 5, right: 5 }, // Small margins to fit all columns
        tableWidth: 'auto', // Automatically adjusts columns to fit the page
    });

    // Save the PDF file
    doc.save("Ticket_Report.pdf");
}



// Filter table by date range
function filterTable() {
    let startDate = document.getElementById("start_date").value;
    let endDate = document.getElementById("end_date").value;
    let dateColumnIndex = document.getElementById("date_filter_type").value;

    let table = document.getElementById("table1").getElementsByTagName("tbody")[0];
    let rows = table.getElementsByTagName("tr");

    for (let row of rows) {
        let dateCell = row.cells[dateColumnIndex]?.innerText.trim();
        if (!dateCell) {
            row.style.display = "none";
            continue;
        }

        // Convert dateCell to YYYY-MM-DD format to ensure consistency
        let ticketDate = new Date(dateCell);
        let formattedTicketDate = ticketDate.getFullYear() + '-' + 
                                  String(ticketDate.getMonth() + 1).padStart(2, '0') + '-' + 
                                  String(ticketDate.getDate()).padStart(2, '0');

        // Ensure input dates are in YYYY-MM-DD format
        let start = startDate ? new Date(startDate) : null;
        let end = endDate ? new Date(endDate) : null;

        let startFormatted = start ? start.getFullYear() + '-' +
                                      String(start.getMonth() + 1).padStart(2, '0') + '-' +
                                      String(start.getDate()).padStart(2, '0') : null;

        let endFormatted = end ? end.getFullYear() + '-' +
                                  String(end.getMonth() + 1).padStart(2, '0') + '-' +
                                  String(end.getDate()).padStart(2, '0') : null;

        // Check if the date is within the selected range
        if ((!startFormatted || formattedTicketDate >= startFormatted) &&
            (!endFormatted || formattedTicketDate <= endFormatted)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    }
}

</script>
@endsection
