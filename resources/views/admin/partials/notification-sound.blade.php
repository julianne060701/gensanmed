@if (Auth::check()) 
<audio id="notification-sound" src="{{ asset('sounds/sound1.mp3') }}" preload="auto"></audio>

<script>
    let lastNotificationCount = 0;

    async function pollNotifications() {
        try {
            const response = await fetch('{{ route('notifications.get') }}');
            const data = await response.json();

            const count = data.label; // Use 'label' from the response

            if (count > lastNotificationCount) {
                // Play sound only if count increases (new notification)
                const sound = document.getElementById('notification-sound');
                sound.play().catch(err => console.log('Sound play failed:', err)); // Handle errors gracefully
            }

            lastNotificationCount = count;
        } catch (err) {
            console.warn('Notification check error:', err);
        }
    }

    // First check on page load
    pollNotifications();

    // Check every 30 seconds
    setInterval(pollNotifications, 30000);
</script>
@endif
