<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = [
        'category_id',
        'title',
        'image',
        'body',
        'user_id',
        'name',
        'accept_commnet',
        'approved',
        'hit',
        'count'
    ];

    public function scopeCategoryName(Builder $query)
    {
        return $query->select('*')->selectRaw("
            (select name from categories where id = posts.category_id) as category_name
        ");
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        $session = new \App\Session\PHPSession;

        static::creating(function ($comment) use ($session) {
            if ($session->has('user')) {
                $user = $session->get('user');

                $comment->user_id = $user['id'];
                $comment->name = $user['name'];
            }
        });

        static::created(function ($comment) {
            $comment->category()->increment('count', 1);
        });

        static::deleted(function ($comment) {
            $comment->category()->decrement('count', 1);
        });
    }
}
