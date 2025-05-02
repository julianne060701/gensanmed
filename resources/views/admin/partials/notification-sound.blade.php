@if (Auth::check()) 
<audio id="notification-sound" src="{{ asset('sounds/sound1.mp3') }}" preload="auto"></audio>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let lastNotificationCount = parseInt(localStorage.getItem('lastNotificationCount')) || 0;

        async function pollNotifications() {
            try {
                const response = await fetch('{{ route('notifications.get') }}');
                const data = await response.json();

                const count = parseInt(data.label) || 0;

                if (count > lastNotificationCount) {
                    const sound = document.getElementById('notification-sound');
                    // Only play if user has interacted (Chrome/Edge requirement)
                    if (document.hasFocus()) {
                        sound.play().catch(err => {
                            console.log('Sound play failed (interaction needed):', err);
                        });
                    }
                }

                localStorage.setItem('lastNotificationCount', count);
                lastNotificationCount = count;
            } catch (err) {
                console.warn('Notification check error:', err);
            }
        }

        // Initial call
        pollNotifications();

        // Repeat every 30 seconds
        setInterval(pollNotifications, 30000);
    });
</script>
@endif
