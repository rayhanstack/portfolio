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

                        <!-- ── Typing Designations tag-input ── -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Typing Designations
                                <span class="text-xs text-gray-400 font-normal">(animated typing effect — press Enter or comma to add)</span>
                            </label>

                            <!-- Tag display + input wrapper -->
                            <div
                                class="form-input min-h-[44px] flex flex-wrap gap-2 cursor-text p-2"
                                @click="focusTagInput"
                            >
                                <!-- Existing tags -->
                                <span
                                    v-for="(tag, i) in form.designations"
                                    :key="i"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-sm font-medium
                                           bg-primary-100 text-primary-700 dark:bg-primary-500/20 dark:text-primary-300
                                           border border-primary-200 dark:border-primary-500/30"
                                >
                                    {{ tag }}
                                    <button
                                        type="button"
                                        @click.stop="removeTag(i)"
                                        class="w-4 h-4 rounded-full flex items-center justify-center
                                               text-primary-500 hover:text-primary-700 hover:bg-primary-200
                                               dark:hover:text-primary-100 dark:hover:bg-primary-500/30
                                               transition-colors flex-shrink-0"
                                        aria-label="Remove"
                                    >
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </span>

                                <!-- Inline text input -->
                                <input
                                    ref="tagInputRef"
                                    v-model="tagDraft"
                                    type="text"
                                    placeholder="Type and press Enter…"
                                    class="flex-1 min-w-[140px] bg-transparent outline-none text-sm
                                           text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 py-0.5"
                                    @keydown.enter.prevent="addTag"
                                    @keydown.comma.prevent="addTag"
                                    @keydown.backspace="onBackspace"
                                    @blur="addTag"
                                />
                            </div>

                            <!-- Hint row -->
                            <p class="mt-1.5 text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ form.designations.length }} designation{{ form.designations.length !== 1 ? 's' : '' }} added.
                                Click <kbd class="px-1 py-0.5 rounded bg-gray-100 dark:bg-white/10 font-mono text-[10px]">×</kbd> on a tag to remove it.
                            </p>
                        </div>
                        <!-- ── end tag-input ── -->

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
const saving     = ref(false)
const tagDraft   = ref('')
const tagInputRef = ref(null)

const form = ref({
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

function focusTagInput() {
    tagInputRef.value?.focus()
}

function addTag() {
    const value = tagDraft.value.replace(/,/g, '').trim()
    if (value && !form.value.designations.includes(value)) {
        form.value.designations.push(value)
    }
    tagDraft.value = ''
}

function removeTag(index) {
    form.value.designations.splice(index, 1)
}

// Backspace on empty input removes the last tag
function onBackspace() {
    if (tagDraft.value === '' && form.value.designations.length) {
        form.value.designations.pop()
    }
}

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