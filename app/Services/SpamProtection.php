<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SpamProtection
{
    protected array $reasons = [];

    /**
     * Run all spam checks. Returns true if spam detected.
     */
    public function isSpam(Request $request, array $data): bool
    {
        $this->reasons = [];

        // Layer 1: Honeypot — bot filled a hidden field
        if ($this->failsHoneypot($request)) {
            return true;
        }

        // Layer 2: Time trap — form submitted too fast (< 3 seconds)
        if ($this->failsTimeTrap($request)) {
            return true;
        }

        // Layer 3: Duplicate content — same body from same IP within 1 hour
        if ($this->isDuplicate($request, $data)) {
            $this->reasons[] = 'duplicate';
            return true;
        }

        // Layer 4: Link spam — too many URLs in comment body
        if ($this->hasExcessiveLinks($data['body'])) {
            $this->reasons[] = 'excessive_links';
            return true;
        }

        // Layer 5: IP flood — too many comments from same IP in short time
        if ($this->isIpFlooding($request)) {
            $this->reasons[] = 'ip_flood';
            return true;
        }

        // Layer 6: Keyword blacklist
        if ($this->hasBlacklistedContent($data)) {
            $this->reasons[] = 'blacklisted_content';
            return true;
        }

        return false;
    }

    /**
     * Determine if comment is likely spam (soft check — auto-mark as pending).
     */
    public function isLikelySpam(array $data): bool
    {
        // Has any links at all (not necessarily excessive)
        if (preg_match_all('/https?:\/\//i', $data['body']) >= 2) {
            return true;
        }

        // Very short comment with a link
        if (strlen($data['body']) < 30 && str_contains($data['body'], 'http')) {
            return true;
        }

        // ALL CAPS
        $upper = preg_replace('/[^A-Z]/', '', $data['body']);
        $alpha = preg_replace('/[^a-zA-Z]/', '', $data['body']);
        if (strlen($alpha) > 10 && strlen($upper) / strlen($alpha) > 0.7) {
            return true;
        }

        return false;
    }

    /**
     * Get rejection reasons for logging.
     */
    public function getReasons(): array
    {
        return $this->reasons;
    }

    // ── Layer 1: Honeypot ──
    protected function failsHoneypot(Request $request): bool
    {
        // If the hidden "website" field has any value, it's a bot
        if ($request->filled('website')) {
            $this->reasons[] = 'honeypot';
            return true;
        }
        return false;
    }

    // ── Layer 2: Time Trap ──
    protected function failsTimeTrap(Request $request): bool
    {
        $loadedAt = $request->input('_loaded_at');
        if (!$loadedAt) {
            // No timestamp = suspicious but not definitive
            return false;
        }

        $elapsed = time() - (int) $loadedAt;

        // Less than 3 seconds = bot speed
        if ($elapsed < 3) {
            $this->reasons[] = 'too_fast';
            return true;
        }

        return false;
    }

    // ── Layer 3: Duplicate Detection ──
    protected function isDuplicate(Request $request, array $data): bool
    {
        $hash = md5($request->ip() . '|' . trim(strtolower($data['body'])));
        $cacheKey = "comment_dup_{$hash}";

        if (Cache::has($cacheKey)) {
            return true;
        }

        // Store for 1 hour
        Cache::put($cacheKey, true, 3600);
        return false;
    }

    // ── Layer 4: Link Spam ──
    protected function hasExcessiveLinks(string $body): bool
    {
        $linkCount = preg_match_all('/https?:\/\/|www\./i', $body);
        return $linkCount >= 3;
    }

    // ── Layer 5: IP Flood ──
    protected function isIpFlooding(Request $request): bool
    {
        $cacheKey = "comment_flood_{$request->ip()}";
        $count = (int) Cache::get($cacheKey, 0);

        if ($count >= 10) {
            return true; // 10 comments in 1 hour from same IP
        }

        Cache::put($cacheKey, $count + 1, 3600);
        return false;
    }

    // ── Layer 6: Keyword Blacklist ──
    protected function hasBlacklistedContent(array $data): bool
    {
        $blacklist = [
            // Common spam keywords
            'buy now', 'click here', 'free money', 'earn money fast',
            'casino online', 'poker online', 'viagra', 'cialis',
            'cryptocurrency investment', 'make money online',
            'cheap followers', 'buy followers', 'seo service',
            'payday loan', 'weight loss pill', 'enlargement',
            // Pharma spam
            'tramadol', 'ambien', 'xanax',
            // Crypto scam
            'bitcoin profit', 'crypto trading bot', 'guaranteed return',
        ];

        $text = strtolower(
            ($data['body'] ?? '') . ' ' .
            ($data['author_name'] ?? '') . ' ' .
            ($data['author_email'] ?? '')
        );

        foreach ($blacklist as $keyword) {
            if (str_contains($text, $keyword)) {
                return true;
            }
        }

        // Check for disposable email domains (common spam sources)
        $email = strtolower($data['author_email'] ?? '');
        if ($email) {
            $disposableDomains = [
                'mailinator.com', 'guerrillamail.com', 'tempmail.com',
                'throwaway.email', 'yopmail.com', 'sharklasers.com',
                'guerrillamailblock.com', 'grr.la', 'dispostable.com',
                'trashmail.com', 'maildrop.cc', 'fakeinbox.com',
            ];
            $domain = substr($email, strpos($email, '@') + 1);
            if (in_array($domain, $disposableDomains)) {
                return true;
            }
        }

        return false;
    }
}