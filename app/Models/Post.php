<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'excerpt',
        'body', 'featured_image', 'status', 'published_at', 'views',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')->singleFile();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function getReadingTimeAttribute(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->body)) / 200));
    }

    public function comments(): HasMany
{
    return $this->hasMany(\App\Models\Comment::class);
}
 
// Only top-level approved comments with nested approved replies
public function approvedComments(): HasMany
{
    return $this->hasMany(\App\Models\Comment::class)
        ->whereNull('parent_id')
        ->where('status', 'approved')
        ->with(['user:id,name,avatar', 'approvedReplies'])
        ->orderBy('created_at', 'desc');
}
 
// Count of approved comments (including replies)
public function getCommentsCountAttribute(): int
{
    return $this->comments()->where('status', 'approved')->count();
}

}
