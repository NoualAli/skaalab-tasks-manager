<?php

namespace App\Traits;

use App\Models\Media;

trait HasMedia
{

    /**
     * Getters
     */

    public function getMediaArrayAttribute()
    {
        return $this->media->map(fn ($item) => $item->id);
    }

    /**
     * Relationships
     */
    public function media()
    {
        return $this->morphToMany(Media::class, 'attachable', 'has_media');
    }

    public function images()
    {
        return $this->media()->whereIn('extension', ['jpg', 'jpeg', 'png', 'svg']);
    }
}