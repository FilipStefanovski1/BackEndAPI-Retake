<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    // Allow mass-assignment for these fields
    protected $fillable = [
        'title',
        'content',
        'image',
        'published',
    ];

    // Reading time helper (based on 200 WPM)
    public function readingTime(): string
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = max(1, ceil($wordCount / 200));
        return $minutes . ' min read';
    }
}
