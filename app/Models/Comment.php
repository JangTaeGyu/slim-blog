<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'parent_id',
        'name',
        'email',
        'comment',
        'approved',
        'post_id'
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function child()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'target_id', 'id')->where('target', 'posts');
    }

    public function notice()
    {
        return $this->belongsTo(Notice::class, 'target_id', 'id')->where('target', 'notices');
    }

    public static function boot()
    {
        parent::boot();

        $session = new \App\Session\PHPSession;

        self::creating(function ($comment) use ($session) {
            if ($session->has('user')) {
                $user = $session->get('user');

                $comment->user_id = $user['id'];
                $comment->name = $user['name'];
            }

            $comment->ip = $_SERVER['REMOTE_ADDR'];
        });
    }
}
