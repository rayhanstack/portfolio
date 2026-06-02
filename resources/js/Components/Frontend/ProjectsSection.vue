<template>
    <section id="projects" class="section-padding">
        <div class="container-max">
            <!-- Header -->
            <div class="text-center mb-12" data-aos="fade-up">
                <span class="badge mb-3">My Work</span>
                <h2 class="section-title mb-4">Featured <span class="gradient-text">Projects</span></h2>
                <p class="section-subtitle max-w-xl mx-auto">
                    A showcase of projects I've built — from concept to production.
                </p>
            </div>

            <!-- Category Filter -->
            <div class="flex flex-wrap justify-center gap-3 mb-10" data-aos="fade-up" data-aos-delay="100">
                <button
                    @click="activeCategory = null"
                    :class="filterBtnClass(null)"
                >All</button>
                <button
                    v-for="cat in categories"
                    :key="cat.id"
                    @click="activeCategory = cat.id"
                    :class="filterBtnClass(cat.id)"
                >
                    {{ cat.name }}
                </button>
            </div>

            <!-- Projects Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <TransitionGroup name="project-grid">
                    <div
                        v-for="(project, i) in filteredProjects"
                        :key="project.id"
                        class="project-card"
                        @click="openProject(project)"
                        data-aos="fade-up"
                        :data-aos-delay="(i % 6) * 80"
                    >
                        <!-- Thumbnail -->
                        <div class="relative h-52 overflow-hidden bg-gray-100 dark:bg-white/5">
                            <img
                                v-if="project.thumbnail"
                                :src="project.thumbnail"
                                :alt="project.title"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-6xl">
                                {{ project.emoji || '🚀' }}
                            </div>

                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                <div class="flex gap-2">
                                    <a
                                        v-if="project.live_url"
                                        :href="project.live_url"
                                        target="_blank"
                                        class="px-3 py-1.5 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white text-xs font-medium hover:bg-white/30 transition-colors"
                                        @click.stop
                                    >Live Demo</a>
                                    <a
                                        v-if="project.github_url"
                                        :href="project.github_url"
                                        target="_blank"
                                        class="px-3 py-1.5 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white text-xs font-medium hover:bg-white/30 transition-colors"
                                        @click.stop
                                    >GitHub</a>
                                </div>
                            </div>

                            <!-- Featured badge -->
                            <div v-if="project.is_featured" class="absolute top-3 right-3">
                                <span class="px-2 py-1 bg-accent-gold/90 backdrop-blur-sm text-black text-xs font-bold rounded-lg">⭐ Featured</span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-5">
                            <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                {{ project.title }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-4">
                                {{ project.description }}
                            </p>

                            <!-- Tech tags -->
                            <div class="flex flex-wrap gap-1.5">
                                <span
                                    v-for="tech in project.technologies?.slice(0, 4)"
                                    :key="tech"
                                    class="px-2.5 py-0.5 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300 text-xs rounded-full border border-primary-100 dark:border-primary-800/30"
                                >
                                    {{ tech }}
                                </span>
                                <span
                                    v-if="project.technologies?.length > 4"
                                    class="px-2.5 py-0.5 bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 text-xs rounded-full"
                                >
                                    +{{ project.technologies.length - 4 }}
                                </span>
                            </div>
                        </div>
                    </div>
                </TransitionGroup>
            </div>

            <!-- Empty state -->
            <div v-if="filteredProjects.length === 0" class="text-center py-20 text-gray-500 dark:text-gray-400">
                No projects found in this category.
            </div>
        </div>

        <!-- Project Detail Modal -->
        <Teleport to="body">
            <Transition name="modal">
                <div
                    v-if="selectedProject"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                    @click.self="selectedProject = null"
                >
                    <div class="glass-card max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                        <!-- Close btn -->
                        <button
                            @click="selectedProject = null"
                            class="absolute top-4 right-4 w-8 h-8 rounded-lg bg-gray-100 dark:bg-white/10 flex items-center justify-center text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors z-10"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Images -->
                        <div class="relative h-64 bg-gray-100 dark:bg-white/5 rounded-t-2xl overflow-hidden">
                            <img
                                v-if="selectedProject.thumbnail"
                                :src="selectedProject.thumbnail"
                                :alt="selectedProject.title"
                                class="w-full h-full object-cover"
                            />
                        </div>

                        <!-- Info -->
                        <div class="p-8 relative">
                            <h3 class="font-display text-2xl font-bold text-gray-900 dark:text-white mb-3">
                                {{ selectedProject.title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                                {{ selectedProject.full_description || selectedProject.description }}
                            </p>

                            <!-- Meta -->
                            <div class="grid sm:grid-cols-2 gap-4 mb-6">
                                <div v-if="selectedProject.client">
                                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Client</div>
                                    <div class="text-gray-900 dark:text-white font-medium">{{ selectedProject.client }}</div>
                                </div>
                                <div v-if="selectedProject.duration">
                                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Duration</div>
                                    <div class="text-gray-900 dark:text-white font-medium">{{ selectedProject.duration }}</div>
                                </div>
                            </div>

                            <!-- Features -->
                            <div v-if="selectedProject.features?.length" class="mb-6">
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-3">Key Features</div>
                                <ul class="grid sm:grid-cols-2 gap-2">
                                    <li
                                        v-for="feature in selectedProject.features"
                                        :key="feature"
                                        class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300"
                                    >
                                        <svg class="w-4 h-4 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ feature }}
                                    </li>
                                </ul>
                            </div>

                            <!-- Technologies -->
                            <div class="flex flex-wrap gap-2 mb-6">
                                <span
                                    v-for="tech in selectedProject.technologies"
                                    :key="tech"
                                    class="badge"
                                >{{ tech }}</span>
                            </div>

                            <!-- Links -->
                            <div class="flex gap-3">
                                <a
                                    v-if="selectedProject.live_url"
                                    :href="selectedProject.live_url"
                                    target="_blank"
                                    class="btn-primary text-sm"
                                >
                                    Live Demo
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                                <a
                                    v-if="selectedProject.github_url"
                                    :href="selectedProject.github_url"
                                    target="_blank"
                                    class="btn-ghost text-sm"
                                >
                                    View Code
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </section>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    projects:   { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
})

const activeCategory  = ref(null)
const selectedProject = ref(null)

const filteredProjects = computed(() =>
    activeCategory.value
        ? props.projects.filter(p => p.category_id === activeCategory.value)
        : props.projects
)

function filterBtnClass(catId) {
    const isActive = activeCategory.value === catId
    return [
        'px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200',
        isActive
            ? 'bg-primary-600 text-white shadow-glow'
            : 'glass-card text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400',
    ]
}

function openProject(project) {
    selectedProject.value = project
}
</script>

<style scoped>
.project-grid-move        { transition: all 0.4s ease; }
.project-grid-enter-active { transition: all 0.3s ease; }
.project-grid-leave-active { transition: all 0.3s ease; position: absolute; }
.project-grid-enter-from, .project-grid-leave-to { opacity: 0; transform: scale(0.95); }

.modal-enter-active, .modal-leave-active { transition: all 0.25s ease; }
.modal-enter-from, .modal-leave-to       { opacity: 0; transform: scale(0.96); }
</style>
