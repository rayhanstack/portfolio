import axios from 'axios'

window.axios = axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

/**
 * MODIFIED: resources/js/bootstrap.js
 *
 * Added Laravel Echo + Pusher for real-time push notification broadcasts.
 *
 * Install frontend dependencies:
 *   npm install --save-dev laravel-echo pusher-js
 *
 * Set in .env:
 *   BROADCAST_DRIVER=pusher          (or reverb)
 *   PUSHER_APP_ID=your-app-id
 *   PUSHER_APP_KEY=your-app-key
 *   PUSHER_APP_SECRET=your-app-secret
 *   PUSHER_APP_CLUSTER=mt1
 *   VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
 *   VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
 *
 * For self-hosted (free): use Laravel Reverb instead of Pusher:
 *   composer require laravel/reverb
 *   php artisan reverb:install
 *   BROADCAST_DRIVER=reverb
 *   VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
 *   VITE_REVERB_HOST="localhost"
 *   VITE_REVERB_PORT="8080"
 */
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

// ── Option A: Pusher (cloud, paid after limits) ───────────────────────────────
if (import.meta.env.VITE_PUSHER_APP_KEY) {
    window.Echo = new Echo({
        broadcaster:   'pusher',
        key:           import.meta.env.VITE_PUSHER_APP_KEY,
        cluster:       import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
        forceTLS:      true,
        authEndpoint:  '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
        },
    })
}

// ── Option B: Laravel Reverb (self-hosted, free) ──────────────────────────────
// Uncomment this block and comment out Option A if using Reverb:
//
// if (import.meta.env.VITE_REVERB_APP_KEY) {
//     window.Echo = new Echo({
//         broadcaster: 'reverb',
//         key:         import.meta.env.VITE_REVERB_APP_KEY,
//         wsHost:      import.meta.env.VITE_REVERB_HOST ?? 'localhost',
//         wsPort:      import.meta.env.VITE_REVERB_PORT ?? 8080,
//         wssPort:     import.meta.env.VITE_REVERB_PORT ?? 8080,
//         forceTLS:    false,
//         enabledTransports: ['ws', 'wss'],
//         authEndpoint: '/broadcasting/auth',
//         auth: {
//             headers: {
//                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
//             },
//         },
//     })
// }

