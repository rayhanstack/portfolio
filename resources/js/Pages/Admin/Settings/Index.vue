<template>
    <AdminLayout>
        <Head title="Site Settings" />

        <div class="max-w-2xl">
            <div class="mb-6">
                <h2 class="font-display text-xl font-bold text-gray-900 dark:text-white">Site Settings</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Global site configuration and branding</p>
            </div>

            <div class="space-y-6">
                <div class="glass-card p-6">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-5">Branding</h3>
                    <div class="space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Site Name</label>
                                <input v-model="form.site_name" type="text" placeholder="My Portfolio" class="form-input" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Logo Text (1–4 chars)</label>
                                <input v-model="form.logo_text" type="text" maxlength="4" placeholder="JD" class="form-input" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Logo Image</label>
                            <div class="flex items-start gap-3">
                                <div v-if="logoUrl" class="w-12 h-12 rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 flex-shrink-0">
                                    <img :src="logoUrl" class="w-full h-full object-contain" />
                                </div>
                                <input type="file" accept="image/*" @change="e => form.logo = e.target.files[0]" class="form-input text-sm" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Favicon (.ico)</label>
                            <input type="file" accept=".ico,image/*" @change="e => form.favicon = e.target.files[0]" class="form-input text-sm" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Footer Text</label>
                            <input v-model="form.footer_text" type="text" placeholder="© 2025 Portfolio. All rights reserved." class="form-input" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button @click="save" :disabled="saving" class="btn-primary disabled:opacity-50">
                        {{ saving ? 'Saving...' : 'Save Settings' }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
})

const saving = ref(false)
const logoUrl = computed(() => props.settings?.logo_url?.value || null)

const form = ref({
    site_name:   props.settings?.site_name?.value   || '',
    logo_text:   props.settings?.logo_text?.value   || '',
    footer_text: props.settings?.footer_text?.value || '',
    logo:        null,
    favicon:     null,
})

function save() {
    saving.value = true
    const data   = new FormData()
    Object.entries(form.value).forEach(([k, v]) => {
        if (v !== null && v !== undefined && v !== '') data.append(k, v)
    })
    router.post('/admin/settings', data, {
        forceFormData: true,
        onFinish: () => { saving.value = false },
    })
}
</script>
