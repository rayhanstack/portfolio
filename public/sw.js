/**
 * NEW FILE: public/sw.js
 *
 * Service Worker for background Web Push notifications.
 * Registered by usePushNotifications.js composable.
 *
 * Handles:
 *   - 'push' event    → shows the browser notification when received
 *   - 'notificationclick' → navigates to the admin message URL when clicked
 *   - 'notificationclose' → tracks dismissal (optional)
 *
 * Place this file at: public/sw.js (must be served from root domain)
 */

const CACHE_NAME = 'portfolio-admin-v1'

// ─── Install ──────────────────────────────────────────────────────────────────
self.addEventListener('install', (event) => {
    self.skipWaiting()
})

// ─── Activate ─────────────────────────────────────────────────────────────────
self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim())
})

// ─── Push Received ────────────────────────────────────────────────────────────
self.addEventListener('push', (event) => {
    if (!event.data) return

    let payload
    try {
        payload = event.data.json()
    } catch {
        payload = { title: 'New Notification', body: event.data.text() }
    }

    const title   = payload.title   || '📬 New Message'
    const options = {
        body:             payload.body    || 'You have a new contact message.',
        icon:             payload.icon    || '/images/notification-icon.png',
        badge:            payload.badge   || '/images/notification-badge.png',
        tag:              payload.tag     || 'portfolio-notification',
        data:             payload.data    || { url: '/admin/messages' },
        requireInteraction: payload.requireInteraction ?? true,
        vibrate:          [200, 100, 200],
        timestamp:        Date.now(),

        // Action buttons shown in the notification
        actions: [
            {
                action: 'view',
                title:  '👁 View Message',
            },
            {
                action: 'dismiss',
                title:  '✕ Dismiss',
            },
        ],
    }

    event.waitUntil(
        self.registration.showNotification(title, options)
    )
})

// ─── Notification Clicked ─────────────────────────────────────────────────────
self.addEventListener('notificationclick', (event) => {
    event.notification.close()

    const action = event.action
    const data   = event.notification.data || {}
    const url    = data.url || '/admin/messages'

    // 'dismiss' action → just close, do nothing
    if (action === 'dismiss') return

    // 'view' action or direct click → open/focus the admin messages page
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then((windowClients) => {
                // If the admin tab is already open, focus it
                for (const client of windowClients) {
                    if (client.url.includes('/admin') && 'focus' in client) {
                        client.focus()
                        client.navigate(url)
                        return
                    }
                }
                // Otherwise open a new tab
                if (clients.openWindow) {
                    return clients.openWindow(url)
                }
            })
    )
})

// ─── Notification Closed ──────────────────────────────────────────────────────
self.addEventListener('notificationclose', (event) => {
    // Optional: track that the user dismissed the notification
    // You could send an analytics event here
    console.log('[SW] Notification dismissed:', event.notification.tag)
})
