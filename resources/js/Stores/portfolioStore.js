import { defineStore } from 'pinia'
import { ref } from 'vue'

export const usePortfolioStore = defineStore('portfolio', () => {
    const hero        = ref(null)
    const about       = ref(null)
    const skills      = ref([])
    const services    = ref([])
    const projects    = ref([])
    const experiences = ref([])
    const educations  = ref([])
    const testimonials = ref([])
    const certifications = ref([])
    const settings    = ref({})
    const socialLinks = ref([])
    const seo         = ref({})
    const contactInfo = ref({})

    function setPageData(data) {
        if (data.hero)           hero.value        = data.hero
        if (data.about)          about.value       = data.about
        if (data.skills)         skills.value      = data.skills
        if (data.services)       services.value    = data.services
        if (data.projects)       projects.value    = data.projects
        if (data.experiences)    experiences.value = data.experiences
        if (data.educations)     educations.value  = data.educations
        if (data.testimonials)   testimonials.value = data.testimonials
        if (data.certifications) certifications.value = data.certifications
        if (data.settings)       settings.value    = data.settings
        if (data.socialLinks)    socialLinks.value = data.socialLinks
        if (data.seo)            seo.value         = data.seo
        if (data.contactInfo)    contactInfo.value = data.contactInfo
    }

    return {
        hero, about, skills, services, projects,
        experiences, educations, testimonials, certifications,
        settings, socialLinks, seo, contactInfo,
        setPageData,
    }
})
