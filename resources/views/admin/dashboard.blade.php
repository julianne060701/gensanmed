@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content_header')
@section('css')
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
<style>
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
<h1>Admin Dashboard</h1>
@stop

@section('content')
<div class="dashboard-card">
    <div class="card bg-primary">
        <div class="card-content">
            <h2>150</h2>
            <h4>Total Users</h4>
        </div>
        <i class="fas fa-users card-icon"></i>
    </div>
    <div class="card bg-success">
        <div class="card-content">
            <h2>53</h2>
            <h4>Total Events</h4>
        </div>
        <i class="fas fa-calendar-alt card-icon"></i>
    </div>
    <div class="card bg-warning text-dark">
        <div class="card-content">
            <h2>44</h2>
            <h4>Total Tickets</h4>
        </div>
        <i class="fas fa-ticket-alt card-icon"></i>
    </div>
    <div class="card bg-info">
        <div class="card-content">
            <h2>30</h2>
            <h4>Purchase Orders</h4>
        </div>
        <i class="fas fa-shopping-cart card-icon"></i>
    </div>
    <div class="card bg-danger">
        <div class="card-content">
            <h2>20</h2>
            <h4>Purchase Requests</h4>
        </div>
        <i class="fas fa-file-invoice card-icon"></i>
    </div>
</div>
@stop

@section('js')
<script>
    console.log('Dashboard Loaded');
</script>
@stop
