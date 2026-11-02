@extends('pension-guard.layout')

@section('title', 'My Notifications')

@section('content')
    <section class="panel">
        <h2>My notifications</h2>

        <p>
            Review security reminders and updates related to your
            Pension Account Guard account.
        </p>

        <p>
            <strong>Unread notifications:</strong>
            {{ $unreadCount }}
        </p>

        @if ($notifications->isEmpty())
            <p>You have no notifications yet.</p>
        @else
            @foreach ($notifications as $notification)
                @php
                    $data = $notification->data;
                @endphp

                <article class="panel">
                    <h3>
                        {{ $data['title'] ?? 'Security notification' }}
                    </h3>

                    <p>
                        {{ $data['message'] ?? 'You have a new notification.' }}
                    </p>

                    <p>
                        <small>
                            {{ $notification->created_at->format('d M Y, H:i') }}
                        </small>
                    </p>

                    @if ($notification->read_at)
                        <p>Read</p>
                    @else
                        <p><strong>Unread</strong></p>

                        <form
                            method="POST"
                            action="{{ route('pension-guard.notifications.read', ['notification' => $notification->id]) }}"
                        >
                            @csrf
                            @method('PATCH')

                            <button type="submit">
                                Mark as read
                            </button>
                        </form>
                    @endif
                </article>
            @endforeach

            {{ $notifications->links() }}
        @endif
    </section>
@endsection
