{{-- resources/views/errors/404.blade.php --}}
@extends('adminlte::page')

@section('title', 'Session Expired')

@section('content_header')
    <h1>Session Expired</h1>
@stop

@section('content')
    <div class="text-center">
        <h2>Your session has expired or the page was not found.</h2>
        <p>Please <a href="{{ route('login') }}">log in</a> again.</p>
    </div>
@stop
