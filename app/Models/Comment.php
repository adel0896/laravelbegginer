<?php

namespace App\Models;

use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Taggable;

    protected $fillable = ['user_id', 'content'];
    protected $hidden = ['deleted_at', 'commentable_type', 'commentable_id', 'user_id'];
    //blog_post_id--same name of the collumn, so we write blogPost because that would be transformed in blog_post_id
//    did not need it anymore after adding polymorphic relationships
    // public function blogPost()
    // {
    //     return $this->belongsTo('App\Models\BlogPost');
    // }

    public function commentable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    // moved it to traits
    // public function tags()
    // {
    //     return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    // }
    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public static function boot()
    {
        parent::boot();
// this method was here before adding the observers
        // static::creating(function (Comment $comment) {
        //     if ($comment->commentable_type === BlogPost::class) {
        //         Cache::tags(['blog-post'])->forget("blog-post-{$comment->commentable_id}");
        //         Cache::tags(['blog-post'])->forget("mostCommented");
        //     }

        // });
        // static::addGlobalScope(new LatestScope);

    }
}
