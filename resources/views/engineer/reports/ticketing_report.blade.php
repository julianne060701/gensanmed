@extends('adminlte::page')

@section('title', 'Dashboard')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content_header')
    <h1 class="ml-1">Ticket Report</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <!-- Date Range Filter -->
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
                <a class="dropdown-item" href="#" onclick="printPage()">
                    <i class="fas fa-print"></i> Print
                </a>
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
                            'order' => [[13, 'desc']], // Sort by Date Request
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

<script>
function printPage() {
    window.print();
}

function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: "landscape" });

    doc.text("Ticket Report", 14, 10);

    const table = document.querySelector("#table1");
    const data = [];
    const headers = [];

    table.querySelectorAll("thead tr th").forEach(th => headers.push(th.innerText));
    table.querySelectorAll("tbody tr").forEach(tr => {
        const row = [];
        tr.querySelectorAll("td").forEach(td => row.push(td.innerText));
        data.push(row);
    });

    doc.autoTable({
        head: [headers],
        body: data,
        startY: 20,
        theme: 'striped',
        styles: { fontSize: 10, cellPadding: 3 },
        headStyles: { fillColor: [41, 128, 185], textColor: 255, fontStyle: 'bold' },
        alternateRowStyles: { fillColor: [240, 240, 240] },
    });

    doc.save("Ticket_Report.pdf");
}

// Function to filter table by date range
function filterTable() {
    let startDate = document.getElementById("start_date").value;
    let endDate = document.getElementById("end_date").value;

    let table = document.getElementById("table1").getElementsByTagName("tbody")[0];
    let rows = table.getElementsByTagName("tr");

    for (let row of rows) {
        let dateCell = row.cells[5]?.innerText.trim(); // Date Request column
        if (!dateCell) continue;

        let ticketDate = new Date(dateCell);
        let start = startDate ? new Date(startDate) : null;
        let end = endDate ? new Date(endDate) : null;

        if ((!start || ticketDate >= start) && (!end || ticketDate <= end)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    }
}
</script>
@endsection