<template>
    <AdminLayout>
        <Head :title="project ? 'Edit Project' : 'New Project'" />

        <div class="max-w-4xl">
            <div class="flex items-center gap-4 mb-6">
                <Link href="/admin/projects" class="p-2 rounded-xl glass-card text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <div>
                    <h2 class="font-display text-xl font-bold text-gray-900 dark:text-white">
                        {{ project ? 'Edit Project' : 'New Project' }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ project ? `Editing: ${project.title}` : 'Add a new portfolio project' }}
                    </p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Basic Info -->
                <div class="glass-card p-6">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-5">Basic Information</h3>
                    <div class="space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Project Title *</label>
                                <input v-model="form.title" type="text" placeholder="My Awesome Project" class="form-input"
                                    :class="{ 'border-red-400': errors.title }" />
                                <p v-if="errors.title" class="mt-1 text-xs text-red-500">{{ errors.title }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Category</label>
                                <select v-model="form.category_id" class="form-input">
                                    <option value="">No category</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Short Description *</label>
                            <textarea v-model="form.description" rows="2" class="form-input resize-none"
                                placeholder="Brief one-liner description shown on project cards"
                                :class="{ 'border-red-400': errors.description }" />
                            <p v-if="errors.description" class="mt-1 text-xs text-red-500">{{ errors.description }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Full Description</label>
                            <textarea v-model="form.full_description" rows="5" class="form-input resize-none"
                                placeholder="Detailed project overview shown in the project detail modal..." />
                        </div>
                    </div>
                </div>

                <!-- Media -->
                <div class="glass-card p-6">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-5">Media</h3>
                    <div class="space-y-5">
                        <!-- Thumbnail -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Thumbnail Image</label>
                            <div class="flex items-start gap-4">
                                <div
                                    v-if="thumbnailPreview || project?.thumbnail"
                                    class="w-32 h-24 rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 flex-shrink-0"
                                >
                                    <img :src="thumbnailPreview || project.thumbnail" class="w-full h-full object-cover" />
                                </div>
                                <div class="flex-1">
                                    <input type="file" accept="image/*" @change="handleThumbnail" class="form-input text-sm" />
                                    <p class="text-xs text-gray-400 mt-1">Recommended: 1200×800px, max 2MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Additional Images</label>
                            <input type="file" accept="image/*" multiple @change="handleImages" class="form-input text-sm" />

                            <!-- Existing images -->
                            <div v-if="project?.images?.length" class="mt-3 flex flex-wrap gap-3">
                                <div
                                    v-for="img in project.images"
                                    :key="img.id"
                                    class="relative w-20 h-16 rounded-lg overflow-hidden group"
                                >
                                    <img :src="img.image" class="w-full h-full object-cover" />
                                    <button
                                        @click="removeImage(img.id)"
                                        class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity"
                                    >
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- New image previews -->
                            <div v-if="imagePreviews.length" class="mt-3 flex flex-wrap gap-3">
                                <div
                                    v-for="(preview, i) in imagePreviews"
                                    :key="'new-' + i"
                                    class="relative w-20 h-16 rounded-lg overflow-hidden border-2 border-primary-300 dark:border-primary-700"
                                >
                                    <img :src="preview" class="w-full h-full object-cover" />
                                    <div class="absolute top-0.5 right-0.5 w-4 h-4 bg-primary-500 rounded-full flex items-center justify-center">
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Emoji fallback -->
                        <div class="grid sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Emoji (fallback)</label>
                                <input v-model="form.emoji" type="text" placeholder="🚀" class="form-input" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technologies & Features -->
                <div class="glass-card p-6">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-5">Technologies & Features</h3>
                    <div class="space-y-5">
                        <!-- Technologies -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Technologies Used</label>
                            <div class="flex gap-2 mb-2">
                                <input
                                    v-model="newTech"
                                    type="text"
                                    placeholder="e.g. Laravel, Vue.js"
                                    class="form-input flex-1"
                                    @keyup.enter="addTech"
                                />
                                <button @click="addTech" class="btn-primary text-sm px-4">Add</button>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="(tech, i) in form.technologies"
                                    :key="tech"
                                    class="flex items-center gap-1.5 badge pr-1.5"
                                >
                                    {{ tech }}
                                    <button @click="removeTech(i)" class="w-4 h-4 rounded-full bg-primary-200 dark:bg-primary-800 flex items-center justify-center hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- Features -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Key Features</label>
                            <div class="flex gap-2 mb-2">
                                <input
                                    v-model="newFeature"
                                    type="text"
                                    placeholder="e.g. Real-time notifications"
                                    class="form-input flex-1"
                                    @keyup.enter="addFeature"
                                />
                                <button @click="addFeature" class="btn-primary text-sm px-4">Add</button>
                            </div>
                            <ul class="space-y-1.5">
                                <li
                                    v-for="(feat, i) in form.features"
                                    :key="feat"
                                    class="flex items-center justify-between gap-3 p-2.5 bg-gray-50 dark:bg-white/5 rounded-lg"
                                >
                                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                        <svg class="w-4 h-4 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ feat }}
                                    </div>
                                    <button @click="removeFeature(i)" class="text-gray-400 hover:text-red-500 transition-colors flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Links & Meta -->
                <div class="glass-card p-6">
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white mb-5">Links & Metadata</h3>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Live Demo URL</label>
                            <input v-model="form.live_url" type="url" placeholder="https://example.com" class="form-input" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">GitHub URL</label>
                            <input v-model="form.github_url" type="url" placeholder="https://github.com/..." class="form-input" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Client / Company</label>
                            <input v-model="form.client" type="text" placeholder="e.g. Acme Corp" class="form-input" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Duration</label>
                            <input v-model="form.duration" type="text" placeholder="e.g. 3 months" class="form-input" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Sort Order</label>
                            <input v-model.number="form.sort_order" type="number" class="form-input" />
                        </div>
                    </div>

                    <!-- Toggles -->
                    <div class="flex flex-wrap gap-6 mt-5 pt-5 border-t border-gray-100 dark:border-white/5">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div
                                @click="form.is_featured = !form.is_featured"
                                :class="['relative w-10 h-5 rounded-full transition-colors cursor-pointer', form.is_featured ? 'bg-primary-600' : 'bg-gray-200 dark:bg-white/10']"
                            >
                                <div :class="['absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform', form.is_featured ? 'left-5' : 'left-0.5']" />
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured Project</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <div
                                @click="form.is_active = !form.is_active"
                                :class="['relative w-10 h-5 rounded-full transition-colors cursor-pointer', form.is_active ? 'bg-green-500' : 'bg-gray-200 dark:bg-white/10']"
                            >
                                <div :class="['absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform', form.is_active ? 'left-5' : 'left-0.5']" />
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active (visible on site)</span>
                        </label>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-4">
                    <Link href="/admin/projects" class="btn-ghost text-sm">Cancel</Link>
                    <button @click="save" :disabled="saving" class="btn-primary disabled:opacity-50">
                        <svg v-if="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        {{ saving ? 'Saving...' : (project ? 'Update Project' : 'Create Project') }}
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
    project:    { type: Object, default: null },
    categories: { type: Array,  default: () => [] },
})

const saving          = ref(false)
const thumbnailPreview = ref(null)
const imagePreviews   = ref([])
const newTech         = ref('')
const newFeature      = ref('')
const errors          = ref({})

const form = ref({
    title:            props.project?.title            || '',
    category_id:      props.project?.category_id      || '',
    description:      props.project?.description      || '',
    full_description: props.project?.full_description || '',
    technologies:     props.project?.technologies     || [],
    features:         props.project?.features         || [],
    live_url:         props.project?.live_url         || '',
    github_url:       props.project?.github_url       || '',
    client:           props.project?.client           || '',
    duration:         props.project?.duration         || '',
    emoji:            props.project?.emoji            || '',
    is_featured:      props.project?.is_featured      ?? false,
    is_active:        props.project?.is_active        ?? true,
    sort_order:       props.project?.sort_order        || 0,
    thumbnail:        null,
    images:           [],
})

function handleThumbnail(e) {
    const file = e.target.files[0]
    if (!file) return
    form.value.thumbnail = file
    thumbnailPreview.value = URL.createObjectURL(file)
}

function handleImages(e) {
    const files = Array.from(e.target.files)
    form.value.images = files
    imagePreviews.value = files.map(f => URL.createObjectURL(f))
}

function addTech() {
    const t = newTech.value.trim()
    if (t && !form.value.technologies.includes(t)) {
        form.value.technologies.push(t)
    }
    newTech.value = ''
}

function removeTech(i) {
    form.value.technologies.splice(i, 1)
}

function addFeature() {
    const f = newFeature.value.trim()
    if (f) form.value.features.push(f)
    newFeature.value = ''
}

function removeFeature(i) {
    form.value.features.splice(i, 1)
}

function removeImage(id) {
    if (confirm('Remove this image?')) {
        router.delete(`/admin/project-images/${id}`)
    }
}

function save() {
    saving.value = true
    errors.value = {}

    const data = new FormData()

    // Append all scalar fields
    const scalars = ['title', 'description', 'full_description', 'live_url', 'github_url', 'client', 'duration', 'emoji', 'sort_order']
    scalars.forEach(key => { if (form.value[key] !== null && form.value[key] !== '') data.append(key, form.value[key]) })

    data.append('category_id',  form.value.category_id || '')
    data.append('is_featured',  form.value.is_featured ? '1' : '0')
    data.append('is_active',    form.value.is_active   ? '1' : '0')

    // JSON arrays
    form.value.technologies.forEach(t => data.append('technologies[]', t))
    form.value.features.forEach(f => data.append('features[]', f))

    if (form.value.thumbnail) data.append('thumbnail', form.value.thumbnail)
    form.value.images.forEach(img => data.append('images[]', img))

    const url    = props.project ? `/admin/projects/${props.project.id}` : '/admin/projects'
    const method = props.project ? 'post' : 'post'  // Laravel: PUT via FormData uses POST + _method

    if (props.project) data.append('_method', 'PUT')

    router.post(url, data, {
        forceFormData: true,
        onSuccess:     () => { router.visit('/admin/projects') },
        onError:       (errs) => { errors.value = errs },
        onFinish:      () => { saving.value = false },
    })
}
</script>
