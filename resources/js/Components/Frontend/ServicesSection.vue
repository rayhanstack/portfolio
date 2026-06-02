<!-- ServicesSection.vue -->
<template>
    <section id="services" class="section-padding">
        <div class="container-max">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="badge mb-3">What I Offer</span>
                <h2 class="section-title mb-4">My <span class="gradient-text">Services</span></h2>
                <p class="section-subtitle max-w-xl mx-auto">Specialized services tailored to your project needs.</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="(service, i) in services" :key="service.id"
                    class="service-card" data-aos="fade-up" :data-aos-delay="(i % 6) * 80">
                    <div class="text-4xl mb-4 group-hover:scale-110 transition-transform duration-300">{{ service.icon_svg || '⚙️' }}</div>
                    <h3 class="font-display font-semibold text-gray-900 dark:text-white text-lg mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ service.title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ service.description }}</p>
                    <div class="mt-4 w-8 h-0.5 bg-gradient-to-r from-primary-500 to-accent-cyan rounded-full group-hover:w-16 transition-all duration-300" />
                </div>
            </div>
        </div>
    </section>
</template>
<script setup>
defineProps({ services: { type: Array, default: () => [] } })
</script>
