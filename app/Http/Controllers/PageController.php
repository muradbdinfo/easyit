<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\TeamMember;
use App\Mail\ContactSubmitted;
use App\Mail\ServiceRequestSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class PageController extends Controller
{
    public function about()
    {
        return Inertia::render('Public/About', [
            'page' => Page::findBySlug('about'),
            'teamMembers' => TeamMember::active()->get(),
        ]);
    }

    public function contact()
    {
        return Inertia::render('Public/Contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $contact = Contact::create($validated);

        try {
            Mail::to(setting('company_email', 'info@easyit.com.bd'))
                ->send(new ContactSubmitted($contact));
        } catch (\Exception $e) {
            \Log::error('Contact email failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Thank you! Your message has been sent. We will get back to you soon.');
    }

    public function serviceRequest()
    {
        return Inertia::render('Public/ServiceRequest', [
            'services' => Service::active()->orderBy('sort_order')->get(['id', 'title']),
        ]);
    }

    public function submitServiceRequest(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'requirements' => 'required|string|max:10000',
            'budget_range' => 'nullable|string|max:100',
            'urgency' => 'required|in:low,medium,high',
        ]);

        $serviceRequest = ServiceRequest::create($validated);

        try {
            Mail::to(setting('company_email', 'info@easyit.com.bd'))
                ->send(new ServiceRequestSubmitted($serviceRequest));
        } catch (\Exception $e) {
            \Log::error('Service request email failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Your service request has been submitted! We will review and contact you shortly.');
    }

    public function faq()
    {
        return Inertia::render('Public/Faq', [
            'faqs' => Faq::active()
                ->with('service:id,title')
                ->get()
                ->groupBy(fn($faq) => $faq->service?->title ?? 'General'),
        ]);
    }

    // ── AdSense Required Legal Pages ──

    public function privacyPolicy()
    {
        return Inertia::render('Public/PrivacyPolicy', [
            'lastUpdated' => 'April 17, 2026',
        ]);
    }

    public function terms()
    {
        return Inertia::render('Public/Terms', [
            'lastUpdated' => 'April 17, 2026',
        ]);
    }

    public function disclaimer()
    {
        return Inertia::render('Public/Disclaimer', [
            'lastUpdated' => 'April 17, 2026',
        ]);
    }
}
