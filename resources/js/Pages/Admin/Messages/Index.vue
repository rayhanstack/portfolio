<template>
    <AdminLayout>
        <Head title="Messages" />

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-display text-xl font-bold text-gray-900 dark:text-white">Messages</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    {{ messages.total }} total · {{ messages.data.filter(m => !m.is_read).length }} unread on this page
                </p>
            </div>
            <button
                v-if="selected.length"
                @click="bulkDelete"
                class="flex items-center gap-2 px-4 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-xl text-sm font-medium transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete {{ selected.length }} selected
            </button>
        </div>

        <div class="glass-card overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5">
                        <th class="px-5 py-3.5 w-10">
                            <input type="checkbox" @change="toggleAll" class="w-4 h-4 rounded accent-primary-600" />
                        </th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">From</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide hidden md:table-cell">Subject</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide hidden lg:table-cell">Date</th>
                        <th class="px-5 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                    <tr
                        v-for="msg in messages.data"
                        :key="msg.id"
                        :class="[
                            'hover:bg-gray-50 dark:hover:bg-white/5 transition-colors cursor-pointer',
                            !msg.is_read ? 'bg-primary-50/30 dark:bg-primary-900/5' : ''
                        ]"
                        @click="$inertia.visit(`/admin/messages/${msg.id}`)"
                    >
                        <td class="px-5 py-4" @click.stop>
                            <input
                                type="checkbox"
                                :value="msg.id"
                                v-model="selected"
                                class="w-4 h-4 rounded accent-primary-600"
                            />
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center font-bold text-primary-600 dark:text-primary-400 text-xs flex-shrink-0">
                                    {{ msg.name[0].toUpperCase() }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span :class="['text-sm', !msg.is_read ? 'font-semibold text-gray-900 dark:text-white' : 'font-medium text-gray-700 dark:text-gray-300']">
                                            {{ msg.name }}
                                        </span>
                                        <span v-if="!msg.is_read" class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0" />
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ msg.email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 hidden md:table-cell">
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                {{ msg.subject || '(no subject)' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 hidden lg:table-cell">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ new Date(msg.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }}
                            </span>
                        </td>
                        <td class="px-5 py-4" @click.stop>
                            <button
                                @click="deleteMessage(msg.id)"
                                class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="!messages.data.length">
                        <td colspan="5" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400 text-sm">
                            No messages yet.
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div v-if="messages.last_page > 1" class="border-t border-gray-100 dark:border-white/5 px-5 py-4 flex items-center justify-between">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Page {{ messages.current_page }} of {{ messages.last_page }}
                </span>
                <div class="flex gap-2">
                    <Link
                        v-if="messages.prev_page_url"
                        :href="messages.prev_page_url"
                        class="px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/10 text-sm transition-colors"
                    >Prev</Link>
                    <Link
                        v-if="messages.next_page_url"
                        :href="messages.next_page_url"
                        class="px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/10 text-sm transition-colors"
                    >Next</Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    messages: { type: Object, default: () => ({ data: [], total: 0 }) },
})

const selected = ref([])

function toggleAll(e) {
    selected.value = e.target.checked ? props.messages.data.map(m => m.id) : []
}

function deleteMessage(id) {
    if (confirm('Delete this message?')) {
        router.delete(`/admin/messages/${id}`)
    }
}

function bulkDelete() {
    if (confirm(`Delete ${selected.value.length} messages?`)) {
        router.post('/admin/messages/bulk-delete', { ids: selected.value }, {
            onSuccess: () => { selected.value = [] },
        })
    }
}
</script>
