<template>
    <AdminLayout>
        <Head title="Dashboard" />

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <div
                v-for="(stat, i) in statCards"
                :key="stat.label"
                class="stat-card group cursor-default"
                data-aos="fade-up"
                :data-aos-delay="i * 60"
            >
                <div
                    :class="['w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0 transition-transform group-hover:scale-110', stat.bg]"
                >
                    {{ stat.icon }}
                </div>
                <div>
                    <div class="text-2xl font-display font-bold text-gray-900 dark:text-white">
                        {{ stat.value }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ stat.label }}</div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Recent Messages -->
            <div class="lg:col-span-2 glass-card p-6" data-aos="fade-up">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-display font-semibold text-gray-900 dark:text-white">Recent Messages</h2>
                    <Link href="/admin/messages" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                        View all
                    </Link>
                </div>

                <div v-if="recentMessages.length" class="space-y-3">
                    <div
                        v-for="msg in recentMessages"
                        :key="msg.id"
                        class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 transition-colors cursor-pointer group"
                        @click="$inertia.visit(`/admin/messages/${msg.id}`)"
                    >
                        <div class="w-9 h-9 rounded-xl bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-sm flex-shrink-0">
                            {{ msg.name[0].toUpperCase() }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-900 dark:text-white text-sm truncate">{{ msg.name }}</span>
                                <span v-if="!msg.is_read" class="w-2 h-2 rounded-full bg-primary-500 flex-shrink-0" />
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ msg.subject || msg.email }}</div>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500 flex-shrink-0">
                            {{ formatDate(msg.created_at) }}
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-10 text-gray-500 dark:text-gray-400 text-sm">
                    No messages yet.
                </div>
            </div>

            <!-- Quick Links -->
            <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="100">
                <h2 class="font-display font-semibold text-gray-900 dark:text-white mb-5">Quick Actions</h2>
                <div class="space-y-2">
                    <Link
                        v-for="link in quickLinks"
                        :key="link.href"
                        :href="link.href"
                        class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group"
                    >
                        <span class="text-lg">{{ link.icon }}</span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                            {{ link.label }}
                        </span>
                        <svg class="w-4 h-4 ml-auto text-gray-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    stats:          { type: Object, default: () => ({}) },
    recentMessages: { type: Array,  default: () => [] },
})

const statCards = computed(() => [
    { label: 'Projects',    value: props.stats.projects,    icon: '🚀', bg: 'bg-blue-50 dark:bg-blue-900/20'   },
    { label: 'Skills',      value: props.stats.skills,      icon: '💡', bg: 'bg-purple-50 dark:bg-purple-900/20' },
    { label: 'Services',    value: props.stats.services,    icon: '⚙️', bg: 'bg-green-50 dark:bg-green-900/20'  },
    { label: 'Experience',  value: props.stats.experiences, icon: '💼', bg: 'bg-amber-50 dark:bg-amber-900/20'  },
    { label: 'Messages',    value: props.stats.messages,    icon: '✉️', bg: 'bg-pink-50 dark:bg-pink-900/20'    },
    { label: 'Unread',      value: props.stats.unread,      icon: '🔴', bg: 'bg-red-50 dark:bg-red-900/20'      },
])

const quickLinks = [
    { href: '/admin/projects/create', icon: '➕', label: 'Add New Project'   },
    { href: '/admin/skills',          icon: '💡', label: 'Manage Skills'     },
    { href: '/admin/hero',            icon: '🏠', label: 'Edit Hero Section' },
    { href: '/admin/seo',             icon: '🔍', label: 'Update SEO'        },
    { href: '/admin/settings',        icon: '⚙️', label: 'Site Settings'     },
    { href: '/',                      icon: '🌐', label: 'View Live Site'    },
]

function formatDate(dateStr) {
    const date = new Date(dateStr)
    const now  = new Date()
    const diff = Math.floor((now - date) / 1000)
    if (diff < 60) return 'Just now'
    if (diff < 3600) return `${Math.floor(diff / 60)}m ago`
    if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
}
</script>
