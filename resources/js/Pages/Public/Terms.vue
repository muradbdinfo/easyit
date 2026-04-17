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
const siteUrl = computed(() => settings.value.site_url || 'https://easyit.com.bd')
const lastUpdated = computed(() => page.props.lastUpdated || new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }))
</script>

<template>
    <AppLayout>
        <SeoHead
            title="Terms & Conditions"
            :description="`Read the terms and conditions of ${companyName}. Understand the rules and guidelines for using our website and services.`"
        />

        <!-- Hero -->
        <section class="bg-gradient-to-br from-gray-900 to-indigo-900 text-white py-16 sm:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl sm:text-4xl font-bold">Terms & Conditions</h1>
                <p class="mt-3 text-gray-300 text-sm sm:text-base">Last updated: {{ lastUpdated }}</p>
            </div>
        </section>

        <Breadcrumbs :items="[{ label: 'Terms & Conditions' }]" />

        <!-- Content -->
        <section class="py-12 sm:py-20">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600 prose-a:text-indigo-600 hover:prose-a:text-indigo-800">

                    <p>Welcome to <strong>{{ companyName }}</strong>. These Terms and Conditions govern your use of our website and the services we provide. By accessing or using our website, you agree to be bound by these terms. If you do not agree to these terms, please do not use our website or services.</p>

                    <h2>1. Definitions</h2>
                    <ul>
                        <li><strong>"Company," "we," "us," "our"</strong> refers to {{ companyName }}.</li>
                        <li><strong>"Website"</strong> refers to the {{ companyName }} website accessible at {{ siteUrl }}.</li>
                        <li><strong>"Services"</strong> refers to all IT services, products, and solutions offered by {{ companyName }}.</li>
                        <li><strong>"Client," "you," "your"</strong> refers to any individual or entity using our website or services.</li>
                        <li><strong>"Content"</strong> refers to all text, images, graphics, videos, and other materials on our website.</li>
                    </ul>

                    <h2>2. Use of Our Website</h2>
                    <h3>2.1 Acceptable Use</h3>
                    <p>You agree to use our website only for lawful purposes and in a manner that does not infringe the rights of, restrict, or inhibit anyone else's use of the website. You must not:</p>
                    <ul>
                        <li>Use the website in any way that violates any applicable local, national, or international law or regulation</li>
                        <li>Attempt to gain unauthorized access to any part of the website, its servers, or any connected systems</li>
                        <li>Introduce viruses, trojans, worms, or any other malicious software or code</li>
                        <li>Use automated tools, bots, or scripts to access or interact with the website without our prior written consent</li>
                        <li>Impersonate any person or entity, or falsely state or misrepresent your affiliation with a person or entity</li>
                        <li>Collect or harvest any personal information from other users of the website</li>
                    </ul>

                    <h3>2.2 Account Registration</h3>
                    <p>Some features of our website, including the client portal, require you to create an account. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. You agree to notify us immediately of any unauthorized use of your account.</p>

                    <h2>3. Services</h2>
                    <h3>3.1 Service Description</h3>
                    <p>{{ companyName }} provides a range of IT services including but not limited to network solutions, web hosting, domain registration, web development, server management, and ISP solutions. Specific terms for individual services may be outlined in separate service agreements.</p>

                    <h3>3.2 Service Availability</h3>
                    <p>We strive to maintain the availability and reliability of our services. However, we do not guarantee uninterrupted or error-free access to our services. We reserve the right to modify, suspend, or discontinue any service at any time with reasonable notice.</p>

                    <h3>3.3 Service Level</h3>
                    <p>For hosting and managed services, specific service levels, uptime guarantees, and support response times will be outlined in the applicable service agreement or plan description.</p>

                    <h2>4. Payment Terms</h2>
                    <ul>
                        <li>All fees for services are quoted in Bangladeshi Taka (BDT) unless otherwise stated</li>
                        <li>Payment is due as specified in the invoice issued by {{ companyName }}</li>
                        <li>Late payments may incur additional charges as outlined in the service agreement</li>
                        <li>We reserve the right to suspend services for overdue accounts after reasonable notice</li>
                        <li>All prices are subject to change with prior notice to existing clients</li>
                    </ul>

                    <h2>5. Intellectual Property</h2>
                    <h3>5.1 Our Content</h3>
                    <p>All content on our website, including text, graphics, logos, images, software, and design elements, is the property of {{ companyName }} or its content suppliers and is protected by copyright, trademark, and other intellectual property laws. You may not reproduce, distribute, modify, or create derivative works from any content without our prior written permission.</p>

                    <h3>5.2 Client Content</h3>
                    <p>You retain ownership of all content you submit to us. By submitting content through our forms, portal, or services, you grant us a non-exclusive license to use, store, and process such content as necessary to provide our services.</p>

                    <h2>6. Limitation of Liability</h2>
                    <p>To the maximum extent permitted by applicable law:</p>
                    <ul>
                        <li>{{ companyName }} shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or related to your use of our website or services</li>
                        <li>Our total liability for any claim arising from these terms or your use of our services shall not exceed the total fees paid by you to {{ companyName }} in the twelve (12) months preceding the claim</li>
                        <li>We are not liable for any loss or damage resulting from events beyond our reasonable control, including but not limited to natural disasters, internet outages, power failures, or cyberattacks</li>
                    </ul>

                    <h2>7. Indemnification</h2>
                    <p>You agree to indemnify, defend, and hold harmless {{ companyName }}, its officers, directors, employees, and agents from any claims, losses, liabilities, damages, costs, and expenses (including reasonable legal fees) arising out of your use of our website or services, your violation of these terms, or your infringement of any third-party rights.</p>

                    <h2>8. Termination</h2>
                    <p>We may terminate or suspend your access to our website and services immediately, without prior notice, if you breach these Terms and Conditions. Upon termination, your right to use our website and services will cease immediately. Provisions that by their nature should survive termination shall remain in effect.</p>

                    <h2>9. Privacy</h2>
                    <p>Your use of our website is also governed by our <a href="/privacy-policy">Privacy Policy</a>, which explains how we collect, use, and protect your personal information. By using our website, you consent to the practices described in our Privacy Policy.</p>

                    <h2>10. Third-Party Links</h2>
                    <p>Our website may contain links to third-party websites or services that are not owned or controlled by {{ companyName }}. We have no control over, and assume no responsibility for, the content, privacy policies, or practices of any third-party websites. You access third-party websites at your own risk.</p>

                    <h2>11. Disclaimers</h2>
                    <p>Our website and services are provided on an "as is" and "as available" basis without warranties of any kind, whether express or implied. We disclaim all warranties, including but not limited to implied warranties of merchantability, fitness for a particular purpose, and non-infringement.</p>

                    <h2>12. Governing Law</h2>
                    <p>These Terms and Conditions shall be governed by and construed in accordance with the laws of the People's Republic of Bangladesh. Any disputes arising from these terms shall be subject to the exclusive jurisdiction of the courts of Bangladesh.</p>

                    <h2>13. Changes to These Terms</h2>
                    <p>We reserve the right to modify or replace these Terms and Conditions at any time. Changes will be effective immediately upon posting on this page. Your continued use of our website after any changes constitutes your acceptance of the new terms. We encourage you to review these terms periodically.</p>

                    <h2>14. Severability</h2>
                    <p>If any provision of these Terms and Conditions is found to be invalid or unenforceable, the remaining provisions shall continue in full force and effect.</p>

                    <h2>15. Contact Us</h2>
                    <p>If you have any questions about these Terms and Conditions, please contact us:</p>
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
