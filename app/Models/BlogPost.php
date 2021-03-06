<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use SoftDeletes;
    use Taggable;
    protected $fillable = ['title', 'content', 'user_id'];

    use HasFactory;
    // do not need it anymore after adding polymorphic relationships
    // public function comments()
    // {
    //     return $this->hasMany('App\Models\Comment');
    // }
    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commentable')->latest();
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    // public function tags()
    // {
    //     return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    // }
    public function image()
    {
        return $this->morphOne('App\Models\Image', 'imageable');
    }
    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }
    public function scopeMostCommented(Builder $query)
    {
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }
    public function scopeLatestWithRelations(Builder $query)
    {
        return $query->latest()
            ->withCount('comments')
            ->with('user')
            ->with('tags');
    }
    public static function booted()
    {
        static::addGlobalScope(new DeletedAdminScope);
        parent::booted();
        // static::addGlobalScope(new LatestScope);

        // these two where here before creating observers
        // static::deleting(function (BlogPost $blogPost) {
        //     $blogPost->comments()->delete();
        //     Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
        // });
        // static::updating(function (BlogPost $blogPost) {
        //     Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
        // });
        // static::restoring(function (BlogPost $blogPost) {
        //     $blogPost->comments()->restore();
        // });
    }
}
