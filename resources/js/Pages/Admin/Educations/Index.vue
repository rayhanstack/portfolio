<template>
    <AdminLayout>
        <Head title="Education" />

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-display text-xl font-bold text-gray-900 dark:text-white">Education</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage your academic background</p>
            </div>
            <button @click="openModal()" class="btn-primary text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Education
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

        <!-- Education List -->
        <div class="space-y-4">
            <div v-for="edu in educations" :key="edu.id"
                class="glass-card p-5 flex items-start gap-5">

                <!-- Logo / fallback -->
                <div class="w-12 h-12 rounded-xl overflow-hidden bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center flex-shrink-0">
                    <img v-if="edu.institution_logo" :src="edu.institution_logo" :alt="edu.institution" class="w-full h-full object-cover" />
                    <span v-else class="text-2xl">🎓</span>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ edu.degree }}
                                <span v-if="edu.field_of_study" class="text-gray-500 dark:text-gray-400 font-normal"> — {{ edu.field_of_study }}</span>
                            </h3>
                            <div class="text-sm text-primary-600 dark:text-primary-400">{{ edu.institution }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ edu.start_year }} – {{ edu.is_current ? 'Present' : (edu.end_year || '—') }}
                                <span v-if="edu.location"> · {{ edu.location }}</span>
                                <span v-if="edu.result_gpa"> · {{ edu.result_gpa }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span :class="['px-2 py-0.5 rounded-full text-xs font-medium', edu.is_active ? 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-white/10 text-gray-500']">
                                {{ edu.is_active ? 'Active' : 'Hidden' }}
                            </span>
                            <button @click="openModal(edu)" class="p-1.5 rounded-lg text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            <button @click="deleteEdu(edu)" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                    <p v-if="edu.description" class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">{{ edu.description }}</p>
                </div>
            </div>

            <div v-if="!educations.length" class="glass-card p-12 text-center text-gray-500 dark:text-gray-400 text-sm">
                No education records added yet.
            </div>
        </div>

        <!-- Modal -->
        <Teleport to="body">
            <Transition name="modal">
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="showModal = false">
                    <div class="glass-card w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
                        <h3 class="font-display font-bold text-gray-900 dark:text-white mb-5">
                            {{ editing ? 'Edit Education' : 'Add Education' }}
                        </h3>

                        <div class="space-y-4">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Institution *</label>
                                    <input v-model="form.institution" type="text" class="form-input" placeholder="University of Dhaka" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Degree *</label>
                                    <input v-model="form.degree" type="text" class="form-input" placeholder="Bachelor of Science" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Field of Study</label>
                                    <input v-model="form.field_of_study" type="text" class="form-input" placeholder="Computer Science" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Location</label>
                                    <input v-model="form.location" type="text" class="form-input" placeholder="Dhaka, Bangladesh" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Result / GPA</label>
                                    <input v-model="form.result_gpa" type="text" class="form-input" placeholder="3.85 / 4.00" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Start Year *</label>
                                    <input v-model.number="form.start_year" type="number" min="1950" max="2100" class="form-input" placeholder="2018" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">End Year</label>
                                    <input v-model.number="form.end_year" type="number" class="form-input" placeholder="2022" :disabled="form.is_current" />
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <input v-model="form.is_current" type="checkbox" id="edu_current" class="w-4 h-4 rounded accent-primary-600" />
                                    <label for="edu_current" class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer">Currently studying here</label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Description</label>
                                <textarea v-model="form.description" rows="3" class="form-input resize-none" placeholder="Activities, achievements, thesis topic..." />
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Institution Logo</label>
                                <div class="flex items-center gap-3">
                                    <div v-if="editing?.institution_logo" class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200 dark:border-white/10 flex-shrink-0">
                                        <img :src="editing.institution_logo" class="w-full h-full object-cover" />
                                    </div>
                                    <input type="file" accept="image/*" @change="e => form.institution_logo = e.target.files[0]" class="form-input text-sm flex-1" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Sort Order</label>
                                    <input v-model.number="form.sort_order" type="number" min="0" class="form-input" />
                                </div>
                                <div class="flex items-center gap-2 mt-5">
                                    <input v-model="form.is_active" type="checkbox" id="edu_active" class="w-4 h-4 rounded accent-primary-600" />
                                    <label for="edu_active" class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer">Active / Visible</label>
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

const props     = defineProps({ educations: { type: Array, default: () => [] } })
const showModal = ref(false)
const editing   = ref(null)
const saving    = ref(false)

const emptyForm = () => ({
    institution: '', degree: '', field_of_study: '', location: '',
    start_year: new Date().getFullYear(), end_year: null,
    is_current: false, result_gpa: '', description: '',
    is_active: true, sort_order: 0,
    institution_logo: null,
})

const form = ref(emptyForm())

function openModal(edu = null) {
    editing.value   = edu
    form.value      = edu ? { ...edu, institution_logo: null } : emptyForm()
    showModal.value = true
}

function save() {
    saving.value = true

    if (form.value.institution_logo) {
        // Has a new file — use FormData
        const fd = new FormData()
        Object.entries(form.value).forEach(([k, v]) => {
            if (v === null || v === undefined) return
            if (typeof v === 'boolean') fd.append(k, v ? '1' : '0')
            else fd.append(k, v)
        })
        const url = editing.value ? `/admin/educations/${editing.value.id}` : '/admin/educations'
        if (editing.value) fd.append('_method', 'PUT')
        router.post(url, fd, {
            forceFormData: true,
            onSuccess: () => { showModal.value = false },
            onFinish:  () => { saving.value = false },
        })
    } else {
        const url    = editing.value ? `/admin/educations/${editing.value.id}` : '/admin/educations'
        const method = editing.value ? 'put' : 'post'
        router[method](url, form.value, {
            onSuccess: () => { showModal.value = false },
            onFinish:  () => { saving.value = false },
        })
    }
}

function deleteEdu(edu) {
    if (confirm(`Delete "${edu.degree} at ${edu.institution}"?`)) {
        router.delete(`/admin/educations/${edu.id}`)
    }
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.25s ease; }
.modal-enter-from, .modal-leave-to       { opacity: 0; }
</style>