<template>
    <AdminLayout>
        <Head title="Experience" />

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-display text-xl font-bold text-gray-900 dark:text-white">Work Experience</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage your professional timeline</p>
            </div>
            <button @click="openModal()" class="btn-primary text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Experience
            </button>
        </div>

        <!-- Experience List -->
        <div class="space-y-4">
            <div
                v-for="exp in experiences"
                :key="exp.id"
                class="glass-card p-5 flex items-start gap-5"
            >
                <div class="w-12 h-12 rounded-xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-xl flex-shrink-0">💼</div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ exp.position }}</h3>
                            <div class="text-sm text-primary-600 dark:text-primary-400">{{ exp.company }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ exp.start_date ? new Date(exp.start_date).toLocaleDateString('en-US',{month:'short',year:'numeric'}) : '' }}
                                —
                                {{ exp.is_current ? 'Present' : (exp.end_date ? new Date(exp.end_date).toLocaleDateString('en-US',{month:'short',year:'numeric'}) : '') }}
                                <span v-if="exp.location"> · {{ exp.location }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button @click="openModal(exp)" class="p-1.5 rounded-lg text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            <button @click="deleteExp(exp)" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">{{ exp.description }}</p>
                    <div v-if="exp.technologies?.length" class="flex flex-wrap gap-1.5 mt-3">
                        <span v-for="t in exp.technologies" :key="t" class="badge text-xs">{{ t }}</span>
                    </div>
                </div>
            </div>

            <div v-if="!experiences.length" class="glass-card p-12 text-center text-gray-500 dark:text-gray-400 text-sm">
                No experience added yet.
            </div>
        </div>

        <!-- Modal -->
        <Teleport to="body">
            <Transition name="modal">
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="showModal = false">
                    <div class="glass-card w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
                        <h3 class="font-display font-bold text-gray-900 dark:text-white mb-5">{{ editing ? 'Edit Experience' : 'Add Experience' }}</h3>
                        <div class="space-y-4">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Company *</label>
                                    <input v-model="form.company" type="text" class="form-input" placeholder="Acme Corp" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Position *</label>
                                    <input v-model="form.position" type="text" class="form-input" placeholder="Senior Developer" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Location</label>
                                    <input v-model="form.location" type="text" class="form-input" placeholder="San Francisco, CA" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Start Date *</label>
                                    <input v-model="form.start_date" type="date" class="form-input" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">End Date</label>
                                    <input v-model="form.end_date" type="date" class="form-input" :disabled="form.is_current" />
                                </div>
                                <div class="flex items-center gap-2 mt-5">
                                    <input v-model="form.is_current" type="checkbox" id="is_current" class="w-4 h-4 rounded accent-primary-600" />
                                    <label for="is_current" class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer">Currently working here</label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Description *</label>
                                <textarea v-model="form.description" rows="3" class="form-input resize-none" placeholder="Describe your role and impact..." />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Key Responsibilities (one per line)</label>
                                <textarea
                                    :value="(form.responsibilities || []).join('\n')"
                                    @input="form.responsibilities = $event.target.value.split('\n').filter(r => r.trim())"
                                    rows="4" class="form-input resize-none font-mono text-sm"
                                    placeholder="Led team of 5 developers&#10;Architected microservices..."
                                />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Technologies (comma separated)</label>
                                <input
                                    :value="(form.technologies || []).join(', ')"
                                    @input="form.technologies = $event.target.value.split(',').map(t => t.trim()).filter(Boolean)"
                                    type="text" class="form-input" placeholder="Laravel, Vue.js, MySQL, Docker"
                                />
                            </div>
                        </div>
                        <div class="flex gap-3 mt-6">
                            <button @click="showModal = false" class="btn-ghost flex-1 justify-center text-sm">Cancel</button>
                            <button @click="save" :disabled="saving" class="btn-primary flex-1 justify-center text-sm disabled:opacity-50">{{ saving ? 'Saving...' : (editing ? 'Update' : 'Create') }}</button>
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

const props = defineProps({ experiences: { type: Array, default: () => [] } })
const showModal = ref(false)
const editing   = ref(null)
const saving    = ref(false)

const emptyForm = () => ({
    company: '', position: '', location: '', start_date: '', end_date: '',
    is_current: false, description: '', responsibilities: [], technologies: [],
    is_active: true, sort_order: 0,
})

const form = ref(emptyForm())

function openModal(exp = null) {
    editing.value = exp
    form.value = exp
        ? { ...exp, responsibilities: exp.responsibilities || [], technologies: exp.technologies || [] }
        : emptyForm()
    showModal.value = true
}

function save() {
    saving.value = true
    const url    = editing.value ? `/admin/experiences/${editing.value.id}` : '/admin/experiences'
    const method = editing.value ? 'put' : 'post'
    router[method](url, form.value, {
        onSuccess: () => { showModal.value = false },
        onFinish:  () => { saving.value = false },
    })
}

function deleteExp(exp) {
    if (confirm(`Delete "${exp.position} at ${exp.company}"?`)) router.delete(`/admin/experiences/${exp.id}`)
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.25s ease; }
.modal-enter-from, .modal-leave-to       { opacity: 0; }
</style>
