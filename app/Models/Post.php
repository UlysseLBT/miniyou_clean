<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre',
        'texte',
        'media_disk',
        'media_url',
        'media_mime',
        'media_size',
        'media_original_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accesseur pratique pour l’URL publique (quand disk=public)
    public function getMediaPublicUrlAttribute(): ?string
    {
        if (!$this->media_url) return null;
        return $this->media_disk === 'public'
            ? asset('storage/'.$this->media_url)
            : $this->media_url; // à adapter si tu utilises d’autres disques (s3 etc.)
    }
}
