<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table = 'notices';

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'name',
        'accept_commnet',
        'approved',
        'hit',
        'count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'target_id', 'id')->where('target', $this->table);
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
    }
}
