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
                <option value="9">Date Request</option>
                <option value="10">Date Approval</option>
                <option value="11">Date Completed</option>
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
                            'Equipment',
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
                            'order' => [[11, 'desc']],
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
function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
        orientation: "landscape",
        unit: "mm",
        format: [216, 330],
    });

    const title = "Ticket Report";
    const pageWidth = doc.internal.pageSize.getWidth();
    const textWidth = doc.getTextWidth(title);
    const centerX = (pageWidth - textWidth) / 2;
    doc.text(title, centerX, 10);

    const table = document.querySelector("#table1");
    const headers = [];
    const data = [];

    table.querySelectorAll("thead tr th").forEach(th => {
        if (th.offsetParent !== null) {
            headers.push(th.innerText.trim());
        }
    });

    table.querySelectorAll("tbody tr").forEach(tr => {
        if (tr.offsetParent !== null && tr.style.display !== "none") {
            const row = [];
            tr.querySelectorAll("td").forEach(td => {
                row.push(td.innerText.trim());
            });
            data.push(row);
        }
    });

    doc.autoTable({
        head: [headers],
        body: data,
        startY: 20,
        theme: 'grid',
        styles: { fontSize: 8, cellPadding: 2, halign: 'center' },
        headStyles: {
            fillColor: [255, 255, 255],
            textColor: 0,
            fontStyle: 'bold',
            lineWidth: 0.5,
            lineColor: [0, 0, 0]
        },
        alternateRowStyles: { fillColor: [245, 245, 245] },
        margin: { top: 15, left: 5, right: 5 },
        tableWidth: 'auto',
    });

    doc.save("Ticket_Report.pdf");
}

function filterTable() {
    let startDate = document.getElementById("start_date").value;
    let endDate = document.getElementById("end_date").value;
    let columnIndex = parseInt(document.getElementById("date_filter_type").value);

    const start = startDate ? new Date(startDate + 'T00:00:00') : null;
    const end = endDate ? new Date(endDate + 'T23:59:59') : null;

    let rows = document.querySelectorAll("#table1 tbody tr");

    rows.forEach(row => {
        let cell = row.cells[columnIndex];
        if (!cell) {
            row.style.display = "none";
            return;
        }

        let cellText = cell.innerText.trim();
        let rowDate = new Date(cellText);

        if ((isNaN(rowDate)) || (start && rowDate < start) || (end && rowDate > end)) {
            row.style.display = "none";
        } else {
            row.style.display = "";
        }
    });
}
</script>
@endsection
