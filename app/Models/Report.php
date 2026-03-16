<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['user_id', 'post_id', 'reason', 'status'];

    const REASONS = [
        'spam'            => 'Spam',
        'inappropriate'   => 'Contenu inapproprié',
        'harassment'      => 'Harcèlement',
        'misinformation'  => 'Fausses informations',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}