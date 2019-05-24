<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestBook extends Model
{
    protected $table = 'guestbooks';

    protected $fillable = [
        'parent_id',
        'user_id',
        'name',
        'password',
        'comment',
        'approved',
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

    public static function boot()
    {
        parent::boot();

        $session = new \App\Session\PHPSession;

        self::creating(function ($buestbook) use ($session) {
            if ($session->has('user')) {
                $user = $session->get('user');

                $buestbook->user_id = $user['id'];
                $buestbook->name = $user['name'];
            }

            $buestbook->ip = $_SERVER['REMOTE_ADDR'];
        });
    }
}
