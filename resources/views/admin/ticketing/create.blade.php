@extends('adminlte::page')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
@section('title', 'Ticket Request')
@section('css')
<style>
    .print-btn, .status-btn {
        background-color: #008CBA;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        flex: 1;
    }
    p{
        font-size: 16px;
    }
    .print-btn:hover, .status-btn:hover {
        background-color: #005f73;
    }

    #printArea {
        display: none;
        text-align: left;
        padding: 20px;
        border: 1px solid #000;
        margin: 30px auto;
        max-width: 1000px; /* Increased max-width */
        background-color: white;
    }

    #printArea img {
        display: block;
        margin: 0 auto 20px;
        width: 120px;
    }

    .signature {
        display: flex;
        justify-content: space-between;
        margin-top: 30px; /* Increased margin-top */
    }

    .signature-line {
        border-top: 1px solid black;
        width: 45%;
        text-align: center;
        font-size: 14px;
    }

    @media print {
        .container, .logout, .print-btn {
            display: none;
        }

        #printArea {
            display: block;
            width: 100%;
            font-size: 16px;
            padding: 15px;
            margin: 0 auto; /* Center the print area */
        }

        body {
            padding: 10px;
        }

        .signature-line {
            width: 45%;
        }

        .signature {
            margin-top: 30px; /* Increased margin-top */
        }
    }


</style>
@endsection
@section('content_header')
<h1 class="ml-1">Ticket Request</h1>
@stop

@section('content')
    <div class="container centered-container">
        <div class="card">
            <div class="card-body">
                <form id="ticketForm" action="{{ route('staff.ticketing.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                   
                    {{-- Ticket Number & Serial Number --}}
                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="ticket_number">Ticket Number</label>
                            <input type="text" name="ticket_number" class="form-control" value="{{ $ticketNumber }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="serial_number">Serial No.</label>
                            <input type="text" name="serial_number" class="form-control" placeholder="Enter Serial Number" required>
                        </div>
                    </div>

                    {{-- Department & Responsible Department --}}
                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="department">Department</label>
                            <input type="text" name="department" class="form-control" placeholder="Enter Department" required>
                        </div>
                        <div class="col-md-6">
                            <label for="responsible_department">Responsible Department</label>
                            <select name="responsible_department" id="responsible_department" class="form-control" required>
                                <option value="">Select Responsible Department</option>
                                <option value="HIMS">HIMS</option>
                                <option value="Engineer">Engineer</option>
                            </select>
                        </div>
                    </div>

                    {{-- Concern Type & Urgency --}}
                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="concern_type">Concern Type</label>
                            <select name="concern_type" id="concern_type" class="form-control" required>
                                <option value="">Select Concern Type</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="urgency">Urgency</label>
                            <select name="urgency" class="form-control" required>
                                <option value="">Select Urgency</option>
                                <option value="Not Urgent">1 - Not Urgent</option>
                                <option value="Urgent">2 - Urgent</option>
                            </select>
                        </div>
                    </div>

                    {{-- Dynamic Fields (IT Equipment) --}}
                    <div class="form-row" id="form-row"></div>

                    {{-- Remarks --}}
                    <div class="form-group">
                        <label for="remarks">Remarks (Input anydesk number if the concern is medsys)</label>
                        <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter remarks"></textarea>
                    </div>

                    {{-- Supporting Document Upload --}}
                    <div class="form-group">
                        <label for="imageUpload">Attach Image</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image_url" id="imageUpload" accept="image/*">
                                <label class="custom-file-label" for="imageUpload">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <!-- Image Preview Section -->
                    <div class="form-group">
                        <p id="fileName" class="mt-2"></p>
                        <img id="imagePreview" src="#" alt="Image Preview" style="max-width: 200px; display: none;" />
                    </div>

                    <!-- Print & Submit Buttons -->
                    <button type="button" class="btn btn-primary mt-4" onclick="printConcern()">Print</button>
                    <button type="submit" class="btn btn-success mt-4">Submit Request</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Print Area -->
    <div id="printArea">
        <img src="{{ asset('img/logo.png') }}" alt="Company Logo" style="width: 200px;">
        <h1 style="text-align: center;">Concern Ticket</h1>
        <hr style="border-top: 3px solid #bbb;">

        <p style="font-size: 20px;"><strong>Ticket Number:</strong> <span id="printTicketNumber"></span></p>
        <p style="font-size: 20px;"><strong>Serial No.:</strong> <span id="printSerialNumber"></span></p>
        <p style="font-size: 20px;"><strong>Department:</strong> <span id="printDepartment"></span></p>
        <p style="font-size: 20px;"><strong>Responsible Department:</strong> <span id="printResponsibleDepartment"></span></p>
        <p style="font-size: 20px;"><strong>Concern Type:</strong> <span id="printConcernType"></span></p>
        <p style="font-size: 20px;"><strong>Urgency:</strong> <span id="printUrgency"></span></p>
        <hr style="border-top: 3px solid #bbb;">
        <h2 style="font-size: 24px;">Concern Details:</h2>
        <p id="printRemarks" style="font-size: 20px;"></p>
        <hr style="border-top: 3px solid #bbb;">
       

<br><br><br><br><br><br>
        <div class="signature">
            <div class="signature-line">Approved By</div>
            <div class="signature-line">Received By</div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function printConcern() {
            document.getElementById("printTicketNumber").innerText = document.querySelector('input[name="ticket_number"]').value;
            document.getElementById("printSerialNumber").innerText = document.querySelector('input[name="serial_number"]').value;
            document.getElementById("printDepartment").innerText = document.querySelector('input[name="department"]').value;
            document.getElementById("printResponsibleDepartment").innerText = document.querySelector('select[name="responsible_department"]').value;
            document.getElementById("printConcernType").innerText = document.querySelector('select[name="concern_type"]').value;
            document.getElementById("printUrgency").innerText = document.querySelector('select[name="urgency"]').value;
            document.getElementById("printRemarks").innerText = document.querySelector('textarea[name="remarks"]').value;


            document.getElementById("printArea").style.display = "block";
            window.print();
            document.getElementById("printArea").style.display = "none";
        }

        document.getElementById('imageUpload').addEventListener('change', function (event) {
            const inputFile = event.target;
            const file = inputFile.files[0];
            const fileLabel = inputFile.closest('.custom-file').querySelector('.custom-file-label');
            const fileNameDisplay = document.getElementById('fileName');
            const imagePreview = document.getElementById('imagePreview');

            if (file) {
                fileLabel.textContent = file.name;
                fileNameDisplay.textContent = `Selected file: ${file.name}`;

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }
            } else {
                fileLabel.textContent = 'Choose file';
                fileNameDisplay.textContent = '';
                imagePreview.style.display = 'none';
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const responsibleDept = document.getElementById('responsible_department');
            const concernType = document.getElementById('concern_type');
            const formRow = document.getElementById('form-row');
            const form = document.getElementById("ticketForm");

            let itEquipmentDiv = document.createElement('div');
            itEquipmentDiv.classList.add('col-md-6', 'mt-2');
            itEquipmentDiv.innerHTML = `
                <label for="equipment">IT Equipment</label>
                <select name="equipment" id="equipment" class="form-control">
                    <option value="">Select IT Equipment</option>
                    <option value="Printer">Printer</option>
                    <option value="Printer Sharing">Printer Sharing</option>
                    <option value="Computer">Computer</option>
                    <option value="Monitor">Monitor</option>
                    <option value="Keyboard">Keyboard</option>
                    <option value="Mouse">Mouse</option>
                    <option value="Speaker">Speaker</option>  
                    <option value="Scanner">Scanner</option>
                    <option value="UPS">UPS</option>
                    <option value="Telephone">Telephone</option>
                    <option value="Network">Network</option>
                </select>
            `;

            const concernOptions = {
                "HIMS": ["Repair", "Maintenance", "Medsys", "Software"],
                "Engineer": ["Fabrication", "Installation", "Repair", "Maintenance", "PMS"]
            };

            responsibleDept.addEventListener('change', function () {
                concernType.innerHTML = '<option value="">Select Concern Type</option>';

                if (concernOptions[this.value]) {
                    concernOptions[this.value].forEach(type => {
                        let option = document.createElement('option');
                        option.value = type;
                        option.textContent = type;
                        concernType.appendChild(option);
                    });
                }

                removeItEquipment();
            });

            concernType.addEventListener('change', function () {
                if (responsibleDept.value === "HIMS" && this.value === "Repair") {
                    addItEquipment();
                } else {
                    removeItEquipment();
                }
            });

            function addItEquipment() {
                if (!document.getElementById('equipment')) {
                    formRow.appendChild(itEquipmentDiv);
                }
            }

            function removeItEquipment() {
                if (document.getElementById('equipment')) {
                    itEquipmentDiv.remove();
                }
            }
        });

    </script>
@endsection
