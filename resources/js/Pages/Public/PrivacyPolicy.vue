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
            title="Privacy Policy"
            :description="`Read the privacy policy of ${companyName}. Learn how we collect, use, and protect your personal information.`"
        />

        <!-- Hero -->
        <section class="bg-gradient-to-br from-gray-900 to-indigo-900 text-white py-16 sm:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl sm:text-4xl font-bold">Privacy Policy</h1>
                <p class="mt-3 text-gray-300 text-sm sm:text-base">Last updated: {{ lastUpdated }}</p>
            </div>
        </section>

        <Breadcrumbs :items="[{ label: 'Privacy Policy' }]" />

        <!-- Content -->
        <section class="py-12 sm:py-20">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-p:text-gray-600 prose-li:text-gray-600 prose-a:text-indigo-600 hover:prose-a:text-indigo-800">

                    <p>At <strong>{{ companyName }}</strong>, we are committed to protecting and respecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website and use our services.</p>

                    <h2>1. Information We Collect</h2>

                    <h3>1.1 Personal Information</h3>
                    <p>We may collect personal information that you voluntarily provide to us when you:</p>
                    <ul>
                        <li>Fill out a contact form or service request form</li>
                        <li>Register for an account on our client portal</li>
                        <li>Subscribe to our newsletter or marketing communications</li>
                        <li>Request a quote or make an inquiry about our services</li>
                        <li>Make a payment for our services</li>
                    </ul>
                    <p>This information may include your name, email address, phone number, company name, mailing address, and payment details.</p>

                    <h3>1.2 Automatically Collected Information</h3>
                    <p>When you visit our website, we may automatically collect certain information about your device and browsing activity, including:</p>
                    <ul>
                        <li>IP address and geographic location</li>
                        <li>Browser type and version</li>
                        <li>Operating system</li>
                        <li>Referring website URLs</li>
                        <li>Pages visited and time spent on each page</li>
                        <li>Date and time of visits</li>
                    </ul>

                    <h3>1.3 Cookies and Tracking Technologies</h3>
                    <p>We use cookies, web beacons, and similar tracking technologies to enhance your experience on our website. These technologies help us analyze website traffic, remember your preferences, and improve our services. You can control cookie settings through your browser preferences.</p>

                    <h2>2. How We Use Your Information</h2>
                    <p>We use the collected information for the following purposes:</p>
                    <ul>
                        <li>To provide, operate, and maintain our services</li>
                        <li>To process and fulfill service requests and transactions</li>
                        <li>To communicate with you regarding your inquiries, projects, and support tickets</li>
                        <li>To send administrative information, invoices, and service updates</li>
                        <li>To improve our website, services, and customer experience</li>
                        <li>To analyze usage trends and monitor website performance</li>
                        <li>To send marketing and promotional communications (with your consent)</li>
                        <li>To detect, prevent, and address technical issues or fraud</li>
                        <li>To comply with legal obligations</li>
                    </ul>

                    <h2>3. Information Sharing and Disclosure</h2>
                    <p>We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:</p>
                    <ul>
                        <li><strong>Service Providers:</strong> We may share information with trusted third-party service providers who assist us in operating our website, conducting our business, or servicing you (e.g., hosting providers, payment processors, email services).</li>
                        <li><strong>Legal Requirements:</strong> We may disclose your information if required by law, court order, or governmental regulation, or if we believe disclosure is necessary to protect our rights, your safety, or the safety of others.</li>
                        <li><strong>Business Transfers:</strong> In the event of a merger, acquisition, or sale of assets, your information may be transferred as part of that transaction.</li>
                    </ul>

                    <h2>4. Data Security</h2>
                    <p>We implement appropriate technical and organizational security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction. These measures include SSL encryption, secure servers, access controls, and regular security assessments. However, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.</p>

                    <h2>5. Data Retention</h2>
                    <p>We retain your personal information only for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required or permitted by law. When your information is no longer needed, we will securely delete or anonymize it.</p>

                    <h2>6. Third-Party Services</h2>
                    <p>Our website may use third-party services such as:</p>
                    <ul>
                        <li><strong>Google Analytics:</strong> To analyze website traffic and user behavior. Google Analytics collects data through cookies. You can opt out by installing the <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener noreferrer">Google Analytics Opt-out Browser Add-on</a>.</li>
                        <li><strong>Google AdSense:</strong> To display advertisements on our website. Google AdSense uses cookies to serve ads based on your prior visits. You can opt out of personalized advertising by visiting <a href="https://www.google.com/settings/ads" target="_blank" rel="noopener noreferrer">Google Ads Settings</a>.</li>
                        <li><strong>Payment Gateways:</strong> To process payments securely. Payment information is handled directly by our payment processor and is not stored on our servers.</li>
                    </ul>

                    <h2>7. Your Rights</h2>
                    <p>You have the following rights regarding your personal information:</p>
                    <ul>
                        <li><strong>Access:</strong> You may request a copy of the personal information we hold about you.</li>
                        <li><strong>Correction:</strong> You may request that we correct any inaccurate or incomplete information.</li>
                        <li><strong>Deletion:</strong> You may request that we delete your personal information, subject to certain legal exceptions.</li>
                        <li><strong>Opt-Out:</strong> You may opt out of receiving marketing communications at any time by clicking the "unsubscribe" link in our emails or contacting us directly.</li>
                        <li><strong>Data Portability:</strong> You may request your personal information in a structured, commonly used format.</li>
                    </ul>

                    <h2>8. Children's Privacy</h2>
                    <p>Our website and services are not directed to children under the age of 18. We do not knowingly collect personal information from children. If we become aware that we have collected personal information from a child, we will take steps to delete such information promptly.</p>

                    <h2>9. Links to Other Websites</h2>
                    <p>Our website may contain links to third-party websites. We are not responsible for the privacy practices or content of those websites. We encourage you to review the privacy policies of any third-party websites you visit.</p>

                    <h2>10. Changes to This Policy</h2>
                    <p>We may update this Privacy Policy from time to time to reflect changes in our practices or legal requirements. We will notify you of any material changes by posting the updated policy on this page with a revised "Last updated" date. We encourage you to review this policy periodically.</p>

                    <h2>11. Contact Us</h2>
                    <p>If you have any questions, concerns, or requests regarding this Privacy Policy, please contact us:</p>
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
