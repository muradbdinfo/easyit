<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import SectionHeading from '@/Components/Public/SectionHeading.vue'
import ServiceCard from '@/Components/Public/ServiceCard.vue'
import ProjectCard from '@/Components/Public/ProjectCard.vue'
import PostCard from '@/Components/Public/PostCard.vue'
import FaqAccordion from '@/Components/Public/FaqAccordion.vue'
import { ref, onMounted } from 'vue'

defineProps({
    services: Array,
    featuredProjects: Array,
    testimonials: Array,
    latestPosts: Array,
    faqs: Array,
    stats: Object,
})

// Animated counters
const counters = ref({ clients: 0, projects: 0, experience: 0 })
onMounted(() => {
    const animate = (key, target) => {
        const num = parseInt(target) || 0
        const step = Math.ceil(num / 40)
        const interval = setInterval(() => {
            counters.value[key] = Math.min(counters.value[key] + step, num)
            if (counters.value[key] >= num) clearInterval(interval)
        }, 30)
    }
    setTimeout(() => {
        animate('clients', 150)
        animate('projects', 300)
        animate('experience', 5)
    }, 500)
})

const currentTestimonial = ref(0)
const nextTestimonial = (testimonials) => { currentTestimonial.value = (currentTestimonial.value + 1) % testimonials.length }
const prevTestimonial = (testimonials) => { currentTestimonial.value = (currentTestimonial.value - 1 + testimonials.length) % testimonials.length }
</script>

<template>
    <AppLayout>
        <Head>
            <title>Easy IT - Network Solutions, Web Hosting & Development</title>
            <meta name="description" content="Easy IT provides professional network solutions, domain registration, web hosting, web development, and ISP solutions in Bangladesh." />
        </Head>

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-gray-900 via-indigo-900 to-gray-900 text-white overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25px 25px, rgba(255,255,255,0.15) 2px, transparent 0); background-size: 50px 50px;"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 lg:py-40">
                <div class="max-w-3xl">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight">
                        Your Trusted <span class="text-indigo-400">IT Partner</span> for Business Growth
                    </h1>
                    <p class="mt-6 text-lg sm:text-xl text-gray-300 leading-relaxed">
                        From network infrastructure to web development, we provide comprehensive IT solutions that keep your business connected and growing.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <Link href="/services" class="inline-flex items-center rounded-lg bg-indigo-600 px-6 py-3 text-sm font-medium text-white hover:bg-indigo-500 transition">
                            Our Services
                            <svg class="ml-2 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </Link>
                        <Link href="/service-request" class="inline-flex items-center rounded-lg border border-white/30 px-6 py-3 text-sm font-medium text-white hover:bg-white/10 transition">
                            Get a Quote
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading title="Our Services" subtitle="Comprehensive IT solutions tailored to your business needs" />
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <ServiceCard v-for="service in services" :key="service.id" :service="service" />
                </div>
            </div>
        </section>

        <!-- Stats Counter -->
        <section class="py-16 bg-indigo-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center text-white">
                    <div><div class="text-4xl font-bold">{{ counters.clients }}+</div><div class="mt-1 text-sm text-indigo-200">Happy Clients</div></div>
                    <div><div class="text-4xl font-bold">{{ counters.projects }}+</div><div class="mt-1 text-sm text-indigo-200">Projects Completed</div></div>
                    <div><div class="text-4xl font-bold">{{ counters.experience }}+</div><div class="mt-1 text-sm text-indigo-200">Years Experience</div></div>
                    <div><div class="text-4xl font-bold">{{ stats.support }}</div><div class="mt-1 text-sm text-indigo-200">Support Available</div></div>
                </div>
            </div>
        </section>

        <!-- Featured Projects -->
        <section v-if="featuredProjects?.length" class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading title="Featured Projects" subtitle="Some of our recent work" />
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <ProjectCard v-for="project in featuredProjects" :key="project.id" :project="project" />
                </div>
                <div class="text-center mt-8">
                    <Link href="/projects" class="inline-flex items-center text-indigo-600 font-medium hover:text-indigo-800 transition">
                        View All Projects <svg class="ml-1 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading title="Why Choose Easy IT?" subtitle="We're committed to delivering excellence" />
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-6"><div class="w-14 h-14 mx-auto bg-indigo-100 rounded-xl flex items-center justify-center text-2xl mb-4">🏆</div><h3 class="font-semibold text-gray-900 text-lg">Local Expertise</h3><p class="text-gray-500 mt-2 text-sm">Deep understanding of Bangladesh's IT infrastructure and business needs.</p></div>
                    <div class="text-center p-6"><div class="w-14 h-14 mx-auto bg-green-100 rounded-xl flex items-center justify-center text-2xl mb-4">⚡</div><h3 class="font-semibold text-gray-900 text-lg">Fast & Reliable</h3><p class="text-gray-500 mt-2 text-sm">Quick response times and reliable solutions that keep your business running.</p></div>
                    <div class="text-center p-6"><div class="w-14 h-14 mx-auto bg-orange-100 rounded-xl flex items-center justify-center text-2xl mb-4">🤝</div><h3 class="font-semibold text-gray-900 text-lg">24/7 Support</h3><p class="text-gray-500 mt-2 text-sm">Round-the-clock support to ensure your systems are always up and running.</p></div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section v-if="testimonials?.length" class="py-20">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <SectionHeading title="What Our Clients Say" />
                <div class="relative bg-white rounded-2xl shadow-lg p-8 mt-8">
                    <div class="text-yellow-400 text-xl mb-4">{{ '★'.repeat(testimonials[currentTestimonial]?.rating || 5) }}</div>
                    <p class="text-gray-600 text-lg italic leading-relaxed">"{{ testimonials[currentTestimonial]?.content }}"</p>
                    <div class="mt-6">
                        <p class="font-semibold text-gray-900">{{ testimonials[currentTestimonial]?.client_name }}</p>
                        <p class="text-sm text-gray-500">{{ testimonials[currentTestimonial]?.designation }} {{ testimonials[currentTestimonial]?.company ? `at ${testimonials[currentTestimonial].company}` : '' }}</p>
                    </div>
                    <div class="flex justify-center gap-2 mt-6">
                        <button @click="prevTestimonial(testimonials)" class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="nextTestimonial(testimonials)" class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Latest Posts -->
        <section v-if="latestPosts?.length" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading title="Latest from Our Blog" subtitle="Tips, tutorials, and industry insights" />
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <PostCard v-for="post in latestPosts" :key="post.id" :post="post" />
                </div>
            </div>
        </section>

        <!-- FAQ Preview -->
        <section v-if="faqs?.length" class="py-20">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading title="Frequently Asked Questions" />
                <FaqAccordion :faqs="faqs" />
                <div class="text-center mt-6">
                    <Link href="/faq" class="text-indigo-600 font-medium hover:text-indigo-800 transition">View All FAQs →</Link>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-16 bg-gradient-to-r from-indigo-600 to-indigo-800 text-white text-center">
            <div class="max-w-3xl mx-auto px-4">
                <h2 class="text-3xl font-bold">Ready to Get Started?</h2>
                <p class="mt-3 text-indigo-100">Let us help you build, connect, and grow your business with our IT solutions.</p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <Link href="/service-request" class="rounded-lg bg-white text-indigo-600 px-6 py-3 font-medium hover:bg-indigo-50 transition">Request a Quote</Link>
                    <Link href="/contact" class="rounded-lg border border-white/30 px-6 py-3 font-medium hover:bg-white/10 transition">Contact Us</Link>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
