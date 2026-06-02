<template>
    <section id="education" class="section-padding bg-gray-50 dark:bg-surface-950/50">
        <div class="container-max">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="badge mb-3">Academic Background</span>
                <h2 class="section-title mb-4">My <span class="gradient-text">Education</span></h2>
                <p class="section-subtitle max-w-xl mx-auto">The academic foundations that shaped my technical expertise.</p>
            </div>

            <div class="max-w-3xl mx-auto space-y-6">
                <div
                    v-for="(edu, i) in educations"
                    :key="edu.id"
                    class="glass-card p-6 hover:border-primary-500/20 transition-all duration-300"
                    data-aos="fade-up"
                    :data-aos-delay="i * 100"
                >
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-2xl flex-shrink-0">🎓</div>
                            <div>
                                <h3 class="font-display font-bold text-gray-900 dark:text-white">{{ edu.degree }}</h3>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ edu.field_of_study }}</div>
                                <div class="text-primary-600 dark:text-primary-400 font-medium text-sm mt-1">{{ edu.institution }}</div>
                                <div v-if="edu.location" class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">📍 {{ edu.location }}</div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <span class="badge whitespace-nowrap">
                                {{ edu.start_year }} — {{ edu.is_current ? 'Present' : edu.end_year }}
                            </span>
                            <span v-if="edu.result_gpa" class="text-xs font-semibold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-2.5 py-1 rounded-full">
                                GPA: {{ edu.result_gpa }}
                            </span>
                        </div>
                    </div>
                    <p v-if="edu.description" class="mt-4 text-sm text-gray-600 dark:text-gray-400 leading-relaxed border-t border-gray-100 dark:border-white/5 pt-4">
                        {{ edu.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
<script setup>
defineProps({ educations: { type: Array, default: () => [] } })
</script>
