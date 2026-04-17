<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import SeoHead from '@/Components/Public/SeoHead.vue'
import Breadcrumbs from '@/Components/Public/Breadcrumbs.vue'

const page = usePage()
const settings = computed(() => page.props.settings || {})
const companyName = computed(() => settings.value.company_name || 'Easy IT')
const companyEmail = computed(() => settings.value.company_email || 'info@easyit.com.bd')
const companyAddress = computed(() => settings.value.company_address || 'Sandwip, Chittagong, Bangladesh')
const lastUpdated = computed(() => page.props.lastUpdated || new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }))
</script>

<template>
    <AppLayout>
        <SeoHead
            title="Disclaimer"
            :description="`Read the disclaimer of ${companyName}. Understand the limitations and terms regarding the information provided on our website.`"
        />

        <!-- Hero -->
        <section class="bg-gradient-to-br from-gray-900 to-indigo-900 text-white py-16 sm:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl sm:text-4xl font-bold">Disclaimer</h1>
                <p class="mt-3 text-gray-300 text-sm sm:text-base">Last updated: {{ lastUpdated }}</p>
            </div>
        </section>

        <Breadcrumbs :items="[{ label: 'Disclaimer' }]" />

        <!-- Content -->
        <section class="py-12 sm:py-20">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600 prose-a:text-indigo-600 hover:prose-a:text-indigo-800">

                    <p>The information provided by <strong>{{ companyName }}</strong> on our website is for general informational purposes only. All information on the site is provided in good faith; however, we make no representation or warranty of any kind, express or implied, regarding the accuracy, adequacy, validity, reliability, availability, or completeness of any information on our website.</p>

                    <h2>1. General Information</h2>
                    <p>The content on our website, including but not limited to text, graphics, images, and other material, is intended for informational purposes only. It does not constitute professional advice. We recommend that you seek professional consultation for specific issues related to your business or technology needs.</p>

                    <h2>2. No Professional Advice</h2>
                    <p>The website does not provide professional, legal, financial, or technical advice. The information is provided as a general guide and should not be relied upon as a substitute for professional consultation. Any reliance you place on such information is strictly at your own risk.</p>

                    <h2>3. Service Information</h2>
                    <p>While we strive to keep the information on our website up-to-date and accurate, service descriptions, pricing, features, and availability are subject to change without notice. The service information displayed on our website is intended as a general overview and may not reflect the most current offerings. For the most accurate and up-to-date information about our services and pricing, please contact us directly.</p>

                    <h2>4. External Links</h2>
                    <p>Our website may contain links to external websites that are not provided or maintained by {{ companyName }}. We do not guarantee the accuracy, relevance, timeliness, or completeness of any information on these external websites. The inclusion of any links does not necessarily imply a recommendation or endorsement of the views expressed within them.</p>

                    <h2>5. Testimonials & Reviews</h2>
                    <p>Testimonials and reviews displayed on our website represent the individual experiences and opinions of our clients. These testimonials are not intended to represent or guarantee that every client will achieve the same or similar results. Individual results may vary depending on specific circumstances, requirements, and factors unique to each client.</p>

                    <h2>6. Advertising Disclaimer</h2>
                    <p>Our website may display advertisements provided by third-party advertising networks, including Google AdSense. These advertisements are served by third-party companies and are not endorsements by {{ companyName }}. We do not control the content of these advertisements and are not responsible for the claims made in them. The appearance of advertisements on our website does not constitute an endorsement, recommendation, or guarantee of the advertised products or services.</p>

                    <h2>7. Technical Accuracy</h2>
                    <p>While we make every effort to ensure that technical information on our website is accurate, technology evolves rapidly. We cannot guarantee that all technical details, specifications, or procedures mentioned on our website are current or applicable to your specific situation.</p>

                    <h2>8. Availability</h2>
                    <p>We do not warrant that our website will be available at all times. We may experience hardware, software, or other problems, or need to perform maintenance related to the website, resulting in interruptions, delays, or errors. We reserve the right to change, revise, update, suspend, discontinue, or otherwise modify the website at any time without notice.</p>

                    <h2>9. Limitation of Liability</h2>
                    <p>Under no circumstance shall {{ companyName }} be liable for any direct, indirect, incidental, consequential, special, or exemplary damages arising out of or in connection with your use of the website or any information provided on the website. This limitation applies regardless of whether the damages arise from the use or misuse of the website, from the inability to use the website, or from the interruption, suspension, modification, alteration, or termination of the website.</p>

                    <h2>10. Fair Use Notice</h2>
                    <p>Our website may contain copyrighted material, the use of which has not always been specifically authorized by the copyright owner. We believe this constitutes "fair use" of any such copyrighted material as provided for in applicable copyright law. If you wish to use copyrighted material from our website for purposes that go beyond fair use, you must obtain permission from the copyright owner.</p>

                    <h2>11. Errors and Omissions</h2>
                    <p>While we have made every attempt to ensure that the information contained on our website is correct, {{ companyName }} is not responsible for any errors or omissions, or for the results obtained from the use of this information. All information on the website is provided "as is," with no guarantee of completeness, accuracy, or timeliness.</p>

                    <h2>12. Contact Us</h2>
                    <p>If you require any more information or have any questions about our disclaimer, please feel free to contact us:</p>
                    <div class="bg-gray-50 rounded-xl p-6 not-prose">
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><strong class="text-gray-900">{{ companyName }}</strong></p>
                            <p>Email: <a :href="`mailto:${companyEmail}`" class="text-indigo-600 hover:text-indigo-800">{{ companyEmail }}</a></p>
                            <p>Address: {{ companyAddress }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
