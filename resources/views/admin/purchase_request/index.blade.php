@extends('adminlte::page')

@section('title', 'Dashboard')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
<link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">

@section('content_header')
<h1 class="ml-1">Purchaser Request</h1>
@stop

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-end mb-3">
        <!-- <a href="{{ route('admin.purchase.create') }}" class="btn btn-primary px-5">Upload PO</a> -->
    </div>




@section('js')
<script>

</script>
@endsection


