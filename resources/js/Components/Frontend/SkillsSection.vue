<template>
    <section id="skills" class="section-padding bg-gray-50 dark:bg-surface-950/50">
        <div class="container-max">
            <!-- Header -->
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="badge mb-3">What I Know</span>
                <h2 class="section-title mb-4">My <span class="gradient-text">Skills</span></h2>
                <p class="section-subtitle max-w-xl mx-auto">
                    A collection of technologies and tools I've mastered over the years.
                </p>
            </div>

            <!-- Category Tabs -->
            <div class="flex flex-wrap justify-center gap-3 mb-12" data-aos="fade-up" data-aos-delay="100">
                <button
                    v-for="cat in categories"
                    :key="cat"
                    @click="activeCategory = cat"
                    :class="[
                        'px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200',
                        activeCategory === cat
                            ? 'bg-primary-600 text-white shadow-glow'
                            : 'glass-card text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400'
                    ]"
                >
                    {{ cat }}
                </button>
            </div>

            <!-- Skills Grid -->
            <div class="grid md:grid-cols-2 gap-6">
                <div
                    v-for="(skill, i) in filteredSkills"
                    :key="skill.id"
                    class="glass-card p-5 group hover:border-primary-500/20 transition-all duration-300"
                    data-aos="fade-up"
                    :data-aos-delay="(i % 6) * 80"
                >
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <!-- Skill Icon -->
                            <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <img
                                    v-if="skill.icon"
                                    :src="skill.icon"
                                    :alt="skill.name"
                                    class="w-6 h-6 object-contain"
                                />
                                <span v-else class="text-xl">{{ skill.emoji || '💡' }}</span>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-white text-sm">{{ skill.name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ skill.category }}</div>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-primary-600 dark:text-primary-400">
                            {{ skill.percentage }}%
                        </span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="skill-bar">
                        <div
                            class="skill-bar-fill"
                            :style="{ width: animated ? skill.percentage + '%' : '0%' }"
                        />
                    </div>
                </div>
            </div>

            <!-- Circular indicators (Top skills) -->
            <div v-if="topSkills.length" class="mt-16">
                <h3 class="text-center text-xl font-display font-bold text-gray-900 dark:text-white mb-10" data-aos="fade-up">
                    Core Competencies
                </h3>
                <div class="flex flex-wrap justify-center gap-8">
                    <div
                        v-for="(skill, i) in topSkills"
                        :key="'top-' + skill.id"
                        class="flex flex-col items-center gap-3"
                        data-aos="zoom-in"
                        :data-aos-delay="i * 100"
                    >
                        <!-- Circular progress -->
                        <div class="relative w-28 h-28">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                                <!-- Background circle -->
                                <circle
                                    cx="50" cy="50" r="40"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="6"
                                    class="text-gray-200 dark:text-white/10"
                                />
                                <!-- Progress circle -->
                                <circle
                                    cx="50" cy="50" r="40"
                                    fill="none"
                                    stroke="url(#grad)"
                                    stroke-width="6"
                                    stroke-linecap="round"
                                    :stroke-dasharray="`${animated ? (skill.percentage / 100) * 251.2 : 0} 251.2`"
                                    class="transition-all duration-1000 ease-out"
                                />
                                <defs>
                                    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#4466f3" />
                                        <stop offset="100%" stop-color="#00d4ff" />
                                    </linearGradient>
                                </defs>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-xl font-bold font-display text-gray-900 dark:text-white">{{ skill.percentage }}%</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="font-medium text-gray-900 dark:text-white text-sm">{{ skill.name }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
    skills: { type: Array, default: () => [] },
})

const activeCategory = ref('All')
const animated = ref(false)

const categories = computed(() => {
    const cats = [...new Set(props.skills.map(s => s.category).filter(Boolean))]
    return ['All', ...cats]
})

const filteredSkills = computed(() => {
    if (activeCategory.value === 'All') return props.skills
    return props.skills.filter(s => s.category === activeCategory.value)
})

const topSkills = computed(() =>
    props.skills.filter(s => s.is_featured || s.percentage >= 85).slice(0, 6)
)

onMounted(() => {
    // Trigger animation after a brief delay
    setTimeout(() => { animated.value = true }, 400)
})
</script>
