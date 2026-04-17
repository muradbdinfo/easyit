<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'post_id', 'user_id', 'parent_id',
        'author_name', 'author_email', 'body',
        'status', 'is_admin_reply', 'ip_address', 'user_agent',
    ];

    protected $casts = [
        'is_admin_reply' => 'boolean',
    ];

    // ── Relationships ──

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function approvedReplies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->where('status', 'approved')
            ->with(['user:id,name,avatar', 'approvedReplies'])
            ->orderBy('created_at', 'asc');
    }

    // ── Scopes ──

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ── Accessors ──

    public function getDisplayNameAttribute(): string
    {
        return $this->user?->name ?? $this->author_name ?? 'Anonymous';
    }

    public function getAvatarInitialAttribute(): string
    {
        return strtoupper(substr($this->display_name, 0, 1));
    }
}
