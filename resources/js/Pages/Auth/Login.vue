<template>
    <div class="min-h-screen animated-gradient-bg flex items-center justify-center p-4">
        <Head title="Admin Login" />

        <!-- Background effects -->
        <div class="absolute inset-0 bg-grid-pattern opacity-20 pointer-events-none" />
        <div class="absolute top-1/4 -left-32 w-64 h-64 bg-primary-500/10 rounded-full blur-3xl" />
        <div class="absolute bottom-1/3 -right-32 w-64 h-64 bg-accent-purple/10 rounded-full blur-3xl" />

        <div class="relative w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-purple shadow-glow-lg mb-4">
                    <span class="text-white font-display font-bold text-xl">P</span>
                </div>
                <h1 class="font-display text-2xl font-bold text-white">Welcome back</h1>
                <p class="text-white/60 text-sm mt-1">Sign in to your admin panel</p>
            </div>

            <!-- Card -->
            <div class="glass-card-dark p-8 border border-white/10">
                <!-- Error -->
                <div v-if="form.errors.email" class="mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
                    {{ form.errors.email }}
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-white/70 mb-1.5">Email Address</label>
                        <input
                            v-model="form.email"
                            type="email"
                            autocomplete="email"
                            placeholder="admin@example.com"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500/50 transition-all"
                            @keyup.enter="submit"
                        />
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm font-medium text-white/70">Password</label>
                        </div>
                        <input
                            v-model="form.password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500/50 transition-all"
                            @keyup.enter="submit"
                        />
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input v-model="form.remember" type="checkbox" class="w-4 h-4 rounded accent-primary-600" />
                        <span class="text-sm text-white/60">Remember me</span>
                    </label>

                    <button
                        @click="submit"
                        :disabled="form.processing"
                        class="w-full btn-primary justify-center py-3 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        {{ form.processing ? 'Signing in...' : 'Sign In' }}
                    </button>
                </div>
            </div>

            <p class="text-center text-white/30 text-xs mt-6">
                Protected area — unauthorized access is prohibited
            </p>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email:    '',
    password: '',
    remember: false,
})

function submit() {
    form.post('/admin/login', {
        onFinish: () => form.reset('password'),
    })
}
</script>
