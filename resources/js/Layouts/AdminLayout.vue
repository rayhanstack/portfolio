<template>
  <div class="min-h-screen bg-gray-50 dark:bg-surface-950 flex">
    <!-- Sidebar -->
    <aside
      :class="[
        'fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-surface-900 border-r border-gray-100 dark:border-white/5 flex flex-col transition-transform duration-300',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
      ]"
    >
      <!-- Logo -->
      <div
        class="flex items-center gap-3 px-5 py-5 border-b border-gray-100 dark:border-white/5"
      >
        <div
          class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-500 to-accent-purple flex items-center justify-center shadow-glow flex-shrink-0"
        >
          <span class="text-white font-bold text-sm">P</span>
        </div>
        <div>
          <div
            class="font-display font-bold text-gray-900 dark:text-white text-sm"
          >
            Portfolio
          </div>
          <div class="text-xs text-gray-500 dark:text-gray-400">
            Admin Panel
          </div>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">
        <template v-for="group in navGroups" :key="group.label">
          <div class="px-3 pt-4 pb-1">
            <span
              class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500"
            >
              {{ group.label }}
            </span>
          </div>
          <Link
            v-for="item in group.items"
            :key="item.href"
            :href="item.href"
            :class="['sidebar-item', { active: isActive(item.href) }]"
          >
            <span class="text-lg" aria-hidden="true">{{ item.icon }}</span>
            {{ item.label }}
            <span
              v-if="item.badge"
              class="ml-auto px-1.5 py-0.5 text-xs bg-red-500 text-white rounded-full"
              >{{ item.badge }}</span
            >
          </Link>
        </template>
      </nav>

      <!-- Bottom -->
      <div class="border-t border-gray-100 dark:border-white/5 p-4 space-y-2">
        <Link href="/" target="_blank" class="sidebar-item">
          <span>🌐</span>
          View Site
        </Link>
        <Link
          href="/admin/logout"
          method="post"
          as="button"
          class="sidebar-item w-full text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10"
        >
          <span>🚪</span>
          Logout
        </Link>
      </div>
    </aside>

    <!-- Overlay (mobile) -->
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-40 bg-black/50 lg:hidden"
      @click="sidebarOpen = false"
    />

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
      <!-- Topbar -->
      <header
        class="sticky top-0 z-30 bg-white/80 dark:bg-surface-900/80 backdrop-blur-xl border-b border-gray-100 dark:border-white/5"
      >
        <div class="flex items-center gap-4 px-4 lg:px-6 py-3.5">
          <!-- Hamburger -->
          <button
            class="lg:hidden p-2 rounded-xl text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition-colors"
            @click="sidebarOpen = !sidebarOpen"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
            </svg>
          </button>

          <!-- Breadcrumb / Page title -->
          <h1
            class="font-display font-semibold text-gray-900 dark:text-white text-lg"
          >
            {{ pageTitle }}
          </h1>

          <div class="ml-auto flex items-center gap-3">
            <!-- Notification Bell -->
            <NotificationBell />

            <!-- Theme toggle -->
            <button
              @click="themeStore.toggle()"
              class="w-9 h-9 rounded-xl glass-card flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
            >
              <svg
                v-if="themeStore.isDark"
                class="w-4 h-4"
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path
                  fill-rule="evenodd"
                  d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                  clip-rule="evenodd"
                />
              </svg>
              <svg
                v-else
                class="w-4 h-4"
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path
                  d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"
                />
              </svg>
            </button>

            <!-- Admin avatar -->
            <div
              class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-500 to-accent-purple flex items-center justify-center text-white text-sm font-bold shadow-glow"
            >
              A
            </div>
          </div>
        </div>
      </header>

      <!-- Flash messages -->
      <Transition name="flash">
        <div
          v-if="flash.success || flash.error"
          :class="[
            'mx-4 lg:mx-6 mt-4 px-4 py-3 rounded-xl flex items-center gap-3 text-sm font-medium',
            flash.success
              ? 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800'
              : 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800',
          ]"
        >
          <svg
            class="w-4 h-4 flex-shrink-0"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              v-if="flash.success"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
            <path
              v-else
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          {{ flash.success || flash.error }}
        </div>
      </Transition>

      <!-- Page content -->
      <main class="flex-1 p-4 lg:p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useThemeStore } from "@/Stores/themeStore";
import NotificationBell from "@/Components/Admin/NotificationBell.vue";

const themeStore = useThemeStore();
const page = usePage();
const sidebarOpen = ref(false);

const flash = computed(() => page.props.flash || {});

const pageTitle = computed(() => {
  const url = page.url;
  const titles = {
    "/admin": "Dashboard",
    "/admin/hero": "Hero Management",
    "/admin/about": "About Me",
    "/admin/skills": "Skills",
    "/admin/services": "Services",
    "/admin/projects": "Projects",
    "/admin/experiences": "Experience",
    "/admin/educations": "Education",
    "/admin/certifications": "Certifications",
    "/admin/social-links": "Social Links",
    "/admin/contact-info": "Contact Info",
    "/admin/messages": "Messages",
    "/admin/seo": "SEO Settings",
    "/admin/settings": "Site Settings",
  };
  return (
    Object.entries(titles).find(([k]) => url.startsWith(k))?.[1] ?? "Admin"
  );
});

const unreadCount = computed(() => page.props.unreadMessages || 0);

const navGroups = computed(() => [
  {
    label: "Overview",
    items: [{ href: "/admin", icon: "📊", label: "Dashboard" }],
  },
  {
    label: "Frontend Content",
    items: [
      { href: "/admin/hero", icon: "🏠", label: "Hero Section" },
      { href: "/admin/about", icon: "👤", label: "About Me" },
      { href: "/admin/skills", icon: "💡", label: "Skills" },
      { href: "/admin/services", icon: "⚙️", label: "Services" },
      { href: "/admin/projects", icon: "🚀", label: "Projects" },
      { href: "/admin/experiences", icon: "💼", label: "Experience" },
      { href: "/admin/educations", icon: "🎓", label: "Education" },
      { href: "/admin/certifications", icon: "🏆", label: "Certifications" },
      // Testimonials removed
    ],
  },
  {
    label: "Communication",
    items: [
      {
        href: "/admin/messages",
        icon: "✉️",
        label: "Messages",
        badge: unreadCount.value || null,
      },
    ],
  },
  {
    label: "Configuration",
    items: [
      { href: "/admin/social-links", icon: "🔗", label: "Social Links" },
      { href: "/admin/contact-info", icon: "📍", label: "Contact Info" },
      { href: "/admin/seo", icon: "🔍", label: "SEO Settings" },
      { href: "/admin/settings", icon: "⚙️", label: "Site Settings" },
    ],
  },
]);

function isActive(href) {
  return page.url === href || (href !== "/admin" && page.url.startsWith(href));
}
</script>

<style scoped>
.flash-enter-active,
.flash-leave-active {
  transition: all 0.3s ease;
}
.flash-enter-from,
.flash-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>