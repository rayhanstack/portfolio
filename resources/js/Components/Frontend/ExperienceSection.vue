<template>
    <section id="experience" class="section-padding">
        <div class="container-max">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="badge mb-3">My Journey</span>
                <h2 class="section-title mb-4">Work <span class="gradient-text">Experience</span></h2>
                <p class="section-subtitle max-w-xl mx-auto">My professional journey and the companies I've had the privilege to work with.</p>
            </div>

            <div class="max-w-3xl mx-auto relative">
                <!-- Vertical line -->
                <div class="absolute left-8 top-0 bottom-0 w-px bg-gradient-to-b from-primary-500 via-primary-300 to-transparent hidden sm:block" />

                <div class="space-y-8">
                    <div
                        v-for="(exp, i) in experiences"
                        :key="exp.id"
                        class="relative pl-0 sm:pl-20"
                        data-aos="fade-up"
                        :data-aos-delay="i * 100"
                    >
                        <!-- Dot (desktop) -->
                        <div class="hidden sm:flex absolute left-5 top-5 -translate-x-1/2 w-6 h-6 rounded-full bg-primary-600 border-4 border-white dark:border-surface-900 shadow-glow z-10 items-center justify-center">
                            <div class="w-2 h-2 rounded-full bg-white" />
                        </div>

                        <div class="glass-card p-6 hover:border-primary-500/20 transition-all duration-300">
                            <div class="flex items-start justify-between gap-4 flex-wrap mb-4">
                                <div>
                                    <h3 class="font-display font-bold text-gray-900 dark:text-white text-lg">{{ exp.position }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-primary-600 dark:text-primary-400 font-medium text-sm">{{ exp.company }}</span>
                                        <span v-if="exp.location" class="text-gray-400 text-xs">· {{ exp.location }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <span class="px-3 py-1 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300 text-xs rounded-full border border-primary-100 dark:border-primary-800/30 whitespace-nowrap">
                                        {{ exp.start_date }} — {{ exp.end_date }}
                                    </span>
                                    <span v-if="exp.is_current" class="flex items-center gap-1 text-green-600 dark:text-green-400 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse" />
                                        Current
                                    </span>
                                </div>
                            </div>

                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-4">{{ exp.description }}</p>

                            <!-- Responsibilities -->
                            <ul v-if="exp.responsibilities?.length" class="space-y-1.5 mb-4">
                                <li v-for="r in exp.responsibilities" :key="r" class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 text-primary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ r }}
                                </li>
                            </ul>

                            <!-- Tech tags -->
                            <div v-if="exp.technologies?.length" class="flex flex-wrap gap-1.5">
                                <span v-for="tech in exp.technologies" :key="tech" class="badge text-xs">{{ tech }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
defineProps({ experiences: { type: Array, default: () => [] } })
</script>
