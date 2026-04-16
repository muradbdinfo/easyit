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

// Scroll-triggered animations
const visible = ref({})
onMounted(() => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) visible.value[e.target.id] = true })
    }, { threshold: 0.1 })
    document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el))
})

// Animated counters
const counters = ref({ clients: 0, projects: 0, experience: 0 })
onMounted(() => {
    const animate = (key, target) => {
        const num = parseInt(target) || 0
        const step = Math.ceil(num / 35)
        const interval = setInterval(() => {
            counters.value[key] = Math.min(counters.value[key] + step, num)
            if (counters.value[key] >= num) clearInterval(interval)
        }, 30)
    }
    setTimeout(() => { animate('clients', 150); animate('projects', 300); animate('experience', 5) }, 800)
})

const currentT = ref(0)
const nextT = (t) => { currentT.value = (currentT.value + 1) % t.length }
const prevT = (t) => { currentT.value = (currentT.value - 1 + t.length) % t.length }
</script>

<template>
    <AppLayout>
        <Head>
            <title>Easy IT - Professional IT Solutions in Bangladesh</title>
            <meta name="description" content="Easy IT provides network solutions, web hosting, domain registration, web development, and ISP solutions in Bangladesh." />
        </Head>

        <!-- ═══ HERO ═══ -->
        <section class="relative min-h-[90vh] flex items-center bg-navy-950 overflow-hidden">
            <!-- Animated background -->
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-br from-navy-950 via-navy-900 to-brand-950"></div>
                <div class="absolute top-1/4 -left-32 w-96 h-96 bg-brand-500/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-1/4 -right-32 w-96 h-96 bg-brand-500/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s"></div>
                <!-- Grid pattern -->
                <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.1) 1px, transparent 1px); background-size: 60px 60px;"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 lg:py-40">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 bg-brand-500/10 border border-brand-500/20 rounded-full px-4 py-1.5 mb-6 animate-fade-in">
                        <span class="w-2 h-2 rounded-full bg-brand-400 animate-pulse"></span>
                        <span class="text-brand-300 text-xs font-semibold tracking-wide">Trusted IT Partner Since 2020</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.1] tracking-tight animate-fade-up">
                        Building Digital
                        <span class="relative">
                            <span class="bg-gradient-to-r from-brand-400 to-cyan-300 bg-clip-text text-transparent"> Infrastructure</span>
                        </span>
                        <br/>That Powers Your Business
                    </h1>

                    <p class="mt-6 text-lg sm:text-xl text-gray-400 leading-relaxed max-w-xl animate-fade-up" style="animation-delay: 0.1s">
                        From network architecture to web development — enterprise-grade IT solutions designed for businesses in Bangladesh.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4 animate-fade-up" style="animation-delay: 0.2s">
                        <Link href="/services" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-7 py-3.5 text-sm font-bold text-white hover:from-brand-600 hover:to-brand-700 transition-all shadow-xl shadow-brand-500/25 hover:shadow-brand-500/40 hover:-translate-y-0.5">
                            Explore Services
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </Link>
                        <Link href="/contact" class="inline-flex items-center gap-2 rounded-xl border border-white/20 px-7 py-3.5 text-sm font-semibold text-gray-300 hover:bg-white/5 hover:text-white hover:border-white/30 transition-all">
                            Contact Us
                        </Link>
                    </div>

                    <!-- Trust badges -->
                    <div class="mt-12 flex items-center gap-6 text-sm text-gray-500 animate-fade-up" style="animation-delay: 0.3s">
                        <div class="flex items-center gap-2"><svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 24/7 Support</div>
                        <div class="flex items-center gap-2"><svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 99.9% Uptime</div>
                        <div class="flex items-center gap-2"><svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Local Experts</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ SERVICES ═══ -->
        <section class="py-20 sm:py-28 bg-gray-50/50" id="services" data-animate>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading tag="What We Do" title="Solutions That Drive Results" subtitle="From network infrastructure to web applications — we provide end-to-end IT services tailored to your needs." />
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <ServiceCard v-for="(service, i) in services" :key="service.id" :service="service" :style="{ animationDelay: `${i * 0.05}s` }" class="animate-fade-up" />
                </div>
            </div>
        </section>

        <!-- ═══ STATS ═══ -->
        <section class="relative py-20 bg-navy-950 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-brand-600/10 via-transparent to-brand-600/10"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="text-center group">
                        <div class="text-4xl sm:text-5xl font-extrabold text-white mb-1">{{ counters.clients }}<span class="text-brand-400">+</span></div>
                        <div class="text-sm text-gray-500 font-medium tracking-wide">Happy Clients</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl sm:text-5xl font-extrabold text-white mb-1">{{ counters.projects }}<span class="text-brand-400">+</span></div>
                        <div class="text-sm text-gray-500 font-medium tracking-wide">Projects Delivered</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl sm:text-5xl font-extrabold text-white mb-1">{{ counters.experience }}<span class="text-brand-400">+</span></div>
                        <div class="text-sm text-gray-500 font-medium tracking-wide">Years Experience</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl sm:text-5xl font-extrabold text-white mb-1">{{ stats.support }}</div>
                        <div class="text-sm text-gray-500 font-medium tracking-wide">Support Available</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ WHY CHOOSE US ═══ -->
        <section class="py-20 sm:py-28">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading tag="Why Easy IT" title="Your Success Is Our Priority" subtitle="We combine local expertise with global standards to deliver IT solutions you can rely on." />
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                    <div class="relative bg-white rounded-2xl border border-gray-100 p-8 hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300 group hover:-translate-y-1">
                        <div class="w-14 h-14 bg-gradient-to-br from-brand-50 to-cyan-50 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-navy-900 mb-2">Reliable & Secure</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Enterprise-grade infrastructure with 99.9% uptime guarantee and robust security protocols.</p>
                    </div>
                    <div class="relative bg-white rounded-2xl border border-gray-100 p-8 hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300 group hover:-translate-y-1">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-navy-900 mb-2">Fast Delivery</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Quick turnaround without compromising quality. We understand the urgency of your business needs.</p>
                    </div>
                    <div class="relative bg-white rounded-2xl border border-gray-100 p-8 hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300 group hover:-translate-y-1">
                        <div class="w-14 h-14 bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-navy-900 mb-2">Local Expertise</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Deep understanding of Bangladesh's IT landscape. On-site support when you need it most.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ FEATURED PROJECTS ═══ -->
        <section v-if="featuredProjects?.length" class="py-20 sm:py-28 bg-gray-50/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading tag="Our Work" title="Featured Projects" subtitle="A selection of our recent work across various industries." />
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <ProjectCard v-for="p in featuredProjects" :key="p.id" :project="p" />
                </div>
                <div class="text-center mt-10">
                    <Link href="/projects" class="inline-flex items-center gap-2 text-sm font-bold text-brand-600 hover:text-brand-700 group transition">
                        View All Projects
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ═══ TESTIMONIALS ═══ -->
        <section v-if="testimonials?.length" class="py-20 sm:py-28">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading tag="Testimonials" title="Trusted by Businesses" />
                <div class="relative bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/30 p-8 sm:p-12">
                    <svg class="absolute top-6 left-8 w-10 h-10 text-brand-100" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    <div class="relative text-center">
                        <div class="flex justify-center gap-0.5 mb-6">
                            <svg v-for="i in 5" :key="i" :class="[i <= (testimonials[currentT]?.rating || 5) ? 'text-amber-400' : 'text-gray-200', 'w-5 h-5']" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-lg sm:text-xl text-gray-700 leading-relaxed italic">"{{ testimonials[currentT]?.content }}"</p>
                        <div class="mt-6">
                            <p class="font-bold text-navy-900">{{ testimonials[currentT]?.client_name }}</p>
                            <p class="text-sm text-gray-500">{{ testimonials[currentT]?.designation }}{{ testimonials[currentT]?.company ? `, ${testimonials[currentT].company}` : '' }}</p>
                        </div>
                    </div>
                    <!-- Nav dots -->
                    <div class="flex justify-center items-center gap-3 mt-8">
                        <button @click="prevT(testimonials)" class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center hover:bg-brand-50 hover:border-brand-200 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <div class="flex gap-1.5">
                            <button v-for="(_, i) in testimonials" :key="i" @click="currentT = i"
                                :class="[currentT === i ? 'w-6 bg-brand-500' : 'w-2 bg-gray-200 hover:bg-gray-300', 'h-2 rounded-full transition-all duration-300']" />
                        </div>
                        <button @click="nextT(testimonials)" class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center hover:bg-brand-50 hover:border-brand-200 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ BLOG ═══ -->
        <section v-if="latestPosts?.length" class="py-20 sm:py-28 bg-gray-50/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading tag="Blog" title="Latest Insights" subtitle="Tips, tutorials, and industry news from our team." />
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <PostCard v-for="post in latestPosts" :key="post.id" :post="post" />
                </div>
            </div>
        </section>

        <!-- ═══ FAQ ═══ -->
        <section v-if="faqs?.length" class="py-20 sm:py-28">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <SectionHeading tag="FAQ" title="Common Questions" />
                <FaqAccordion :faqs="faqs" />
                <div class="text-center mt-8">
                    <Link href="/faq" class="text-sm font-semibold text-brand-600 hover:text-brand-700 transition">View All FAQs →</Link>
                </div>
            </div>
        </section>

        <!-- ═══ CTA ═══ -->
        <section class="relative py-20 bg-navy-950 overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute top-0 left-1/4 w-64 h-64 bg-brand-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-1/4 w-64 h-64 bg-brand-500/5 rounded-full blur-3xl"></div>
            </div>
            <div class="relative max-w-3xl mx-auto px-4 text-center">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Ready to Transform Your IT?</h2>
                <p class="mt-4 text-lg text-gray-400">Let's build something great together. Get in touch for a free consultation.</p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <Link href="/service-request" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 px-8 py-4 text-sm font-bold text-white hover:from-brand-600 hover:to-brand-700 transition-all shadow-xl shadow-brand-500/25 hover:shadow-brand-500/40 hover:-translate-y-0.5">
                        Request a Quote
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </Link>
                    <Link href="/contact" class="inline-flex items-center rounded-xl border border-white/20 px-8 py-4 text-sm font-semibold text-gray-300 hover:bg-white/5 hover:text-white transition-all">
                        Contact Us
                    </Link>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
