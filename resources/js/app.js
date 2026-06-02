import './bootstrap'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp, Head, Link } from '@inertiajs/vue3'
import { createPinia } from 'pinia'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import NProgress from 'nprogress'

// AOS (Animate on Scroll)
import AOS from 'aos'
import 'aos/dist/aos.css'

// Swiper
import 'swiper/css'
import 'swiper/css/pagination'
import 'swiper/css/navigation'
import 'swiper/css/effect-fade'

const appName = import.meta.env.VITE_APP_NAME || 'Portfolio'

// NProgress router events
import { router } from '@inertiajs/vue3'

router.on('start',  () => NProgress.start())
router.on('finish', () => NProgress.done())

createInertiaApp({
    title: (title) => title ? `${title} | ${appName}` : appName,

    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),

    setup({ el, App, props, plugin }) {
        const pinia = createPinia()

        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .component('Head', Head)
            .component('Link', Link)

        // Initialize AOS globally after mount
        app.mixin({
            mounted() {
                AOS.init({
                    duration: 700,
                    once: true,
                    offset: 80,
                    easing: 'ease-out-cubic',
                })
            },
        })

        app.mount(el)
        return app
    },

    progress: false, // Using NProgress manually above
})
