<template>
    <AdminLayout>
        <Head title="Social Links" />

        <div class="max-w-2xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-display text-xl font-bold text-gray-900 dark:text-white">Social Links</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage your social media profiles</p>
                </div>
                <button @click="openModal()" class="btn-primary text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Link
                </button>
            </div>

            <div class="glass-card overflow-hidden">
                <div class="divide-y divide-gray-50 dark:divide-white/5">
                    <div
                        v-for="link in links"
                        :key="link.id"
                        class="flex items-center gap-4 p-4 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors"
                    >
                        <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-xl flex-shrink-0">
                            {{ platformEmoji(link.platform) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 dark:text-white text-sm capitalize">{{ link.platform }}</div>
                            <a :href="link.url" target="_blank" class="text-xs text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 truncate block transition-colors">
                                {{ link.url }}
                            </a>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span :class="['w-2 h-2 rounded-full', link.is_active ? 'bg-green-400' : 'bg-gray-300 dark:bg-gray-600']" />
                            <button @click="openModal(link)" class="p-1.5 rounded-lg text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button @click="deleteLink(link)" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div v-if="!links.length" class="p-12 text-center text-gray-500 dark:text-gray-400 text-sm">
                        No social links yet. Add your profiles!
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <Teleport to="body">
            <Transition name="modal">
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="showModal = false">
                    <div class="glass-card w-full max-w-sm p-6">
                        <h3 class="font-display font-bold text-gray-900 dark:text-white mb-5">{{ editing ? 'Edit Link' : 'Add Social Link' }}</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Platform *</label>
                                <select v-model="form.platform" class="form-input">
                                    <option value="">Select platform</option>
                                    <option v-for="p in platforms" :key="p.value" :value="p.value">{{ p.label }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Profile URL *</label>
                                <input v-model="form.url" type="url" placeholder="https://github.com/username" class="form-input" />
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Sort Order</label>
                                    <input v-model.number="form.sort_order" type="number" class="form-input" />
                                </div>
                                <div class="flex items-end pb-1">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input v-model="form.is_active" type="checkbox" class="w-4 h-4 rounded accent-primary-600" />
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3 mt-6">
                            <button @click="showModal = false" class="btn-ghost flex-1 justify-center text-sm">Cancel</button>
                            <button @click="save" :disabled="saving" class="btn-primary flex-1 justify-center text-sm disabled:opacity-50">
                                {{ saving ? 'Saving...' : (editing ? 'Update' : 'Add') }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ links: { type: Array, default: () => [] } })

const showModal = ref(false)
const editing   = ref(null)
const saving    = ref(false)
const form      = ref({ platform: '', url: '', is_active: true, sort_order: 0 })

const platforms = [
    { value: 'github',    label: '🐙 GitHub'    },
    { value: 'linkedin',  label: '💼 LinkedIn'  },
    { value: 'twitter',   label: '🐦 Twitter/X' },
    { value: 'instagram', label: '📸 Instagram' },
    { value: 'facebook',  label: '📘 Facebook'  },
    { value: 'youtube',   label: '▶️  YouTube'   },
]

const emojiMap = { github: '🐙', linkedin: '💼', twitter: '🐦', instagram: '📸', facebook: '📘', youtube: '▶️' }

function platformEmoji(p) { return emojiMap[p?.toLowerCase()] || '🔗' }

function openModal(link = null) {
    editing.value = link
    form.value = link
        ? { platform: link.platform, url: link.url, is_active: link.is_active, sort_order: link.sort_order }
        : { platform: '', url: '', is_active: true, sort_order: 0 }
    showModal.value = true
}

function save() {
    saving.value = true
    const url    = editing.value ? `/admin/social-links/${editing.value.id}` : '/admin/social-links'
    const method = editing.value ? 'put' : 'post'
    router[method](url, form.value, {
        onSuccess: () => { showModal.value = false },
        onFinish:  () => { saving.value = false },
    })
}

function deleteLink(link) {
    if (confirm(`Delete ${link.platform}?`)) router.delete(`/admin/social-links/${link.id}`)
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.25s ease; }
.modal-enter-from, .modal-leave-to       { opacity: 0; }
</style>
