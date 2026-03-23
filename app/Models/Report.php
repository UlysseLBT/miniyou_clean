<?php
// app/Models/Report.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    const REASONS = [
        'spam'           => 'Spam',
        'harassment'     => 'Harcèlement',
        'inappropriate'  => 'Contenu inapproprié',
        'misinformation' => 'Désinformation',
        'other'          => 'Autre',
    ];

    protected $fillable = ['post_id', 'comment_id', 'user_id', 'reason', 'details', 'status'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    // 👇 ajouté
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}