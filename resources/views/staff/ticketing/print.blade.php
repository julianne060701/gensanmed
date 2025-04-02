@extends('adminlte::page')

@section('title', 'Print Ticket')

@section('content')
    <!-- Print Area -->
    <div id="printArea">
        <div class="ticket-header">
            <img src="{{ asset('img/logo.png') }}" alt="Company Logo">
            <h1>Concern Ticket</h1>
        </div>

        <hr>

        @if(isset($ticket))
            <p><strong>Ticket Number:</strong> {{ $ticket->ticket_number }}</p>
            <p><strong>Serial No.:</strong> {{ $ticket->serial_number ?? 'N/A' }}</p>
            <p><strong>Department:</strong> {{ $ticket->department }}</p>
            <p><strong>Responsible Department:</strong> {{ $ticket->responsible_department ?? 'N/A' }}</p>
            <p><strong>Concern Type:</strong> {{ $ticket->concern_type }}</p>
            <p><strong>Urgency:</strong> {{ $ticket->urgency }}</p>

            <hr>
            <h2>Concern Details:</h2>
            <p>{{ $ticket->remarks }}</p>
            <hr>

            <div class="signature">
                <div class="signature-line">
                    <p>Approved By</p>
                </div>
                <div class="signature-line">
                    <p>Received By</p>
                </div>
            </div>
        @else
            <p class="text-danger text-center">⚠️ Ticket data not found!</p>
        @endif
    </div>

    <!-- Print Button -->
    <div class="text-center mt-3">
        <button class="print-btn" id="printButton">Print Ticket</button>
    </div>

    @section('js')
    <script>
       document.getElementById("printButton").addEventListener("click", function() {
    let printContent = document.getElementById("printArea").outerHTML;
    let printWindow = window.open("", "_blank", "width=800,height=600");

    if (printWindow) {
        printWindow.document.write(`
            <html>
            <head>
                <title>Print Ticket</title>
                <style>
                
                  #printArea {
                        display: block;
                        width: 100%;
                        max-width: 800px;
                        margin: 0 auto;
                        padding: 20px;
                        background: white;
                        color: black;
                        text-align: left;
                        border: 1px solid black;
                        box-sizing: border-box;
                        border-collapse: collapse;
                    }

                    .ticket-header {
                        text-align: center;
                    }
                    #printArea img {
                        width: 120px;
                        margin-bottom: 20px;
                    }
                    .signature {
                        display: flex;
                        justify-content: space-between;
                        margin-top: 30px;
                    }
                    .signature-line {
                        border-top: 1px solid black;
                        width: 45%;
                        text-align: center;
                    }
                    @media print {
                        .print-btn {
                            display: none;
                        }
                    }
                        
                </style>
            </head>
            <body>
                ${printContent}
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(() => window.close(), 1000);
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    } else {
        alert("Please allow pop-ups to print the ticket.");
    }
});

    </script>
    @endsection

    <style>
        /* Common Styles for Dashboard and Print */
        #printArea {
            display: block;
            width: 100%;
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: white;
            color: black;
            text-align: left;
            border: 1px solid #000;
        }

        .ticket-header {
            text-align: center;
        }

        #printArea img {
            width: 120px;
            margin-bottom: 20px;
        }

        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .signature-line {
            border-top: 1px solid black;
            width: 45%;
            text-align: center;
        }

        /* Print Button */
        .print-btn {
            background-color: #008CBA;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-btn:hover {
            background-color: #005f73;
        }

        /* Hide print button on actual print */
        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
@endsection
