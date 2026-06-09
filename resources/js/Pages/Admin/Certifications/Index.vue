<template>
    <AdminLayout>
        <Head title="Certifications" />

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-display text-xl font-bold text-gray-900 dark:text-white">Certifications</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage your credentials and certificates</p>
            </div>
            <button @click="openModal()" class="btn-primary text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Certification
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

        <!-- Certification Grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="cert in certifications" :key="cert.id"
                class="glass-card p-5 flex flex-col gap-3 group">

                <!-- Certificate image preview -->
                <div class="relative h-32 rounded-xl overflow-hidden bg-gray-100 dark:bg-white/5 flex-shrink-0">
                    <img v-if="cert.certificate_image"
                        :src="cert.certificate_image" :alt="cert.title"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    <div v-else class="w-full h-full flex items-center justify-center text-4xl">🏆</div>
                    <div class="absolute top-2 right-2 flex gap-1">
                        <span :class="['px-2 py-0.5 rounded-full text-xs font-medium backdrop-blur-sm', cert.is_active ? 'bg-green-500/80 text-white' : 'bg-gray-500/80 text-white']">
                            {{ cert.is_active ? 'Active' : 'Hidden' }}
                        </span>
                    </div>
                </div>

                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 dark:text-white line-clamp-2 leading-snug">{{ cert.title }}</h3>
                    <div class="text-sm text-primary-600 dark:text-primary-400 mt-0.5">{{ cert.organization }}</div>
                    <div class="flex items-center gap-3 mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <span>{{ formatDate(cert.issue_date) }}</span>
                        <span v-if="cert.expiry_date" class="text-orange-500 dark:text-orange-400">Expires {{ formatDate(cert.expiry_date) }}</span>
                    </div>
                    <div v-if="cert.credential_id" class="mt-1 text-xs text-gray-400 dark:text-gray-500 font-mono truncate">
                        ID: {{ cert.credential_id }}
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2 border-t border-gray-100 dark:border-white/5">
                    <a v-if="cert.verification_url" :href="cert.verification_url" target="_blank"
                        class="text-xs text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1 flex-1 truncate">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Verify
                    </a>
                    <span v-else class="flex-1" />
                    <button @click="openModal(cert)" class="p-1.5 rounded-lg text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </button>
                    <button @click="deleteCert(cert)" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <div v-if="!certifications.length" class="glass-card p-12 text-center text-gray-500 dark:text-gray-400 text-sm">
            No certifications added yet.
        </div>

        <!-- Modal -->
        <Teleport to="body">
            <Transition name="modal">
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="showModal = false">
                    <div class="glass-card w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                        <h3 class="font-display font-bold text-gray-900 dark:text-white mb-5">
                            {{ editing ? 'Edit Certification' : 'Add Certification' }}
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Title *</label>
                                <input v-model="form.title" type="text" class="form-input" placeholder="AWS Certified Solutions Architect" />
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Issuing Organization *</label>
                                <input v-model="form.organization" type="text" class="form-input" placeholder="Amazon Web Services" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Issue Date *</label>
                                    <input v-model="form.issue_date" type="date" class="form-input" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Expiry Date</label>
                                    <input v-model="form.expiry_date" type="date" class="form-input" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Credential ID</label>
                                <input v-model="form.credential_id" type="text" class="form-input font-mono" placeholder="ABC-12345-XYZ" />
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Verification URL</label>
                                <input v-model="form.verification_url" type="url" class="form-input" placeholder="https://verify.example.com/cert/..." />
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Certificate Image</label>
                                <div class="flex items-center gap-3">
                                    <div v-if="editing?.certificate_image" class="w-16 h-12 rounded-lg overflow-hidden border border-gray-200 dark:border-white/10 flex-shrink-0">
                                        <img :src="editing.certificate_image" class="w-full h-full object-cover" />
                                    </div>
                                    <input type="file" accept="image/*" @change="e => form.certificate_image = e.target.files[0]" class="form-input text-sm flex-1" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Sort Order</label>
                                    <input v-model.number="form.sort_order" type="number" min="0" class="form-input" />
                                </div>
                                <div class="flex items-center gap-2 mt-5">
                                    <input v-model="form.is_active" type="checkbox" id="cert_active" class="w-4 h-4 rounded accent-primary-600" />
                                    <label for="cert_active" class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer">Active / Visible</label>
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

const props     = defineProps({ certifications: { type: Array, default: () => [] } })
const showModal = ref(false)
const editing   = ref(null)
const saving    = ref(false)

const emptyForm = () => ({
    title: '', organization: '', issue_date: '', expiry_date: '',
    credential_id: '', verification_url: '',
    is_active: true, sort_order: 0,
    certificate_image: null,
})

const form = ref(emptyForm())

function openModal(cert = null) {
    editing.value   = cert
    form.value      = cert ? { ...cert, certificate_image: null } : emptyForm()
    showModal.value = true
}

function save() {
    saving.value = true
    const fd     = new FormData()

    Object.entries(form.value).forEach(([k, v]) => {
        if (v === null || v === undefined) return
        if (typeof v === 'boolean') fd.append(k, v ? '1' : '0')
        else fd.append(k, v)
    })

    const url = editing.value ? `/admin/certifications/${editing.value.id}` : '/admin/certifications'
    if (editing.value) fd.append('_method', 'PUT')

    router.post(url, fd, {
        forceFormData: true,
        onSuccess: () => { showModal.value = false },
        onFinish:  () => { saving.value = false },
    })
}

function deleteCert(cert) {
    if (confirm(`Delete "${cert.title}"?`)) router.delete(`/admin/certifications/${cert.id}`)
}

function formatDate(dateStr) {
    if (!dateStr) return ''
    return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', year: 'numeric' })
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.25s ease; }
.modal-enter-from, .modal-leave-to       { opacity: 0; }
</style>