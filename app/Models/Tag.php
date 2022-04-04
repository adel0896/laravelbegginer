<?php

namespace App\Models;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    // use HasFactory;
    public function blogPosts()
    {
        return $this->morphedByMany(BlogPost::class, 'taggable')->withTimestamps()->as('tagged');
    }
    public function comments()
    {
        return $this->morphedByMany(Comment::class, 'taggable')->withTimestamps()->as('tagged');
    }
}
