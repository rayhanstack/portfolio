<template>
    <Head>
        <title>{{ seo?.meta_title || settings?.site_name }}</title>
        <meta name="description" :content="seo?.meta_description" />
        <meta name="keywords" :content="seo?.keywords" />
        <meta property="og:title"       :content="seo?.og_title || seo?.meta_title" />
        <meta property="og:description" :content="seo?.og_description || seo?.meta_description" />
        <meta property="og:image"       :content="seo?.og_image" />
        <meta name="twitter:card"       content="summary_large_image" />
        <meta name="twitter:title"      :content="seo?.meta_title" />
        <meta name="twitter:description" :content="seo?.meta_description" />
    </Head>

    <FrontendLayout
        :settings="settings"
        :social-links="socialLinks"
        :hero="hero"
    >
        <!-- 1. Hero Section -->
        <HeroSection :hero="hero" :social-links="socialLinks" />

        <!-- 2. About Section -->
        <AboutSection :about="about" />

        <!-- 3. Skills Section -->
        <SkillsSection :skills="skills" />

        <!-- 4. Services Section -->
        <ServicesSection :services="services" />

        <!-- 5. Projects Section -->
        <ProjectsSection :projects="projects" :categories="projectCategories" />

        <!-- 6. Experience Section -->
        <ExperienceSection :experiences="experiences" />

        <!-- 7. Education Section -->
        <EducationSection :educations="educations" />

        <!-- 9. Certifications Section -->
        <CertificationsSection :certifications="certifications" />

        <!-- 10. Contact Section -->
        <ContactSection :contact-info="contactInfo" :social-links="socialLinks" />
    </FrontendLayout>
</template>

<script setup>
import { onMounted } from 'vue'
import FrontendLayout from '@/Layouts/FrontendLayout.vue'
import { usePortfolioStore } from '@/Stores/portfolioStore'

// Section components
import HeroSection          from '@/Components/Frontend/HeroSection.vue'
import AboutSection         from '@/Components/Frontend/AboutSection.vue'
import SkillsSection        from '@/Components/Frontend/SkillsSection.vue'
import ServicesSection      from '@/Components/Frontend/ServicesSection.vue'
import ProjectsSection      from '@/Components/Frontend/ProjectsSection.vue'
import ExperienceSection    from '@/Components/Frontend/ExperienceSection.vue'
import EducationSection     from '@/Components/Frontend/EducationSection.vue'
import CertificationsSection from '@/Components/Frontend/CertificationsSection.vue'
import ContactSection       from '@/Components/Frontend/ContactSection.vue'

const props = defineProps({
    hero:             { type: Object, default: null },
    about:            { type: Object, default: null },
    skills:           { type: Array,  default: () => [] },
    services:         { type: Array,  default: () => [] },
    projects:         { type: Array,  default: () => [] },
    projectCategories:{ type: Array,  default: () => [] },
    experiences:      { type: Array,  default: () => [] },
    educations:       { type: Array,  default: () => [] },
    testimonials:     { type: Array,  default: () => [] },
    certifications:   { type: Array,  default: () => [] },
    contactInfo:      { type: Object, default: () => ({}) },
    socialLinks:      { type: Array,  default: () => [] },
    settings:         { type: Object, default: () => ({}) },
    seo:              { type: Object, default: () => ({}) },
})

const store = usePortfolioStore()

onMounted(() => {
    store.setPageData({
        hero:          props.hero,
        about:         props.about,
        skills:        props.skills,
        services:      props.services,
        projects:      props.projects,
        experiences:   props.experiences,
        educations:    props.educations,
        certifications: props.certifications,
        contactInfo:   props.contactInfo,
        socialLinks:   props.socialLinks,
        settings:      props.settings,
        seo:           props.seo,
    })
})
</script>
