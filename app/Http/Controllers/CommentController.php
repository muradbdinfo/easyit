<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\NewCommentNotification;
use App\Services\SpamProtection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function __construct(
        protected SpamProtection $spamProtection
    ) {}

    /**
     * Store a new comment on a blog post.
     */
    public function store(Request $request, Post $post)
    {
        // Check if comments are enabled
        if (!Setting::get('comments_enabled', '1')) {
            return back()->with('error', 'Comments are currently disabled.');
        }

        // Check if guest commenting is allowed
        $isGuest = !$request->user();
        if ($isGuest && !Setting::get('comments_allow_guests', '1')) {
            return back()->with('error', 'Please log in to comment.');
        }

        // Only published posts
        if ($post->status !== 'published') {
            abort(404);
        }

        // Validate
        $maxDepth = (int) Setting::get('comments_max_depth', '3');
        $rules = [
            'body'      => 'required|string|min:3|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
            'website'   => 'nullable|max:0', // Honeypot — must be empty
        ];

        if ($isGuest) {
            $rules['author_name']  = 'required|string|max:100';
            $rules['author_email'] = 'required|email|max:255';
        }

        $validated = $request->validate($rules);

        // ═══ SPAM PROTECTION ═══

        // Hard spam check — silently reject (return fake success to fool bots)
        if ($this->spamProtection->isSpam($request, $validated)) {
            Log::channel('single')->info('Spam comment blocked', [
                'ip'      => $request->ip(),
                'reasons' => $this->spamProtection->getReasons(),
                'post_id' => $post->id,
                'body'    => \Str::limit($validated['body'], 100),
            ]);

            // Return fake success — don't let bots know they were caught
            return back()->with('success', 'Comment submitted and awaiting approval.');
        }

        // Check reply depth
        if (!empty($validated['parent_id'])) {
            $parent = Comment::find($validated['parent_id']);
            if ($parent && $parent->post_id !== $post->id) {
                abort(403);
            }
            $depth = 0;
            $current = $parent;
            while ($current && $current->parent_id) {
                $depth++;
                $current = $current->parent;
                if ($depth >= $maxDepth) break;
            }
            if ($depth >= $maxDepth) {
                return back()->with('error', 'Maximum reply depth reached.');
            }
        }

        // Determine status
        $requireApproval = Setting::get('comments_require_approval', '1');
        $isAdmin = $request->user() && $request->user()->hasRole('admin');

        // Soft spam check — force pending even if approval is off
        $forcePending = !$isAdmin && $this->spamProtection->isLikelySpam($validated);

        if ($isAdmin) {
            $status = 'approved';
        } elseif ($forcePending || $requireApproval) {
            $status = 'pending';
        } else {
            $status = 'approved';
        }

        $comment = Comment::create([
            'post_id'        => $post->id,
            'user_id'        => $request->user()?->id,
            'parent_id'      => $validated['parent_id'] ?? null,
            'author_name'    => $isGuest ? $validated['author_name'] : null,
            'author_email'   => $isGuest ? $validated['author_email'] : null,
            'body'           => strip_tags($validated['body']),
            'status'         => $status,
            'is_admin_reply' => $isAdmin,
            'ip_address'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
        ]);

        // Notify admin if enabled
        if (!$isAdmin && Setting::get('comments_notify_admin', '1')) {
            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewCommentNotification($comment));
            }
        }

        // Clear comments cache
        Cache::forget("post_{$post->id}_comments");

        $message = $comment->status === 'approved'
            ? 'Comment posted successfully!'
            : 'Comment submitted and awaiting approval.';

        return back()->with('success', $message);
    }
}