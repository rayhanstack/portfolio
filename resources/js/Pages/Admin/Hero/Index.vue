<!-- Hero/Index.vue -->
<template>
    <AdminLayout>
        <Head title="Hero Section" />
        <div class="max-w-3xl">
            <div class="mb-6">
                <h2 class="font-display text-xl font-bold text-gray-900 dark:text-white">Hero Section</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage the main landing section content</p>
            </div>

            <div class="space-y-6">
                <div class="glass-card p-6">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-5">Personal Info</h3>
                    <div class="space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Your Name *</label>
                                <input v-model="form.name" type="text" placeholder="John Doe" class="form-input" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Primary Designation</label>
                                <input v-model="form.designation" type="text" placeholder="Full Stack Developer" class="form-input" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Typing Designations <span class="text-xs text-gray-400 font-normal">(for animated typing effect — one per line)</span>
                            </label>
                            <textarea
                                :value="(form.designations || []).join('\n')"
                                @input="form.designations = $event.target.value.split('\n').filter(d => d.trim())"
                                rows="4"
                                class="form-input resize-none font-mono text-sm"
                                placeholder="Full Stack Developer&#10;Laravel Expert&#10;Vue.js Enthusiast&#10;Problem Solver"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tagline / Bio Snippet</label>
                            <textarea v-model="form.tagline" rows="3" class="form-input resize-none" placeholder="I craft elegant, scalable web applications..." />
                        </div>
                    </div>
                </div>

                <div class="glass-card p-6">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-5">Media & Stats</h3>
                    <div class="space-y-5">
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Profile Photo</label>
                                <div class="flex items-start gap-3">
                                    <div v-if="hero?.profile_image" class="w-16 h-16 rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 flex-shrink-0">
                                        <img :src="hero.profile_image" class="w-full h-full object-cover" />
                                    </div>
                                    <input type="file" accept="image/*" @change="e => form.profile_image = e.target.files[0]" class="form-input text-sm" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Resume / CV File</label>
                                <div class="space-y-2">
                                    <a v-if="hero?.resume_url" :href="hero.resume_url" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-primary-600 dark:text-primary-400 hover:underline">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        Current resume
                                    </a>
                                    <input type="file" accept=".pdf,.doc,.docx" @change="e => form.resume = e.target.files[0]" class="form-input text-sm" />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Years Experience</label>
                                <input v-model.number="form.years_experience" type="number" min="0" class="form-input" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Projects Done</label>
                                <input v-model.number="form.projects_count" type="number" min="0" class="form-input" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Happy Clients</label>
                                <input v-model.number="form.clients_count" type="number" min="0" class="form-input" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button @click="save" :disabled="saving" class="btn-primary disabled:opacity-50">
                        {{ saving ? 'Saving...' : 'Save Hero Section' }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ hero: { type: Object, default: null } })
const saving = ref(false)
const form   = ref({
    name:             props.hero?.name             || '',
    designation:      props.hero?.designation      || '',
    designations:     props.hero?.designations     || [],
    tagline:          props.hero?.tagline          || '',
    years_experience: props.hero?.years_experience || 0,
    projects_count:   props.hero?.projects_count   || 0,
    clients_count:    props.hero?.clients_count    || 0,
    profile_image:    null,
    resume:           null,
})

function save() {
    saving.value = true
    const data   = new FormData()
    Object.entries(form.value).forEach(([k, v]) => {
        if (v === null || v === undefined) return
        if (Array.isArray(v)) v.forEach(item => data.append(`${k}[]`, item))
        else data.append(k, v)
    })
    router.post('/admin/hero', data, {
        forceFormData: true,
        onFinish: () => { saving.value = false },
    })
}
</script>
