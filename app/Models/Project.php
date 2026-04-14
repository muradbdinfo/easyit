<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Project extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia;

    protected $fillable = [
        'service_id', 'title', 'slug', 'short_description', 'description',
        'client_name', 'project_url', 'completed_at', 'is_featured',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'completed_at' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('project_images');
        $this->addMediaCollection('project_thumbnail')->singleFile();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
