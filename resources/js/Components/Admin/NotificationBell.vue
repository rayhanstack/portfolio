<template>
    <!--
        NEW FILE: resources/js/Components/Admin/NotificationBell.vue

        Notification bell icon for the admin topbar.
        Shows unread count badge, dropdown list of notifications,
        and the push notification enable/disable toggle.

        Import in: resources/js/Layouts/AdminLayout.vue
        Usage: <NotificationBell />
    -->
    <div class="relative" ref="bellRef">
        <!-- Bell button -->
        <button
            @click="toggleDropdown"
            class="relative w-9 h-9 rounded-xl glass-card flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all duration-200 hover:shadow-glow"
            :class="{ 'text-primary-600 dark:text-primary-400': open }"
            title="Notifications"
        >
            <!-- Bell icon — rings when new notification arrives -->
            <svg
                :class="['w-4 h-4 transition-transform', ringing ? 'animate-[ring_0.5s_ease-in-out]' : '']"
                fill="none" stroke="currentColor" viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>

            <!-- Unread badge -->
            <Transition name="badge">
                <span
                    v-if="push.unreadCount.value > 0"
                    class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center shadow-sm"
                >
                    {{ push.unreadCount.value > 99 ? '99+' : push.unreadCount.value }}
                </span>
            </Transition>
        </button>

        <!-- Dropdown panel -->
        <Transition name="dropdown">
            <div
                v-if="open"
                class="absolute right-0 top-12 w-96 max-h-[80vh] flex flex-col glass-card border border-gray-200 dark:border-white/10 shadow-card-hover z-50 overflow-hidden"
            >
                <!-- Header -->
                <div class="flex items-center justify-between px-4 py-3.5 border-b border-gray-100 dark:border-white/5 flex-shrink-0">
                    <div>
                        <h3 class="font-display font-semibold text-gray-900 dark:text-white text-sm">Notifications</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ push.unreadCount.value }} unread
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Mark all read -->
                        <button
                            v-if="push.unreadCount.value > 0"
                            @click="push.markAllRead()"
                            class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors"
                        >
                            Mark all read
                        </button>
                    </div>
                </div>

                <!-- Notification list -->
                <div class="flex-1 overflow-y-auto divide-y divide-gray-50 dark:divide-white/5">
                    <!-- Empty state -->
                    <div v-if="push.notifications.value.length === 0" class="flex flex-col items-center justify-center py-12 text-center px-4">
                        <div class="text-4xl mb-3">🔔</div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">All caught up!</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">New contact messages will appear here</p>
                    </div>

                    <!-- Notification items -->
                    <TransitionGroup name="notification-list">
                        <div
                            v-for="notification in push.notifications.value"
                            :key="notification.id"
                            :class="[
                                'flex items-start gap-3 px-4 py-3.5 cursor-pointer hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group',
                                !notification.read_at ? 'bg-primary-50/50 dark:bg-primary-900/10' : ''
                            ]"
                            @click="handleNotificationClick(notification)"
                        >
                            <!-- Avatar -->
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-500 to-accent-purple flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-glow">
                                {{ notification.data.sender_name?.[0]?.toUpperCase() || '?' }}
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <p :class="['text-sm truncate', !notification.read_at ? 'font-semibold text-gray-900 dark:text-white' : 'font-medium text-gray-700 dark:text-gray-300']">
                                        {{ notification.data.sender_name }}
                                    </p>
                                    <div class="flex items-center gap-1.5 flex-shrink-0">
                                        <span v-if="!notification.read_at" class="w-2 h-2 rounded-full bg-primary-500 flex-shrink-0" />
                                        <span class="text-[10px] text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                            {{ notification.created_at }}
                                        </span>
                                    </div>
                                </div>
                                <p v-if="notification.data.subject" class="text-xs font-medium text-gray-600 dark:text-gray-400 mt-0.5 truncate">
                                    {{ notification.data.subject }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-0.5 line-clamp-2 leading-relaxed">
                                    {{ notification.data.preview }}
                                </p>
                            </div>
                        </div>
                    </TransitionGroup>
                </div>

                <!-- Footer: Push notification toggle + settings -->
                <div class="border-t border-gray-100 dark:border-white/5 p-3 flex-shrink-0 bg-gray-50/50 dark:bg-white/2">
                    <div class="flex items-center justify-between">
                        <!-- Push toggle -->
                        <div class="flex items-center gap-2.5">
                            <button
                                @click="push.toggle()"
                                :disabled="push.isLoading.value || !push.isSupported.value"
                                :class="[
                                    'relative w-9 h-5 rounded-full transition-colors disabled:opacity-40 disabled:cursor-not-allowed',
                                    push.isSubscribed.value ? 'bg-primary-600' : 'bg-gray-200 dark:bg-white/10'
                                ]"
                            >
                                <div :class="['absolute top-0.5 w-4 h-4 rounded-full bg-white shadow-sm transition-transform duration-200', push.isSubscribed.value ? 'left-4' : 'left-0.5']" />
                            </button>
                            <div>
                                <p class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                    {{ push.isSubscribed.value ? 'Push on' : 'Push off' }}
                                </p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500">
                                    {{ push.isSupported.value ? (push.permission.value === 'denied' ? 'Blocked in browser' : 'Browser notifications') : 'Not supported' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Test button -->
                            <button
                                v-if="push.isSubscribed.value"
                                @click="push.sendTest()"
                                class="text-xs text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 px-2 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5 transition-colors"
                                title="Send a test push notification"
                            >
                                Test
                            </button>

                            <!-- View all messages link -->
                            <Link
                                href="/admin/messages"
                                class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 font-medium px-2 py-1 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors"
                                @click="open = false"
                            >
                                All messages →
                            </Link>
                        </div>
                    </div>

                    <!-- Error message -->
                    <Transition name="fade">
                        <p v-if="push.error.value" class="mt-2 text-xs text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-900/10 rounded-lg px-2 py-1.5">
                            ⚠️ {{ push.error.value }}
                        </p>
                    </Transition>

                    <!-- Permission denied help -->
                    <Transition name="fade">
                        <p v-if="push.permission.value === 'denied'" class="mt-2 text-xs text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/10 rounded-lg px-2 py-1.5">
                            🔒 Notifications blocked. Go to browser settings → Site permissions → Notifications → Allow.
                        </p>
                    </Transition>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { usePushNotifications } from '@/Composables/usePushNotifications'
import { router } from '@inertiajs/vue3'

const push    = usePushNotifications()
const open    = ref(false)
const ringing = ref(false)
const bellRef = ref(null)

// ─── Toggle dropdown ──────────────────────────────────────────────────────────
function toggleDropdown() {
    open.value = !open.value
}

// ─── Handle notification click ────────────────────────────────────────────────
function handleNotificationClick(notification) {
    push.markRead(notification.id)
    open.value = false
    if (notification.data?.url) {
        router.visit(notification.data.url)
    }
}

// ─── Ring animation when new notification arrives ─────────────────────────────
let prevCount = 0
watch(() => push.unreadCount.value, (newVal) => {
    if (newVal > prevCount) {
        ringing.value = true
        setTimeout(() => { ringing.value = false }, 600)
    }
    prevCount = newVal
})

// ─── Close on outside click ───────────────────────────────────────────────────
function handleOutsideClick(event) {
    if (bellRef.value && !bellRef.value.contains(event.target)) {
        open.value = false
    }
}

onMounted(() => {
    document.addEventListener('mousedown', handleOutsideClick)
})

onUnmounted(() => {
    document.removeEventListener('mousedown', handleOutsideClick)
})
</script>

<style scoped>
/* Dropdown open/close animation */
.dropdown-enter-active { transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
.dropdown-leave-active { transition: all 0.15s ease-in; }
.dropdown-enter-from   { opacity: 0; transform: translateY(-8px) scale(0.97); }
.dropdown-leave-to     { opacity: 0; transform: translateY(-4px) scale(0.98); }

/* Badge pop animation */
.badge-enter-active  { transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1); }
.badge-leave-active  { transition: all 0.15s ease; }
.badge-enter-from    { opacity: 0; transform: scale(0); }
.badge-leave-to      { opacity: 0; transform: scale(0); }

/* Notification list items */
.notification-list-enter-active { transition: all 0.3s ease; }
.notification-list-leave-active { transition: all 0.2s ease; }
.notification-list-enter-from   { opacity: 0; transform: translateX(-12px); }
.notification-list-leave-to     { opacity: 0; transform: translateX(12px); }

/* Fade */
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to       { opacity: 0; }

/* Bell ring animation */
@keyframes ring {
    0%, 100% { transform: rotate(0deg); }
    20%       { transform: rotate(-15deg); }
    40%       { transform: rotate(15deg); }
    60%       { transform: rotate(-10deg); }
    80%       { transform: rotate(10deg); }
}
</style>
