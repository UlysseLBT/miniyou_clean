<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mime extends Model
{
    // Car la table s'appelle 'mime' (singulier)
    protected $table = 'mime';

    protected $fillable = [
        'type',
        'subtype',
        'full',
        'extensions',
        'is_allowed',
        'max_size_mb',
    ];

    protected $casts = [
        'extensions' => 'array',
        'is_allowed' => 'boolean',
        'max_size_mb' => 'integer',
    ];

    // Si plus tard tu ajoutes un champ mime_id dans 'media',
    // tu pourras décommenter cette relation :
    /*
    public function media()
    {
        return $this->hasMany(Media::class, 'mime_id');
    }
    */
}
