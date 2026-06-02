<!-- AboutSection.vue -->
<template>
    <section id="about" class="section-padding">
        <div class="container-max">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Image -->
                <div class="relative" data-aos="fade-right">
                    <div class="relative w-full max-w-md mx-auto">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/20 to-accent-purple/20 rounded-3xl blur-2xl" />
                        <div class="relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl aspect-square">
                            <img
                                v-if="about?.profile_image"
                                :src="about.profile_image"
                                :alt="about?.full_name"
                                class="w-full h-full object-cover"
                            />
                            <div v-else class="w-full h-full bg-gradient-to-br from-primary-500 to-accent-purple flex items-center justify-center text-8xl">👤</div>
                        </div>
                        <!-- Experience badge -->
                        <div class="absolute -bottom-5 -right-5 glass-card p-4 rounded-2xl border border-primary-500/20 shadow-glow">
                            <div class="text-3xl font-display font-bold text-primary-600 dark:text-primary-400">5+</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">Years Experience</div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div data-aos="fade-left">
                    <span class="badge mb-3">About Me</span>
                    <h2 class="section-title mb-5">
                        Know More <span class="gradient-text">About Me</span>
                    </h2>
                    <p class="section-subtitle mb-8 whitespace-pre-line">{{ about?.bio }}</p>

                    <!-- Info grid -->
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div v-for="item in infoItems" :key="item.label" class="flex flex-col gap-0.5">
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ item.label }}</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ item.value || '—' }}</span>
                        </div>
                    </div>

                    <!-- Download CV -->
                    <a v-if="about?.resume_file" :href="about.resume_file" class="btn-primary" download>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download CV
                    </a>

                    <!-- Counters -->
                    <div v-if="about?.counters?.length" class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-10 pt-8 border-t border-gray-100 dark:border-white/5">
                        <div v-for="counter in about.counters" :key="counter.label" class="text-center">
                            <div class="text-3xl font-display font-bold gradient-text">{{ counter.value }}{{ counter.suffix }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ counter.label }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { computed } from 'vue'
const props = defineProps({ about: { type: Object, default: null } })
const infoItems = computed(() => props.about ? [
    { label: 'Name',      value: props.about.full_name     },
    { label: 'Email',     value: props.about.email         },
    { label: 'Phone',     value: props.about.phone         },
    { label: 'Location',  value: props.about.location      },
    { label: 'Languages', value: props.about.languages     },
    { label: 'Available', value: props.about.freelance_status },
] : [])
</script>
