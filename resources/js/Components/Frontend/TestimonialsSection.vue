<template>
    <section id="testimonials" class="section-padding">
        <div class="container-max">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="badge mb-3">Client Reviews</span>
                <h2 class="section-title mb-4">What Clients <span class="gradient-text">Say</span></h2>
                <p class="section-subtitle max-w-xl mx-auto">Don't take my word for it — hear it from the people I've worked with.</p>
            </div>

            <div v-if="testimonials.length" class="relative" data-aos="fade-up" data-aos-delay="100">
                <Swiper
                    :modules="swiperModules"
                    :slides-per-view="1"
                    :space-between="24"
                    :breakpoints="{
                        640:  { slidesPerView: 2 },
                        1024: { slidesPerView: 3 },
                    }"
                    :pagination="{ clickable: true }"
                    :autoplay="{ delay: 4000, disableOnInteraction: false }"
                    loop
                    class="pb-12"
                >
                    <SwiperSlide v-for="t in testimonials" :key="t.id">
                        <div class="testimonial-card h-full flex flex-col">
                            <!-- Quote icon -->
                            <div class="text-primary-500/30 text-6xl font-serif leading-none mb-3 select-none">"</div>

                            <!-- Review -->
                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed flex-1">{{ t.review }}</p>

                            <!-- Stars -->
                            <div class="flex items-center gap-0.5 mt-4 mb-5">
                                <span v-for="star in 5" :key="star" class="text-amber-400 text-sm">
                                    {{ star <= t.rating ? '★' : '☆' }}
                                </span>
                            </div>

                            <!-- Client -->
                            <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-white/5">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-primary-50 dark:bg-primary-900/20 flex-shrink-0">
                                    <img
                                        v-if="t.client_image"
                                        :src="t.client_image"
                                        :alt="t.client_name"
                                        class="w-full h-full object-cover"
                                    />
                                    <div v-else class="w-full h-full flex items-center justify-center font-bold text-primary-600 dark:text-primary-400">
                                        {{ t.client_name[0] }}
                                    </div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-white text-sm">{{ t.client_name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ t.designation }}{{ t.company ? ` @ ${t.company}` : '' }}</div>
                                </div>
                            </div>
                        </div>
                    </SwiperSlide>
                </Swiper>
            </div>

            <div v-else class="text-center text-gray-500 dark:text-gray-400 py-12">No testimonials yet.</div>
        </div>
    </section>
</template>

<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Pagination, Autoplay } from 'swiper/modules'

defineProps({ testimonials: { type: Array, default: () => [] } })

const swiperModules = [Pagination, Autoplay]
</script>
