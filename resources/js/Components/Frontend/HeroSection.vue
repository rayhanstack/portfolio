<template>
    <section
        id="hero"
        class="relative min-h-screen flex items-center justify-center overflow-hidden animated-gradient-bg"
    >
        <!-- Particle Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
            <div
                v-for="(p, i) in particles"
                :key="i"
                class="particle"
                :style="{
                    left: p.x + '%',
                    top: p.y + '%',
                    width: p.size + 'px',
                    height: p.size + 'px',
                    animationDuration: p.duration + 's',
                    animationDelay: p.delay + 's',
                }"
            />

            <!-- Gradient orbs -->
            <div class="absolute top-1/4 -left-40 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl animate-pulse-slow" />
            <div class="absolute bottom-1/3 -right-40 w-96 h-96 bg-accent-purple/10 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 2s" />
            <div class="absolute top-2/3 left-1/3 w-64 h-64 bg-accent-cyan/5 rounded-full blur-3xl" />
        </div>

        <!-- Grid pattern overlay -->
        <div class="absolute inset-0 bg-grid-pattern opacity-30 pointer-events-none" aria-hidden="true" />

        <!-- Content -->
        <div class="container-max px-4 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center py-28 lg:py-0">

                <!-- Left: Text content -->
                <div class="order-2 lg:order-1 text-center lg:text-left">
                    <!-- Availability badge -->
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 mb-6 rounded-full bg-white/5 border border-white/10 text-sm text-white/80"
                        data-aos="fade-down"
                    >
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse flex-shrink-0" />
                        Available for opportunities
                    </div>

                    <!-- Name -->
                    <h1
                        class="font-display text-5xl md:text-6xl xl:text-7xl font-bold text-white leading-tight mb-4"
                        data-aos="fade-up" data-aos-delay="100"
                    >
                        Hi, I'm
                        <span class="gradient-text block">{{ hero?.name || 'John Doe' }}</span>
                    </h1>

                    <!-- Typing designation -->
                    <div class="font-display text-xl md:text-2xl text-white/70 mb-6 h-9" data-aos="fade-up" data-aos-delay="200">
                        <span ref="typedEl" />
                    </div>

                    <!-- Bio snippet -->
                    <p
                        v-if="hero?.tagline"
                        class="text-white/60 text-lg leading-relaxed mb-8 max-w-lg mx-auto lg:mx-0"
                        data-aos="fade-up" data-aos-delay="300"
                    >
                        {{ hero.tagline }}
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap gap-4 justify-center lg:justify-start" data-aos="fade-up" data-aos-delay="400">
                        <a href="#contact" class="btn-primary" @click.prevent="scrollTo('#contact')">
                            Hire Me
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <a href="#projects" class="btn-glass" @click.prevent="scrollTo('#projects')">
                            View Projects
                        </a>
                        <a
                            v-if="hero?.resume_url"
                            :href="hero.resume_url"
                            class="btn-glass"
                            download
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Resume
                        </a>
                    </div>

                    <!-- Social Links -->
                    <div v-if="socialLinks?.length" class="flex items-center gap-3 mt-8 justify-center lg:justify-start" data-aos="fade-up" data-aos-delay="500">
                        <span class="text-white/40 text-sm">Follow me</span>
                        <div class="w-8 h-px bg-white/20" />
                        <a
                            v-for="link in socialLinks"
                            :key="link.id"
                            :href="link.url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white/60 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path :d="getSocialIcon(link.platform)" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Right: Profile Image -->
                <div class="order-1 lg:order-2 flex justify-center" data-aos="fade-left" data-aos-delay="200">
                    <div class="relative">
                        <!-- Animated ring -->
                        <div class="absolute inset-0 rounded-full border-2 border-primary-500/30 animate-spin-slow scale-110" />
                        <div class="absolute inset-0 rounded-full border border-accent-cyan/20 animate-spin-slow scale-125" style="animation-direction: reverse; animation-duration: 12s" />

                        <!-- Glow effect -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-500/20 to-accent-purple/20 rounded-full blur-2xl scale-110" />

                        <!-- Profile image -->
                        <div class="relative w-64 h-64 md:w-80 md:h-80 lg:w-96 lg:h-96 rounded-full overflow-hidden border-4 border-white/10 shadow-2xl animate-float">
                            <img
                                v-if="hero?.profile_image"
                                :src="hero.profile_image"
                                :alt="hero?.name"
                                class="w-full h-full object-cover"
                                loading="eager"
                            />
                            <div v-else class="w-full h-full bg-gradient-to-br from-primary-500 to-accent-purple flex items-center justify-center">
                                <span class="text-8xl">👤</span>
                            </div>
                        </div>

                        <!-- Floating stat badges -->
                        <div class="absolute -bottom-4 -left-4 glass-card-dark px-4 py-3 rounded-2xl border border-white/10 shadow-glass animate-float" style="animation-delay: 1s">
                            <div class="text-2xl font-bold text-white">{{ hero?.years_experience || 5 }}+</div>
                            <div class="text-xs text-white/60">Years Experience</div>
                        </div>
                        <div class="absolute -top-4 -right-4 glass-card-dark px-4 py-3 rounded-2xl border border-white/10 shadow-glass animate-float" style="animation-delay: 2s">
                            <div class="text-2xl font-bold text-white">{{ hero?.projects_count || 50 }}+</div>
                            <div class="text-xs text-white/60">Projects Done</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll indicator -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-white/40">
                <span class="text-xs font-medium tracking-widest uppercase">Scroll Down</span>
                <div class="w-px h-12 bg-gradient-to-b from-white/40 to-transparent animate-pulse" />
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Typed from 'typed.js'

const props = defineProps({
    hero:        { type: Object, default: null },
    socialLinks: { type: Array,  default: () => [] },
})

const typedEl = ref(null)
let typedInstance = null

const particles = Array.from({ length: 25 }, () => ({
    x:        Math.random() * 100,
    y:        Math.random() * 100,
    size:     Math.random() * 6 + 2,
    duration: Math.random() * 4 + 3,
    delay:    Math.random() * 4,
}))

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

function scrollTo(href) {
    const id = href.replace('#', '')
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' })
}

onMounted(() => {
    if (typedEl.value) {
        const strings = props.hero?.designations?.length
            ? props.hero.designations
            : ['Full Stack Developer', 'UI/UX Designer', 'Problem Solver', 'Creative Thinker']

        typedInstance = new Typed(typedEl.value, {
            strings,
            typeSpeed:      55,
            backSpeed:      30,
            backDelay:      2000,
            startDelay:     500,
            loop:           true,
            cursorChar:     '|',
            smartBackspace: true,
        })
    }
})

onUnmounted(() => {
    typedInstance?.destroy()
})
</script>
