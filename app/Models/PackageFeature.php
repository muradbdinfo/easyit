<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageFeature extends Model
{
    protected $fillable = ['package_id', 'feature', 'is_included', 'sort_order'];

    protected $casts = ['is_included' => 'boolean'];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
