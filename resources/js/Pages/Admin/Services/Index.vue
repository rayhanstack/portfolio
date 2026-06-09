<template>
    <AdminLayout>
        <Head title="Services" />

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-display text-xl font-bold text-gray-900 dark:text-white">Services</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage the services you offer</p>
            </div>
            <button @click="openModal()" class="btn-primary text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Service
            </button>
        </div>

        <!-- Flash -->
        <div v-if="$page.props.flash?.success"
            class="mb-4 flex items-center gap-2 px-4 py-3 rounded-xl bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 text-green-700 dark:text-green-400 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ $page.props.flash.success }}
        </div>

        <!-- Service List -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="service in services" :key="service.id"
                class="glass-card p-5 group flex flex-col gap-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="text-3xl leading-none">{{ service.icon_svg || '⚙️' }}</div>
                    <div class="flex gap-1">
                        <span :class="['px-2 py-0.5 rounded-full text-xs font-medium', service.is_active ? 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-white/10 text-gray-500']">
                            {{ service.is_active ? 'Active' : 'Hidden' }}
                        </span>
                        <button @click="openModal(service)" class="p-1.5 rounded-lg text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                        <button @click="deleteService(service)" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white">{{ service.title }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-3 flex-1">{{ service.description }}</p>
                <div v-if="service.color" class="flex items-center gap-1.5 text-xs text-gray-400">
                    <span class="w-3 h-3 rounded-full border border-white/20 flex-shrink-0" :style="{ background: service.color }" />
                    {{ service.color }}
                </div>
            </div>
        </div>

        <div v-if="!services.length" class="glass-card p-12 text-center text-gray-500 dark:text-gray-400 text-sm">
            No services added yet. Click "Add Service" to get started.
        </div>

        <!-- Modal -->
        <Teleport to="body">
            <Transition name="modal">
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="showModal = false">
                    <div class="glass-card w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                        <h3 class="font-display font-bold text-gray-900 dark:text-white mb-5">
                            {{ editing ? 'Edit Service' : 'Add Service' }}
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Title *</label>
                                <input v-model="form.title" type="text" class="form-input" placeholder="Web Development" />
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Description *</label>
                                <textarea v-model="form.description" rows="4" class="form-input resize-none" placeholder="Describe what this service includes..." />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Icon / Emoji</label>
                                    <input v-model="form.icon_svg" type="text" class="form-input" placeholder="🌐" />
                                    <p class="mt-1 text-xs text-gray-400">Paste an emoji or SVG string</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Accent Color</label>
                                    <div class="flex items-center gap-2">
                                        <input v-model="form.color" type="color" class="w-10 h-9 rounded-lg border border-gray-200 dark:border-white/10 cursor-pointer bg-transparent p-0.5" />
                                        <input v-model="form.color" type="text" class="form-input flex-1" placeholder="#6366f1" />
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Sort Order</label>
                                    <input v-model.number="form.sort_order" type="number" min="0" class="form-input" />
                                </div>
                                <div class="flex items-center gap-2 mt-5">
                                    <input v-model="form.is_active" type="checkbox" id="svc_active" class="w-4 h-4 rounded accent-primary-600" />
                                    <label for="svc_active" class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer">Active / Visible</label>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-6">
                            <button @click="showModal = false" class="btn-ghost flex-1 justify-center text-sm">Cancel</button>
                            <button @click="save" :disabled="saving" class="btn-primary flex-1 justify-center text-sm disabled:opacity-50">
                                {{ saving ? 'Saving…' : (editing ? 'Update' : 'Create') }}
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
import { router, Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props     = defineProps({ services: { type: Array, default: () => [] } })
const showModal = ref(false)
const editing   = ref(null)
const saving    = ref(false)

const emptyForm = () => ({
    title: '', description: '', icon_svg: '', color: '', is_active: true, sort_order: 0,
})

const form = ref(emptyForm())

function openModal(service = null) {
    editing.value  = service
    form.value     = service ? { ...service } : emptyForm()
    showModal.value = true
}

function save() {
    saving.value = true
    const url    = editing.value ? `/admin/services/${editing.value.id}` : '/admin/services'
    const method = editing.value ? 'put' : 'post'
    router[method](url, form.value, {
        onSuccess: () => { showModal.value = false },
        onFinish:  () => { saving.value = false },
    })
}

function deleteService(service) {
    if (confirm(`Delete "${service.title}"?`)) router.delete(`/admin/services/${service.id}`)
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.25s ease; }
.modal-enter-from, .modal-leave-to       { opacity: 0; }
</style>