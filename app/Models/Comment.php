<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'parent_id',
        'user_id',
        'name',
        'password',
        'comment',
        'approved',
        'target',
        'target_id',
        'ip'
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function child()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'target_id', 'id');
    }

    public function notice()
    {
        return $this->belongsTo(Notice::class, 'target_id', 'id');
    }

    public function scopeTargetTitle(Builder $query)
    {
        return $query->select('*')->selectRaw("
            case
                when comments.target = 'posts' then (select title from posts where id = comments.target_id)
                when comments.target = 'notices' then (select title from notices where id = comments.target_id)
            end as target_title
        ");
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

            $comment->ip = $_SERVER['REMOTE_ADDR'];
        });

        static::created(function ($comment) {
            if ($comment->target === 'posts') {
                $comment->post()->increment('count', 1);
            }

            if ($comment->target === 'notices') {
                $comment->notice()->increment('count', 1);
            }
        });

        static::deleted(function ($comment) {
            if ($comment->target === 'posts') {
                $comment->post()->decrement('count', 1);
            }

            if ($comment->target === 'notices') {
                $comment->notice()->decrement('count', 1);
            }
        });
    }
}
