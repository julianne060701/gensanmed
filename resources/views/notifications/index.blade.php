@extends('adminlte::page')

@section('content')
    <div class="container-fluid">

        <!-- Section Header -->
        <div class="row mb-4">
            <div class="col">
                <h4>Notifications</h4>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Button to mark all as read -->
        <div class="row mb-3">
            <div class="col">
                <a href="{{ route('notifications.markAllRead') }}" class="btn btn-primary">Mark All as Read</a>
            </div>
        </div>

        <!-- Notification List -->
        <div class="row mb-4">
            <div class="col">
                @if ($notifications->count())
                    <ul class="list-group">
                        @foreach ($notifications as $notification)
                            <li class="list-group-item {{ $notification->read_at ? '' : 'list-group-item-info' }}">
                                <a href="{{ route('notifications.read', $notification->id) }}" class="d-block text-decoration-none text-dark">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>{{ $notification->data['title'] ?? 'Notification' }}</strong><br>
                                            <small>{{ $notification->data['message'] ?? '' }}</small>
                                        </div>
                                        <div class="text-muted text-end">
                                            {{ $notification->created_at->diffForHumans() }}
                                            @if (!$notification->read_at)
                                                <span class="badge bg-warning text-dark">New</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="alert alert-info">No notifications available.</div>
                @endif
            </div>
        </div>

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

    .badge.bg-warning {
        font-size: 0.75rem;
        padding: 0.25em 0.5em;
        border-radius: 0.2rem;
    }
</style>
@endpush
