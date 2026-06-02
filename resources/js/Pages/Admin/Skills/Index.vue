<template>
    <AdminLayout>
        <Head title="Skills" />

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-display text-xl font-bold text-gray-900 dark:text-white">Skills</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage technical and soft skills</p>
            </div>
            <button @click="openModal()" class="btn-primary text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Skill
            </button>
        </div>

        <!-- Skills Table -->
        <div class="glass-card overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5">
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Skill</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide hidden sm:table-cell">Category</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Progress</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide hidden md:table-cell">Featured</th>
                        <th class="px-5 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                    <tr
                        v-for="skill in skills"
                        :key="skill.id"
                        class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors"
                    >
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-base flex-shrink-0">
                                    {{ skill.emoji || '💡' }}
                                </div>
                                <span class="font-medium text-gray-900 dark:text-white text-sm">{{ skill.name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 hidden sm:table-cell">
                            <span class="badge text-xs">{{ skill.category }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3 min-w-[120px]">
                                <div class="flex-1 skill-bar">
                                    <div class="skill-bar-fill" :style="{ width: skill.percentage + '%' }" />
                                </div>
                                <span class="text-xs font-bold text-primary-600 dark:text-primary-400 w-8 text-right">{{ skill.percentage }}%</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 hidden md:table-cell">
                            <span
                                :class="[
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    skill.is_featured
                                        ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                                        : 'bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400'
                                ]"
                            >
                                {{ skill.is_featured ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <button
                                    @click="openModal(skill)"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button
                                    @click="deleteSkill(skill)"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!skills.length">
                        <td colspan="5" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400 text-sm">
                            No skills yet. Add your first skill!
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <Teleport to="body">
            <Transition name="modal">
                <div
                    v-if="showModal"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                    @click.self="showModal = false"
                >
                    <div class="glass-card w-full max-w-md p-6 relative">
                        <h3 class="font-display font-bold text-gray-900 dark:text-white mb-5">
                            {{ editing ? 'Edit Skill' : 'Add Skill' }}
                        </h3>

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Skill Name *</label>
                                    <input v-model="form.name" type="text" placeholder="e.g. Laravel" class="form-input" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Category *</label>
                                    <input v-model="form.category" type="text" placeholder="e.g. Backend" class="form-input" list="categories" />
                                    <datalist id="categories">
                                        <option v-for="cat in existingCategories" :key="cat" :value="cat" />
                                    </datalist>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                                    Proficiency: {{ form.percentage }}%
                                </label>
                                <input
                                    v-model.number="form.percentage"
                                    type="range" min="1" max="100"
                                    class="w-full accent-primary-600"
                                />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Emoji</label>
                                    <input v-model="form.emoji" type="text" placeholder="💡" class="form-input" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Sort Order</label>
                                    <input v-model.number="form.sort_order" type="number" class="form-input" />
                                </div>
                            </div>

                            <label class="flex items-center gap-3 cursor-pointer">
                                <input v-model="form.is_featured" type="checkbox" class="w-4 h-4 rounded accent-primary-600" />
                                <span class="text-sm text-gray-700 dark:text-gray-300">Mark as featured (shown in circular indicators)</span>
                            </label>
                        </div>

                        <div class="flex gap-3 mt-6">
                            <button @click="showModal = false" class="btn-ghost flex-1 justify-center text-sm">Cancel</button>
                            <button @click="save()" :disabled="saving" class="btn-primary flex-1 justify-center text-sm disabled:opacity-50">
                                {{ saving ? 'Saving...' : (editing ? 'Update' : 'Create') }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    skills: { type: Array, default: () => [] },
})

const showModal = ref(false)
const editing   = ref(null)
const saving    = ref(false)

const form = ref({
    name: '', category: '', percentage: 80,
    emoji: '', sort_order: 0, is_featured: false,
})

const existingCategories = computed(() =>
    [...new Set(props.skills.map(s => s.category).filter(Boolean))]
)

function openModal(skill = null) {
    editing.value = skill
    form.value = skill
        ? { name: skill.name, category: skill.category, percentage: skill.percentage, emoji: skill.emoji || '', sort_order: skill.sort_order || 0, is_featured: skill.is_featured }
        : { name: '', category: '', percentage: 80, emoji: '', sort_order: 0, is_featured: false }
    showModal.value = true
}

function save() {
    saving.value = true
    const url    = editing.value ? `/admin/skills/${editing.value.id}` : '/admin/skills'
    const method = editing.value ? 'put' : 'post'

    router[method](url, form.value, {
        onSuccess: () => { showModal.value = false },
        onFinish:  () => { saving.value = false },
    })
}

function deleteSkill(skill) {
    if (confirm(`Delete "${skill.name}"?`)) {
        router.delete(`/admin/skills/${skill.id}`)
    }
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.25s ease; }
.modal-enter-from, .modal-leave-to       { opacity: 0; }
</style>
