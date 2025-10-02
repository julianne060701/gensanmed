@extends('adminlte::page')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
@section('title', 'Borrow Request')


@section('content_header')
<h1 class="ml-1">Borrow Request</h1>
@stop

@section('content')
<div class="container centered-container">
    <div class="card">
        <div class="card-body">
            <form id="borrowForm" action="{{ route('head.borrow.store') }}" method="POST">
                @csrf

                {{-- Borrower Name & Purpose --}}
                <div class="form-row">
                    <div class="col-md-6">
                        <label for="borrower_name">Borrower Name</label>
                        <input type="text" name="borrower_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="purpose">Purpose</label>
                        <input type="text" name="purpose" class="form-control" required>
                    </div>
                </div>

                {{-- Location & Equipment Type --}}
                <div class="form-row mt-3">
                    <div class="col-md-6">
                        <label for="location">Location</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="type_of_equipment">Type of Equipment</label>
                        <input type="text" name="type_of_equipment" class="form-control" required>
                    </div>
                </div>

                {{-- Borrowed At & Returned At --}}
                <div class="form-row mt-3">
                    <div class="col-md-6">
                        <label for="borrowed_at">Date & Time Borrowed</label>
                        <input type="datetime-local" name="borrowed_at" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="returned_at">Date & Time Returned</label>
                        <input type="datetime-local" name="returned_at" class="form-control">
                    </div>
                </div>

                <!-- Print & Submit Buttons -->
                <button type="button" class="btn btn-primary mt-4" onclick="printBorrow()">Print</button>
                <button type="submit" class="btn btn-success mt-4">Submit Request</button>
            </form>
        </div>
    </div>
</div>

@endsection


