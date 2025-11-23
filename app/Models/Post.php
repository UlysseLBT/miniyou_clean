<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Community;
use App\Models\Comment;
use App\Models\PostLike;

class Post extends Model
{
    use HasFactory;

    /**
     * @method static \Illuminate\Database\Eloquent\Factories\Factory factory(...$parameters)
     */

    // Colonnes pouvant être remplies en masse
    protected $fillable = [
        'user_id',
        'community_id',
        'titre',
        'texte',
        'url',      // <- si ta colonne s'appelle bien `url`
    ];

    // Relation : un post appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function community()
    {
        return $this->belongsTo(Community::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

}
