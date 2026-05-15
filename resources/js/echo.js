import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

/*
|--------------------------------------------------------------------------
| Filament Notification
|--------------------------------------------------------------------------
*/

window.Echo.private(`App.Models.User.${window.userId}`)
    .notification((notification) => {

        console.log('notification', notification);

        if (window.Filament?.Notification) {
            window.Filament.Notification.make()
                .title(notification.title ?? 'Notification')
                .body(notification.body ?? '')
                .success()
                .send();
        }
    });

/*
|--------------------------------------------------------------------------
| Smart Refresh System
|--------------------------------------------------------------------------
|
| debounce 2 sec
| max wait 3 sec
|
*/

let refreshTimeout = null;
let firstEventTime = null;

window.handleCarModelChanged = () => {

    const now = Date.now();

    console.log('car model changed');

    // event แรก
    if (!firstEventTime) {
        firstEventTime = now;
    }

    // clear timer เดิม
    if (refreshTimeout) {
        clearTimeout(refreshTimeout);
    }

    const elapsed = now - firstEventTime;

    // ถ้าเกิน 3 วิแล้ว refresh เลย
    if (elapsed >= 3000) {

        console.log('MAX WAIT REACHED -> REFRESH');

        doRefresh();

        return;
    }

    // debounce 2 วิ
    refreshTimeout = setTimeout(() => {

        console.log('DEBOUNCE REFRESH');

        doRefresh();

    }, 2000);
};

function doRefresh() {

    firstEventTime = null;

    refreshTimeout = null;

    // reload livewire table
    Livewire.dispatch('refresh-car-model-table');
}

/*
|--------------------------------------------------------------------------
| Listen Broadcast
|--------------------------------------------------------------------------
*/

window.Echo.channel('car-models')
    .listen('.car-model.changed', (e) => {

        console.log('broadcast received', e);

        window.handleCarModelChanged();
    });