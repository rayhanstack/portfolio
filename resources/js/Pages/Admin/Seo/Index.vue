<template>
    <AdminLayout>
        <Head title="SEO Settings" />

        <div class="max-w-3xl">
            <div class="mb-6">
                <h2 class="font-display text-xl font-bold text-gray-900 dark:text-white">SEO Settings</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage meta tags, Open Graph, and structured data</p>
            </div>

            <div class="space-y-6">
                <!-- Basic Meta -->
                <div class="glass-card p-6">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-4">Basic Meta Tags</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Meta Title <span class="text-xs text-gray-400 font-normal">(max 70 chars) — {{ form.meta_title?.length || 0 }}/70</span>
                            </label>
                            <input v-model="form.meta_title" type="text" maxlength="70" class="form-input" placeholder="John Doe | Full Stack Developer" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Meta Description <span class="text-xs text-gray-400 font-normal">(max 160 chars) — {{ form.meta_description?.length || 0 }}/160</span>
                            </label>
                            <textarea v-model="form.meta_description" rows="3" maxlength="160" class="form-input resize-none" placeholder="Experienced full stack developer specializing in..." />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Keywords</label>
                            <input v-model="form.keywords" type="text" class="form-input" placeholder="laravel, vue.js, full stack developer, web development" />
                        </div>
                    </div>
                </div>

                <!-- Open Graph -->
                <div class="glass-card p-6">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-4">Open Graph (Facebook, LinkedIn)</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">OG Title</label>
                            <input v-model="form.og_title" type="text" class="form-input" placeholder="Leave blank to use Meta Title" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">OG Description</label>
                            <textarea v-model="form.og_description" rows="2" class="form-input resize-none" placeholder="Leave blank to use Meta Description" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">OG Image (1200×630 recommended)</label>
                            <div class="flex items-start gap-4">
                                <div v-if="seo?.og_image" class="w-32 h-16 rounded-lg overflow-hidden border border-gray-200 dark:border-white/10 flex-shrink-0">
                                    <img :src="seo.og_image" class="w-full h-full object-cover" />
                                </div>
                                <input type="file" accept="image/*" @change="handleOgImage" class="form-input text-sm" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Twitter Card -->
                <div class="glass-card p-6">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-4">Twitter Card</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Card Type</label>
                            <select v-model="form.twitter_card" class="form-input">
                                <option value="summary">Summary</option>
                                <option value="summary_large_image">Summary Large Image</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Twitter Title</label>
                            <input v-model="form.twitter_title" type="text" class="form-input" placeholder="Leave blank to use Meta Title" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Twitter Description</label>
                            <textarea v-model="form.twitter_description" rows="2" class="form-input resize-none" />
                        </div>
                    </div>
                </div>

                <!-- Schema Markup -->
                <div class="glass-card p-6">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-2">Schema / Structured Data</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">JSON-LD schema markup for rich search results</p>
                    <textarea
                        v-model="form.schema_markup"
                        rows="8"
                        class="form-input resize-none font-mono text-xs"
                        placeholder='{"@context": "https://schema.org", "@type": "Person", ...}'
                    />
                </div>

                <div class="flex justify-end">
                    <button @click="save" :disabled="saving" class="btn-primary disabled:opacity-50">
                        {{ saving ? 'Saving...' : 'Save SEO Settings' }}
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

const props = defineProps({
    seo: { type: Object, default: () => ({}) },
})

const saving = ref(false)
const form   = ref({
    meta_title:          props.seo?.meta_title || '',
    meta_description:    props.seo?.meta_description || '',
    keywords:            props.seo?.keywords || '',
    og_title:            props.seo?.og_title || '',
    og_description:      props.seo?.og_description || '',
    og_image:            null,
    twitter_card:        props.seo?.twitter_card || 'summary_large_image',
    twitter_title:       props.seo?.twitter_title || '',
    twitter_description: props.seo?.twitter_description || '',
    schema_markup:       props.seo?.schema_markup || '',
})

function handleOgImage(e) {
    form.value.og_image = e.target.files[0]
}

function save() {
    saving.value = true
    const data   = new FormData()

    Object.entries(form.value).forEach(([key, val]) => {
        if (val !== null && val !== undefined) data.append(key, val)
    })

    router.post('/admin/seo', data, {
        forceFormData: true,
        onFinish: () => { saving.value = false },
    })
}
</script>
