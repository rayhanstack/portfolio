<template>
    <AdminLayout>
        <Head title="Projects" />

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-display text-xl font-bold text-gray-900 dark:text-white">Projects</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ projects.length }} total projects</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="showCatModal = true" class="btn-ghost text-sm">
                    Manage Categories
                </button>
                <Link href="/admin/projects/create" class="btn-primary text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Project
                </Link>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div
                v-for="project in projects"
                :key="project.id"
                class="glass-card overflow-hidden group"
            >
                <!-- Thumbnail -->
                <div class="relative h-44 bg-gray-100 dark:bg-white/5">
                    <img
                        v-if="project.thumbnail"
                        :src="project.thumbnail"
                        :alt="project.title"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center text-5xl">{{ project.emoji || '🚀' }}</div>

                    <!-- Badges -->
                    <div class="absolute top-3 left-3 flex gap-2">
                        <span v-if="project.is_featured" class="px-2 py-1 bg-amber-500/90 backdrop-blur-sm text-black text-xs font-bold rounded-lg">⭐ Featured</span>
                        <span
                            :class="[
                                'px-2 py-1 backdrop-blur-sm text-xs font-medium rounded-lg',
                                project.is_active ? 'bg-green-500/90 text-white' : 'bg-gray-500/90 text-white'
                            ]"
                        >{{ project.is_active ? 'Active' : 'Hidden' }}</span>
                    </div>
                </div>

                <!-- Info -->
                <div class="p-5">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-1 truncate">{{ project.title }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-3">{{ project.description }}</p>

                    <div v-if="project.category" class="mb-3">
                        <span class="badge text-xs">{{ project.category.name }}</span>
                    </div>

                    <!-- Tech tags -->
                    <div class="flex flex-wrap gap-1 mb-4">
                        <span v-for="tech in (project.technologies || []).slice(0, 3)" :key="tech"
                            class="px-2 py-0.5 bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 text-xs rounded-full">
                            {{ tech }}
                        </span>
                        <span v-if="(project.technologies || []).length > 3"
                            class="px-2 py-0.5 bg-gray-100 dark:bg-white/5 text-gray-500 text-xs rounded-full">
                            +{{ project.technologies.length - 3 }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 pt-4 border-t border-gray-100 dark:border-white/5">
                        <Link
                            :href="`/admin/projects/${project.id}/edit`"
                            class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 hover:bg-primary-100 dark:hover:bg-primary-900/30 text-xs font-medium transition-colors"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </Link>
                        <a v-if="project.live_url" :href="project.live_url" target="_blank"
                            class="flex items-center justify-center w-9 h-9 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                        <button @click="deleteProject(project)"
                            class="flex items-center justify-center w-9 h-9 rounded-xl bg-red-50 dark:bg-red-900/10 text-red-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/20 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div v-if="!projects.length" class="col-span-full glass-card p-16 text-center">
                <div class="text-5xl mb-4">🚀</div>
                <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-2">No projects yet</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Create your first project to showcase your work.</p>
                <Link href="/admin/projects/create" class="btn-primary text-sm">Add First Project</Link>
            </div>
        </div>

        <!-- Category Management Modal -->
        <Teleport to="body">
            <Transition name="modal">
                <div v-if="showCatModal"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                    @click.self="showCatModal = false">
                    <div class="glass-card w-full max-w-md p-6">
                        <h3 class="font-display font-bold text-gray-900 dark:text-white mb-5">Project Categories</h3>

                        <!-- Existing categories -->
                        <div class="space-y-2 mb-5 max-h-60 overflow-y-auto">
                            <div v-for="cat in categories" :key="cat.id"
                                class="flex items-center justify-between p-3 bg-gray-50 dark:bg-white/5 rounded-xl">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full" :style="{ background: cat.color || '#4466f3' }" />
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ cat.name }}</span>
                                    <span class="text-xs text-gray-400">({{ cat.projects_count || 0 }} projects)</span>
                                </div>
                                <button @click="deleteCategory(cat)" class="text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div v-if="!categories.length" class="text-center py-4 text-gray-500 dark:text-gray-400 text-sm">
                                No categories yet
                            </div>
                        </div>

                        <!-- Add category form -->
                        <div class="flex gap-2 border-t border-gray-100 dark:border-white/5 pt-4">
                            <input v-model="newCatName" type="text" placeholder="Category name" class="form-input flex-1" @keyup.enter="addCategory" />
                            <input v-model="newCatColor" type="color" class="w-11 h-11 rounded-xl cursor-pointer border border-gray-200 dark:border-white/10 p-1" />
                            <button @click="addCategory" class="btn-primary text-sm px-4">Add</button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    projects:   { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
})

const showCatModal = ref(false)
const newCatName   = ref('')
const newCatColor  = ref('#4466f3')

function deleteProject(project) {
    if (confirm(`Delete "${project.title}"? This cannot be undone.`)) {
        router.delete(`/admin/projects/${project.id}`)
    }
}

function addCategory() {
    if (!newCatName.value.trim()) return
    router.post('/admin/project-categories', {
        name:  newCatName.value,
        color: newCatColor.value,
    }, {
        onSuccess: () => { newCatName.value = '' },
    })
}

function deleteCategory(cat) {
    if (confirm(`Delete category "${cat.name}"?`)) {
        router.delete(`/admin/project-categories/${cat.id}`)
    }
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.25s ease; }
.modal-enter-from, .modal-leave-to       { opacity: 0; }
</style>
