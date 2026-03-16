<?php

namespace App\Services;

use App\Models\Hashtag;
use App\Models\Post;

class HashtagService
{
    /**
     * Extrait les hashtags du contenu + champ dédié,
     * les crée si besoin, et les associe au post.
     */
    public function syncHashtags(Post $post, string $content, ?string $rawTags = null): void
    {
        $tags = collect();

        // 1. Détection automatique dans le contenu (#motclé)
        preg_match_all('/#(\w+)/u', $content, $matches);
        $tags = $tags->merge($matches[1]);

        // 2. Champ séparé (ex: "laravel, php, bts")
        if ($rawTags) {
            $manual = collect(explode(',', $rawTags))
                ->map(fn($t) => trim(ltrim($t, '#')))
                ->filter();
            $tags = $tags->merge($manual);
        }

        // Normalise en minuscules, dédoublonne
        $tags = $tags->map(fn($t) => strtolower($t))->unique()->filter();

        // Crée ou récupère chaque hashtag
        $ids = $tags->map(function ($name) {
            return Hashtag::firstOrCreate(['name' => $name])->id;
        });

        // Synchronise la relation pivot
        $post->hashtags()->sync($ids);
    }
}