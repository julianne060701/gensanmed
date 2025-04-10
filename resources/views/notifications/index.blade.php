@extends('adminlte::page')

@section('content')
    <h4>Notifications</h4>

    <!-- Button to mark all as read -->
    <a href="{{ route('notifications.markAllRead') }}" class="btn btn-primary mb-3">Mark All as Read</a>

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

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>



@endsection
