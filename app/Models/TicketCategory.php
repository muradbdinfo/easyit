<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class TicketCategory extends Model
{
    use HasSlug;
    protected $fillable = ['name','slug','color','sort_order','is_active'];
    public function getSlugOptions(): SlugOptions { return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug'); }
    public function tickets() { return $this->hasMany(Ticket::class); }
    public function scopeActive($q) { return $q->where('is_active', true)->orderBy('sort_order'); }
}
