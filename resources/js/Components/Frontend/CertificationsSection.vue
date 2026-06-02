<template>
    <section id="certifications" class="section-padding bg-gray-50 dark:bg-surface-950/50">
        <div class="container-max">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="badge mb-3">Credentials</span>
                <h2 class="section-title mb-4">My <span class="gradient-text">Certifications</span></h2>
                <p class="section-subtitle max-w-xl mx-auto">Industry-recognized certifications that validate my expertise.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="(cert, i) in certifications"
                    :key="cert.id"
                    class="glass-card p-6 group hover:-translate-y-1 hover:border-primary-500/20 transition-all duration-300"
                    data-aos="fade-up"
                    :data-aos-delay="(i % 6) * 80"
                >
                    <!-- Certificate image -->
                    <div v-if="cert.certificate_image" class="relative h-40 rounded-xl overflow-hidden mb-5 bg-gray-100 dark:bg-white/5">
                        <img :src="cert.certificate_image" :alt="cert.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent" />
                    </div>
                    <div v-else class="h-24 rounded-xl bg-gradient-to-br from-primary-500/10 to-accent-purple/10 flex items-center justify-center mb-5 text-4xl">🏆</div>

                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-1">{{ cert.title }}</h3>
                    <div class="text-sm text-primary-600 dark:text-primary-400 font-medium mb-3">{{ cert.organization }}</div>

                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>Issued: {{ formatDate(cert.issue_date) }}</span>
                        <span v-if="cert.expiry_date">Expires: {{ formatDate(cert.expiry_date) }}</span>
                    </div>

                    <div v-if="cert.credential_id" class="mt-2 text-xs text-gray-400 dark:text-gray-500 font-mono truncate">
                        ID: {{ cert.credential_id }}
                    </div>

                    <a
                        v-if="cert.verification_url"
                        :href="cert.verification_url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-4 inline-flex items-center gap-1.5 text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Verify Credential
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
defineProps({ certifications: { type: Array, default: () => [] } })

function formatDate(dateStr) {
    if (!dateStr) return ''
    return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', year: 'numeric' })
}
</script>
