<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class CommentController extends Controller
{
    /**
     * List all comments with filters.
     */
    public function index(Request $request)
    {
        $query = Comment::with([
            'post:id,title,slug',
            'user:id,name,email',
            'parent:id,body,author_name',
        ]);

        // Status filter
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('body', 'like', "%{$s}%")
                  ->orWhere('author_name', 'like', "%{$s}%")
                  ->orWhere('author_email', 'like', "%{$s}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$s}%"))
                  ->orWhereHas('post', fn($pq) => $pq->where('title', 'like', "%{$s}%"));
            });
        }

        // Post filter
        if ($request->post_id) {
            $query->where('post_id', $request->post_id);
        }

        $comments = $query->latest()->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'all'      => Comment::count(),
            'pending'  => Comment::where('status', 'pending')->count(),
            'approved' => Comment::where('status', 'approved')->count(),
            'spam'     => Comment::where('status', 'spam')->count(),
            'trash'    => Comment::where('status', 'trash')->count(),
        ];

        return Inertia::render('Admin/Comments/Index', [
            'comments' => $comments,
            'stats'    => $stats,
            'filters'  => $request->only('status', 'search', 'post_id'),
            'posts'    => Post::orderBy('title')->get(['id', 'title']),
        ]);
    }

    /**
     * Show single comment detail.
     */
    public function show(Comment $comment)
    {
        $comment->load([
            'post:id,title,slug',
            'user:id,name,email',
            'parent:id,body,author_name,author_email,user_id',
            'parent.user:id,name',
            'replies' => fn($q) => $q->with('user:id,name')->latest(),
        ]);

        return Inertia::render('Admin/Comments/Show', [
            'comment' => $comment,
        ]);
    }

    /**
     * Approve a comment.
     */
    public function approve(Comment $comment)
    {
        $comment->update(['status' => 'approved']);
        Cache::forget("post_{$comment->post_id}_comments");
        return back()->with('success', 'Comment approved.');
    }

    /**
     * Mark comment as spam.
     */
    public function spam(Comment $comment)
    {
        $comment->update(['status' => 'spam']);
        Cache::forget("post_{$comment->post_id}_comments");
        return back()->with('success', 'Comment marked as spam.');
    }

    /**
     * Move comment to trash.
     */
    public function trash(Comment $comment)
    {
        $comment->update(['status' => 'trash']);
        Cache::forget("post_{$comment->post_id}_comments");
        return back()->with('success', 'Comment trashed.');
    }

    /**
     * Restore comment to pending.
     */
    public function restore(Comment $comment)
    {
        $comment->update(['status' => 'pending']);
        Cache::forget("post_{$comment->post_id}_comments");
        return back()->with('success', 'Comment restored to pending.');
    }

    /**
     * Permanently delete a comment.
     */
    public function destroy(Comment $comment)
    {
        $postId = $comment->post_id;
        $comment->delete();
        Cache::forget("post_{$postId}_comments");
        return back()->with('success', 'Comment permanently deleted.');
    }

    /**
     * Bulk actions on multiple comments.
     */
    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'exists:comments,id',
            'action' => 'required|in:approve,spam,trash,delete',
        ]);

        $comments = Comment::whereIn('id', $validated['ids'])->get();
        $postIds = $comments->pluck('post_id')->unique();

        switch ($validated['action']) {
            case 'approve':
                Comment::whereIn('id', $validated['ids'])->update(['status' => 'approved']);
                break;
            case 'spam':
                Comment::whereIn('id', $validated['ids'])->update(['status' => 'spam']);
                break;
            case 'trash':
                Comment::whereIn('id', $validated['ids'])->update(['status' => 'trash']);
                break;
            case 'delete':
                Comment::whereIn('id', $validated['ids'])->delete();
                break;
        }

        // Clear cache for all affected posts
        foreach ($postIds as $pid) {
            Cache::forget("post_{$pid}_comments");
        }

        return back()->with('success', count($validated['ids']) . ' comment(s) updated.');
    }

    /**
     * Admin reply to a comment directly from admin panel.
     */
    public function reply(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'body' => 'required|string|min:3|max:2000',
        ]);

        Comment::create([
            'post_id'        => $comment->post_id,
            'user_id'        => $request->user()->id,
            'parent_id'      => $comment->id,
            'body'           => strip_tags($validated['body']),
            'status'         => 'approved',
            'is_admin_reply' => true,
            'ip_address'     => $request->ip(),
        ]);

        Cache::forget("post_{$comment->post_id}_comments");

        return back()->with('success', 'Reply posted.');
    }
}
