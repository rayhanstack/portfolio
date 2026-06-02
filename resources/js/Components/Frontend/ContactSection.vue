<template>
    <section id="contact" class="section-padding bg-gray-50 dark:bg-surface-950/50">
        <div class="container-max">
            <!-- Header -->
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="badge mb-3">Get In Touch</span>
                <h2 class="section-title mb-4">Let's <span class="gradient-text">Connect</span></h2>
                <p class="section-subtitle max-w-xl mx-auto">
                    Have a project in mind? Let's talk about it. I'm always open to new opportunities.
                </p>
            </div>

            <div class="grid lg:grid-cols-5 gap-10">
                <!-- Left: Info cards -->
                <div class="lg:col-span-2 flex flex-col gap-5">
                    <div
                        v-for="(item, i) in infoCards"
                        :key="item.label"
                        class="glass-card p-5 flex items-center gap-4"
                        data-aos="fade-right"
                        :data-aos-delay="i * 100"
                    >
                        <div class="w-12 h-12 rounded-xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center flex-shrink-0">
                            <component :is="item.icon" class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-0.5">{{ item.label }}</div>
                            <a
                                v-if="item.href"
                                :href="item.href"
                                class="font-medium text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
                            >
                                {{ item.value }}
                            </a>
                            <div v-else class="font-medium text-gray-900 dark:text-white">{{ item.value }}</div>
                        </div>
                    </div>

                    <!-- Social links -->
                    <div class="glass-card p-5" data-aos="fade-right" data-aos-delay="300">
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Follow me on</div>
                        <div class="flex flex-wrap gap-2">
                            <a
                                v-for="link in socialLinks"
                                :key="link.id"
                                :href="link.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="px-3 py-2 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 text-xs font-medium transition-all"
                            >
                                {{ link.platform }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right: Contact form -->
                <div class="lg:col-span-3" data-aos="fade-left" data-aos-delay="100">
                    <div class="glass-card p-8">
                        <h3 class="font-display text-xl font-bold text-gray-900 dark:text-white mb-6">Send a Message</h3>

                        <Transition name="success">
                            <div v-if="success" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl flex items-center gap-3 text-green-700 dark:text-green-400">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium">Message sent! I'll get back to you soon.</span>
                            </div>
                        </Transition>

                        <div class="space-y-4">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Your Name</label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        placeholder="John Doe"
                                        class="form-input"
                                        :class="{ 'border-red-400': errors.name }"
                                    />
                                    <p v-if="errors.name" class="mt-1 text-xs text-red-500">{{ errors.name[0] }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email Address</label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        placeholder="john@example.com"
                                        class="form-input"
                                        :class="{ 'border-red-400': errors.email }"
                                    />
                                    <p v-if="errors.email" class="mt-1 text-xs text-red-500">{{ errors.email[0] }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Subject</label>
                                <input
                                    v-model="form.subject"
                                    type="text"
                                    placeholder="Project Inquiry"
                                    class="form-input"
                                    :class="{ 'border-red-400': errors.subject }"
                                />
                                <p v-if="errors.subject" class="mt-1 text-xs text-red-500">{{ errors.subject[0] }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Message</label>
                                <textarea
                                    v-model="form.message"
                                    rows="5"
                                    placeholder="Tell me about your project..."
                                    class="form-input resize-none"
                                    :class="{ 'border-red-400': errors.message }"
                                />
                                <p v-if="errors.message" class="mt-1 text-xs text-red-500">{{ errors.message[0] }}</p>
                            </div>

                            <button
                                @click="submitForm"
                                :disabled="loading"
                                class="btn-primary w-full justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                <span>{{ loading ? 'Sending...' : 'Send Message' }}</span>
                                <svg v-if="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, computed, h } from 'vue'
import axios from 'axios'

const props = defineProps({
    contactInfo: { type: Object, default: () => ({}) },
    socialLinks: { type: Array,  default: () => [] },
})

const form = ref({ name: '', email: '', subject: '', message: '' })
const errors = ref({})
const loading = ref(false)
const success = ref(false)

// Simple icon components inline
const PhoneIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', class: 'w-5 h-5' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z' })
    ])
}

const MailIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', class: 'w-5 h-5' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' })
    ])
}

const MapPinIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', class: 'w-5 h-5' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z' }),
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M15 11a3 3 0 11-6 0 3 3 0 016 0z' })
    ])
}

const infoCards = computed(() => [
    props.contactInfo?.phone && {
        label: 'Phone',
        value: props.contactInfo.phone,
        href: `tel:${props.contactInfo.phone}`,
        icon: PhoneIcon,
    },
    props.contactInfo?.email && {
        label: 'Email',
        value: props.contactInfo.email,
        href: `mailto:${props.contactInfo.email}`,
        icon: MailIcon,
    },
    props.contactInfo?.address && {
        label: 'Location',
        value: props.contactInfo.address,
        href: null,
        icon: MapPinIcon,
    },
].filter(Boolean))

async function submitForm() {
    loading.value = true
    errors.value  = {}
    success.value = false

    try {
        await axios.post('/contact', form.value)
        success.value = true
        form.value = { name: '', email: '', subject: '', message: '' }
        setTimeout(() => { success.value = false }, 6000)
    } catch (err) {
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors
        }
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
.success-enter-active, .success-leave-active { transition: all 0.3s ease; }
.success-enter-from, .success-leave-to       { opacity: 0; transform: translateY(-8px); }
</style>
