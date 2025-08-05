<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    // Allow mass-assignment for these fields
    protected $fillable = [
        'title',
        'content',
        'image',
        'published',
    ];

    /**
     * Accessor for reading time (based on 200 WPM).
     */
    public function readingTime(): string
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = max(1, ceil($wordCount / 200));
        return $minutes . ' min read';
    }

    /**
     * Accessor for the image URL.
     * Returns uploaded image if it exists, otherwise a placeholder.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        return 'https://via.placeholder.com/600x300?text=No+Image';
    }
}
