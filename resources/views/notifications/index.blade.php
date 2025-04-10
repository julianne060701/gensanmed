@extends('adminlte::page')

@section('content')
    <div class="container-fluid">
        <!-- Section Header -->
        <div class="row mb-4">
            <div class="col">
                <h4>Notifications</h4>
            </div>
        </div>

        <!-- Button to mark all as read -->
        <div class="row mb-3">
            <div class="col">
                <a href="{{ route('notifications.markAllRead') }}" class="btn btn-primary">Mark All as Read</a>
            </div>
        </div>

        <!-- Notification List -->
        <div class="row mb-4">
            <div class="col">
                <ul class="list-group">
                    @foreach ($notifications as $notification)
                        <li class="list-group-item {{ $notification->read_at ? '' : 'list-group-item-info' }}">
                            <a href="{{ route('notifications.read', $notification->id) }}">
                                <strong>{{ $notification->data['title'] ?? 'Notification' }}</strong><br>
                                {{ $notification->data['message'] ?? '' }}
                                <span class="float-right text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success mt-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Pagination -->
        <div class="row">
            <div class="col">
                <div class="d-flex justify-content-center">
                    {{ $notifications->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
<style>
    /* Fix oversized pagination arrows */
    .pagination .page-link {
        font-size: 1rem !important;
        padding: 0.375rem 0.75rem;
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
    }

    .pagination .page-link svg,
    .pagination .page-link i {
        font-size: 1rem !important;
        vertical-align: middle;
    }
</style>
@endpush
