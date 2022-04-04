<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email',
        'email_verified_at',
        'created_at',
        'updated_at',
        'is_admin',

    ];
    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function commentsOn()
    {
        return $this->morphMany('App\Models\Comment', 'commentable')->latest();
    }
    public function image()
    {
        return $this->morphOne('App\Models\Image', 'imageable');
    }
    public function scopeWithMostBlogPosts(Builder $query)
    {
        return $query->withCount('blogPosts')->orderBy('blog_posts_count', 'desc');
    }
    public function scopeWithMostBlogPostsLastMonth(Builder $query)
    {
        return $query->withCount(['blogPosts' => function (Builder $query) {
            $query->whereBetween(static::CREATED_AT, [now()->subMonths(3), now()]);
        }])->has('blogPosts', '>=', 2)
            ->orderBy('blog_posts_count', 'desc');

    }
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
