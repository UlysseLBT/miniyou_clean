<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Community;

class Post extends Model
{
    use HasFactory;

    /**
     * @method static \Illuminate\Database\Eloquent\Factories\Factory factory(...$parameters)
     */

    // Colonnes pouvant être remplies en masse
    protected $fillable = [
        'user_id',
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

}
