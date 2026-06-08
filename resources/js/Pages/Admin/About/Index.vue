<!-- About/Index.vue -->
<template>
    <AdminLayout>
        <Head title="About Me" />
        <div class="max-w-3xl">
            <div class="mb-6">
                <h2 class="font-display text-xl font-bold text-gray-900 dark:text-white">About Me</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage your personal profile and bio</p>
            </div>

            <!-- Flash success message -->
            <div
                v-if="$page.props.flash?.success"
                class="mb-4 flex items-center gap-2 px-4 py-3 rounded-xl bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 text-green-700 dark:text-green-400 text-sm"
            >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ $page.props.flash.success }}
            </div>

            <div class="space-y-6">

                <!-- Profile -->
                <div class="glass-card p-6">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-5">Profile</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div v-if="about?.profile_image" class="w-20 h-20 rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 flex-shrink-0">
                                <img :src="about.profile_image" class="w-full h-full object-cover" />
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Profile Photo</label>
                                <input type="file" accept="image/*" @change="e => form.profile_image = e.target.files[0]" class="form-input text-sm" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Full Name *</label>
                            <input v-model="form.full_name" type="text" placeholder="John Doe" class="form-input" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Biography *</label>
                            <textarea v-model="form.bio" rows="6" class="form-input resize-none" placeholder="Write your professional bio here..." />
                        </div>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="glass-card p-6">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-5">Personal Details</h3>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                            <input v-model="form.email" type="email" class="form-input" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Phone</label>
                            <input v-model="form.phone" type="text" class="form-input" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Location</label>
                            <input v-model="form.location" type="text" class="form-input" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nationality</label>
                            <input v-model="form.nationality" type="text" class="form-input" />
                        </div>
                        <!-- date_of_birth was missing from the form entirely -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Date of Birth</label>
                            <input v-model="form.date_of_birth" type="text" placeholder="e.g. Jan 1, 1990" class="form-input" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Languages</label>
                            <input v-model="form.languages" type="text" class="form-input" placeholder="English, Bengali" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Freelance Status</label>
                            <select v-model="form.freelance_status" class="form-input">
                                <option>Available</option>
                                <option>Busy</option>
                                <option>Not Available</option>
                            </select>
                        </div>
                        <!-- is_active toggle was missing -->
                        <div class="flex items-center gap-3 pt-5">
                            <button
                                type="button"
                                @click="form.is_active = !form.is_active"
                                :class="[
                                    'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none',
                                    form.is_active ? 'bg-primary-600' : 'bg-gray-200 dark:bg-white/10'
                                ]"
                            >
                                <span
                                    :class="[
                                        'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                        form.is_active ? 'translate-x-5' : 'translate-x-0'
                                    ]"
                                />
                            </button>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Section Active
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Achievement Counters -->
                <div class="glass-card p-6">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-2">Achievement Counters</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Animated counters shown in the About section</p>
                    <div class="space-y-3">
                        <div
                            v-for="(counter, i) in form.counters"
                            :key="i"
                            class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-white/5 rounded-xl"
                        >
                            <input v-model="form.counters[i].label"        type="text"   placeholder="Label"  class="form-input flex-1 text-sm" />
                            <input v-model.number="form.counters[i].value" type="number" placeholder="Value"  class="form-input w-24 text-sm" />
                            <input v-model="form.counters[i].suffix"       type="text"   placeholder="+"      class="form-input w-16 text-sm" />
                            <button @click="removeCounter(i)" class="text-red-400 hover:text-red-600 transition-colors p-1 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <button @click="addCounter" class="btn-ghost text-sm w-full justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Counter
                        </button>
                    </div>
                </div>

                <!-- Resume -->
                <div class="glass-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-display font-semibold text-gray-900 dark:text-white">Resume / CV</h3>
                        <a v-if="about?.resume_file" :href="about.resume_file" target="_blank" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">
                            View current
                        </a>
                    </div>
                    <input type="file" accept=".pdf,.doc,.docx" @change="e => form.resume_file = e.target.files[0]" class="form-input text-sm" />
                </div>

                <div class="flex justify-end">
                    <button @click="save" :disabled="saving" class="btn-primary disabled:opacity-50">
                        {{ saving ? 'Saving...' : 'Save About Section' }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router, Head } from '@inertiajs/vue3'   // Head was not imported
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props  = defineProps({ about: { type: Object, default: null } })
const saving = ref(false)

const form = ref({
    full_name:        props.about?.full_name        || '',
    bio:              props.about?.bio              || '',
    email:            props.about?.email            || '',
    phone:            props.about?.phone            || '',
    location:         props.about?.location         || '',
    nationality:      props.about?.nationality      || '',
    date_of_birth:    props.about?.date_of_birth    || '',   // was missing
    languages:        props.about?.languages        || '',
    freelance_status: props.about?.freelance_status || 'Available',
    is_active:        props.about?.is_active        ?? true, // was missing
    counters:         props.about?.counters         || [{ label: '', value: 0, suffix: '+' }],
    profile_image:    null,
    resume_file:      null,
})

function addCounter()     { form.value.counters.push({ label: '', value: 0, suffix: '+' }) }
function removeCounter(i) { form.value.counters.splice(i, 1) }

function save() {
    saving.value = true
    const data   = new FormData()
    Object.entries(form.value).forEach(([k, v]) => {
        if (v === null || v === undefined) return
        if (k === 'counters') {
            // Send as JSON string; controller decodes it back to array
            data.append(k, JSON.stringify(v))
        } else if (typeof v === 'boolean') {
            data.append(k, v ? '1' : '0')
        } else {
            data.append(k, v)
        }
    })
    router.post('/admin/about', data, {
        forceFormData: true,
        onFinish: () => { saving.value = false },
    })
}
</script>