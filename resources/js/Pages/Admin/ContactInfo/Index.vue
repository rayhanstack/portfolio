<template>
    <AdminLayout>
        <Head title="Contact Info" />
        <div class="max-w-2xl">
            <div class="mb-6">
                <h2 class="font-display text-xl font-bold text-gray-900 dark:text-white">Contact Information</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Displayed on the contact section of your portfolio</p>
            </div>
            <div class="glass-card p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email Address</label>
                        <input v-model="form.email" type="email" placeholder="hello@example.com" class="form-input" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Phone Number</label>
                        <input v-model="form.phone" type="text" placeholder="+1 (555) 000-0000" class="form-input" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Address</label>
                        <textarea v-model="form.address" rows="2" class="form-input resize-none" placeholder="123 Main St, City, Country" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Google Maps Embed URL</label>
                        <input v-model="form.map_embed_url" type="url" class="form-input" placeholder="https://www.google.com/maps/embed?pb=..." />
                        <p class="mt-1 text-xs text-gray-400">Get from Google Maps → Share → Embed a map → copy src URL</p>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button @click="save" :disabled="saving" class="btn-primary disabled:opacity-50">
                        {{ saving ? 'Saving...' : 'Save Contact Info' }}
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

const props = defineProps({ contactInfo: { type: Object, default: null } })
const saving = ref(false)
const form   = ref({
    email:         props.contactInfo?.email         || '',
    phone:         props.contactInfo?.phone         || '',
    address:       props.contactInfo?.address       || '',
    map_embed_url: props.contactInfo?.map_embed_url || '',
})

function save() {
    saving.value = true
    router.post('/admin/contact-info', form.value, {
        onFinish: () => { saving.value = false },
    })
}
</script>
