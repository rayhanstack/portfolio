<template>
    <div class="min-h-screen bg-white dark:bg-surface-900 transition-colors duration-300">
        <!-- Navigation -->
        <nav
            :class="[
                'fixed top-0 inset-x-0 z-50 transition-all duration-500',
                scrolled
                    ? 'bg-white/90 dark:bg-surface-900/90 backdrop-blur-xl shadow-sm border-b border-gray-100 dark:border-white/5'
                    : 'bg-transparent'
            ]"
        >
            <div class="container-max px-4 lg:px-8 py-4 flex items-center justify-between">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-2 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-500 to-accent-purple flex items-center justify-center shadow-glow group-hover:shadow-glow-lg transition-shadow">
                        <span class="text-white font-display font-bold text-sm">
                            {{ settings?.logo_text || 'P' }}
                        </span>
                    </div>
                    <span class="font-display font-bold text-lg text-gray-900 dark:text-white">
                        {{ settings?.site_name || 'Portfolio' }}
                    </span>
                </Link>

                <!-- Desktop Nav Links -->
                <div class="hidden lg:flex items-center gap-1">
                    <a
                        v-for="item in navItems"
                        :key="item.href"
                        :href="item.href"
                        class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-all duration-150"
                        @click.prevent="scrollTo(item.href)"
                    >
                        {{ item.label }}
                    </a>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <!-- Theme Toggle -->
                    <button
                        @click="themeStore.toggle()"
                        class="w-9 h-9 rounded-xl glass-card flex items-center justify-center hover:shadow-glow transition-all"
                        :title="themeStore.isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                    >
                        <svg v-if="themeStore.isDark" class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                        </svg>
                        <svg v-else class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                        </svg>
                    </button>

                    <!-- CTA (Download CV) -->
                    <a
                        v-if="hero?.resume_url"
                        :href="hero.resume_url"
                        target="_blank"
                        class="hidden sm:inline-flex btn-primary text-sm py-2"
                        download
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Resume
                    </a>

                    <!-- Hamburger (mobile) -->
                    <button
                        class="lg:hidden w-9 h-9 rounded-xl glass-card flex items-center justify-center"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <Transition name="menu">
                <div
                    v-if="mobileMenuOpen"
                    class="lg:hidden bg-white/95 dark:bg-surface-900/95 backdrop-blur-xl border-t border-gray-100 dark:border-white/5"
                >
                    <div class="container-max px-4 py-4 flex flex-col gap-1">
                        <a
                            v-for="item in navItems"
                            :key="item.href"
                            :href="item.href"
                            class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-white/5 rounded-xl transition-all"
                            @click.prevent="scrollTo(item.href); mobileMenuOpen = false"
                        >
                            {{ item.label }}
                        </a>
                        <a
                            v-if="hero?.resume_url"
                            :href="hero.resume_url"
                            class="btn-primary text-sm mt-2"
                            download
                        >
                            Download Resume
                        </a>
                    </div>
                </div>
            </Transition>
        </nav>

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-surface-950 py-12 px-4">
            <div class="container-max flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-500 to-accent-purple flex items-center justify-center">
                        <span class="text-white font-bold text-xs">{{ settings?.logo_text || 'P' }}</span>
                    </div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ settings?.footer_text || '© 2025 Portfolio. All rights reserved.' }}</span>
                </div>

                <!-- Social Links -->
                <div class="flex items-center gap-3">
                    <a
                        v-for="link in socialLinks"
                        :key="link.id"
                        :href="link.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="w-9 h-9 rounded-xl glass-card flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:shadow-glow transition-all"
                    >
                        <span class="sr-only">{{ link.platform }}</span>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path :d="getSocialIcon(link.platform)" />
                        </svg>
                    </a>
                </div>
            </div>
        </footer>

        <!-- Scroll to top -->
        <Transition name="fade">
            <button
                v-if="scrolled && scrollY > 600"
                @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                class="fixed bottom-6 right-6 z-50 w-11 h-11 rounded-xl bg-primary-600 text-white shadow-glow-lg hover:bg-primary-700 flex items-center justify-center transition-all hover:-translate-y-1"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
            </button>
        </Transition>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useThemeStore } from '@/Stores/themeStore'
import { usePortfolioStore } from '@/Stores/portfolioStore'

const props = defineProps({
    settings:    { type: Object, default: () => ({}) },
    socialLinks: { type: Array,  default: () => [] },
    hero:        { type: Object, default: null },
})

const themeStore    = useThemeStore()
const portfolioStore = usePortfolioStore()

const scrollY = ref(0)
const scrolled = ref(false)
const mobileMenuOpen = ref(false)

const navItems = [
    { href: '#hero',            label: 'Home'         },
    { href: '#about',           label: 'About'        },
    { href: '#skills',          label: 'Skills'       },
    { href: '#services',        label: 'Services'     },
    { href: '#projects',        label: 'Projects'     },
    { href: '#experience',      label: 'Experience'   },
    { href: '#education',       label: 'Education'    },
    { href: '#testimonials',    label: 'Testimonials' },
    { href: '#contact',         label: 'Contact'      },
]

function scrollTo(href) {
    const id = href.replace('#', '')
    const el = document.getElementById(id)
    if (el) el.scrollIntoView({ behavior: 'smooth' })
}

function handleScroll() {
    scrollY.value  = window.scrollY
    scrolled.value = window.scrollY > 60
}

const socialIcons = {
    github:    'M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z',
    linkedin:  'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
    twitter:   'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z',
    facebook:  'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
    instagram: 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z',
    youtube:   'M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z',
}

function getSocialIcon(platform) {
    return socialIcons[platform?.toLowerCase()] || ''
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true })
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
.menu-enter-active, .menu-leave-active { transition: all 0.25s ease; }
.menu-enter-from, .menu-leave-to      { opacity: 0; transform: translateY(-8px); }
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to       { opacity: 0; }
</style>
