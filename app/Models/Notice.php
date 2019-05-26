<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table = 'notices';

    protected $fillable = [
        'title',
        'content',
        'accept_commnet',
        'approved',
        'hit',
        'count'
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'target_id', 'id')->where('target', $this->table);
    }
}
