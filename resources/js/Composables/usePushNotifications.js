/**
 * NEW FILE: resources/js/Composables/usePushNotifications.js
 *
 * Vue 3 composable that encapsulates the entire browser Web Push
 * notification lifecycle for the admin panel.
 *
 * Responsibilities:
 *   1. Register the Service Worker (public/sw.js)
 *   2. Fetch the VAPID public key from Laravel
 *   3. Subscribe / unsubscribe the browser via PushManager
 *   4. Persist the subscription to Laravel backend
 *   5. Connect to Laravel Echo (Pusher/Reverb) for real-time in-app alerts
 *   6. Expose reactive state consumed by NotificationBell.vue
 *
 * Usage (in AdminLayout.vue):
 *   import { usePushNotifications } from '@/Composables/usePushNotifications'
 *   const push = usePushNotifications()
 */

import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

// ─── Helper: convert VAPID public key (Base64URL) → Uint8Array ───────────────
function urlBase64ToUint8Array(base64String) {
    const padding  = '='.repeat((4 - (base64String.length % 4)) % 4)
    const base64   = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/')
    const rawData  = atob(base64)
    return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)))
}

export function usePushNotifications() {
    // ─── State ────────────────────────────────────────────────────────────────
    const isSupported   = ref('serviceWorker' in navigator && 'PushManager' in window)
    const isSubscribed  = ref(false)
    const isLoading     = ref(false)
    const permission    = ref(Notification.permission)   // 'default' | 'granted' | 'denied'
    const notifications = ref([])                        // in-app bell items
    const unreadCount   = ref(0)
    const error         = ref(null)

    let swRegistration  = null
    let echoChannel     = null

    // ─── Service Worker Registration ─────────────────────────────────────────
    async function registerServiceWorker() {
        if (!isSupported.value) return null
        try {
            swRegistration = await navigator.serviceWorker.register('/sw.js', { scope: '/' })
            await navigator.serviceWorker.ready
            return swRegistration
        } catch (err) {
            console.error('[Push] SW registration failed:', err)
            error.value = 'Service Worker registration failed.'
            return null
        }
    }

    // ─── Check current subscription status ───────────────────────────────────
    async function checkSubscriptionStatus() {
        if (!swRegistration) return
        try {
            const sub = await swRegistration.pushManager.getSubscription()
            if (sub) {
                // Verify it's still valid on the server side
                const { data } = await axios.get('/admin/push/status', {
                    params: { endpoint: sub.endpoint },
                })
                isSubscribed.value = data.subscribed
            } else {
                isSubscribed.value = false
            }
        } catch {
            isSubscribed.value = false
        }
    }

    // ─── Subscribe ────────────────────────────────────────────────────────────
    async function subscribe() {
        if (!isSupported.value || !swRegistration) return
        isLoading.value = true
        error.value     = null

        try {
            // 1. Request notification permission
            const perm = await Notification.requestPermission()
            permission.value = perm

            if (perm !== 'granted') {
                error.value = 'Notification permission denied. Please enable notifications in your browser settings.'
                return
            }

            // 2. Get VAPID public key from server
            const { data: keyData } = await axios.get('/admin/push/vapid-public-key')
            const applicationServerKey = urlBase64ToUint8Array(keyData.public_key)

            // 3. Create push subscription via PushManager
            const subscription = await swRegistration.pushManager.subscribe({
                userVisibleOnly:      true,  // Required: must show notification to user
                applicationServerKey,
            })

            // 4. Send subscription to Laravel backend
            await axios.post('/admin/push/subscribe', {
                endpoint: subscription.endpoint,
                keys: {
                    p256dh: btoa(String.fromCharCode(...new Uint8Array(subscription.getKey('p256dh')))),
                    auth:   btoa(String.fromCharCode(...new Uint8Array(subscription.getKey('auth')))),
                },
                encoding: (PushManager.supportedContentEncodings || ['aesgcm'])[0],
            })

            isSubscribed.value = true
        } catch (err) {
            console.error('[Push] Subscribe failed:', err)
            error.value = err.response?.data?.message || 'Failed to subscribe to push notifications.'
        } finally {
            isLoading.value = false
        }
    }

    // ─── Unsubscribe ─────────────────────────────────────────────────────────
    async function unsubscribe() {
        if (!swRegistration) return
        isLoading.value = true
        error.value     = null

        try {
            const subscription = await swRegistration.pushManager.getSubscription()

            if (subscription) {
                // Remove from server first
                await axios.post('/admin/push/unsubscribe', {
                    endpoint: subscription.endpoint,
                })
                // Then unsubscribe from browser
                await subscription.unsubscribe()
            }

            isSubscribed.value = false
        } catch (err) {
            console.error('[Push] Unsubscribe failed:', err)
            error.value = 'Failed to unsubscribe.'
        } finally {
            isLoading.value = false
        }
    }

    // ─── Toggle subscription ─────────────────────────────────────────────────
    async function toggle() {
        if (isSubscribed.value) {
            await unsubscribe()
        } else {
            await subscribe()
        }
    }

    // ─── Send test notification ───────────────────────────────────────────────
    async function sendTest() {
        try {
            await axios.post('/admin/push/test')
        } catch (err) {
            error.value = err.response?.data?.error || 'Test failed.'
        }
    }

    // ─── Fetch in-app notifications ───────────────────────────────────────────
    async function fetchNotifications() {
        try {
            const { data } = await axios.get('/admin/push/notifications')
            notifications.value = data.notifications
            unreadCount.value   = data.unread_count
        } catch {
            // Silently fail — not critical
        }
    }

    // ─── Mark notification as read ────────────────────────────────────────────
    async function markRead(id) {
        try {
            await axios.post(`/admin/push/notifications/${id}/read`)
            const n = notifications.value.find(n => n.id === id)
            if (n) n.read_at = new Date().toISOString()
            if (unreadCount.value > 0) unreadCount.value--
        } catch { /* silent */ }
    }

    async function markAllRead() {
        try {
            await axios.post('/admin/push/notifications/mark-all-read')
            notifications.value.forEach(n => { n.read_at = n.read_at || new Date().toISOString() })
            unreadCount.value = 0
        } catch { /* silent */ }
    }

    // ─── Real-time via Laravel Echo ───────────────────────────────────────────
    /**
     * Connect to the private Echo channel so admins get an in-page
     * notification the instant a contact form is submitted —
     * even before the Web Push notification arrives.
     *
     * Requires window.Echo to be configured (see bootstrap.js additions below).
     */
    function connectEcho() {
        if (!window.Echo) return

        echoChannel = window.Echo.private('admin.notifications')
            .listen('.new.message', (payload) => {
                // Add to notification bell immediately
                notifications.value.unshift({
                    id:         'echo-' + Date.now(),
                    data:       {
                        message_id:   payload.id,
                        sender_name:  payload.name,
                        sender_email: payload.email,
                        subject:      payload.subject,
                        preview:      payload.preview,
                        url:          `/admin/messages/${payload.id}`,
                    },
                    read_at:    null,
                    created_at: payload.received_at,
                })
                unreadCount.value++

                // Browser notification via Notification API (if tab is open + permission granted)
                if (permission.value === 'granted' && document.hidden) {
                    // Tab is hidden — browser push will handle it
                    return
                }
                if (permission.value === 'granted' && !document.hidden) {
                    // Tab is visible — show a subtle in-page toast instead
                    // (handled reactively in NotificationBell.vue via unreadCount)
                }
            })
    }

    function disconnectEcho() {
        if (window.Echo && echoChannel) {
            window.Echo.leave('admin.notifications')
            echoChannel = null
        }
    }

    // ─── Lifecycle ────────────────────────────────────────────────────────────
    onMounted(async () => {
        if (!isSupported.value) return

        await registerServiceWorker()
        await checkSubscriptionStatus()
        await fetchNotifications()
        connectEcho()
    })

    onUnmounted(() => {
        disconnectEcho()
    })

    return {
        // State
        isSupported,
        isSubscribed,
        isLoading,
        permission,
        notifications,
        unreadCount,
        error,
        // Actions
        subscribe,
        unsubscribe,
        toggle,
        sendTest,
        fetchNotifications,
        markRead,
        markAllRead,
    }
}
