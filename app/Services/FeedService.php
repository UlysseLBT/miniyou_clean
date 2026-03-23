<?php
namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FeedService
{
    /**
     * Fil "Pour toi" — score = récence + likes×0.3 + comments×0.5 + affinité×2.0
     * S'appuie sur post_likes et comments existants, sans table supplémentaire.
     */
    public function getForYouFeed(User $user, int $perPage = 15): LengthAwarePaginator
    {
        $followedIds = $user->following()->pluck('users.id');

        // Affinité : nombre de fois que $user a liké OU commenté
        // des posts d'un auteur donné (via post_likes + comments)
        $affinitySubquery = DB::table('post_likes')
            ->select('posts.user_id as author_id', DB::raw('COUNT(*) as affinity_score'))
            ->join('posts', 'posts.id', '=', 'post_likes.post_id')
            ->where('post_likes.user_id', $user->id)
            ->groupBy('posts.user_id')
            ->unionAll(
                DB::table('comments')
                    ->select('posts.user_id as author_id', DB::raw('COUNT(*) as affinity_score'))
                    ->join('posts', 'posts.id', '=', 'comments.post_id')
                    ->where('comments.user_id', $user->id)
                    ->groupBy('posts.user_id')
            );

        // On wrappe l'union dans une sous-requête pour sommer les deux sources
        $affinityGrouped = DB::table(DB::raw("({$affinitySubquery->toSql()}) as aff_union"))
            ->mergeBindings($affinitySubquery)
            ->select('author_id', DB::raw('SUM(affinity_score) as affinity_score'))
            ->groupBy('author_id');

        // Toutes les colonnes de posts préfixées pour satisfaire MySQL strict mode
        $groupByColumns = array_map(
            fn($col) => "posts.{$col}",
            Schema::getColumnListing('posts')
        );
        $groupByColumns[] = 'affinity.affinity_score';

        return Post::query()
            ->with(['user', 'hashtags'])
            ->where('posts.user_id', '!=', $user->id)
            ->where(function ($q) use ($followedIds) {
                // Posts des abonnements (7 derniers jours)
                $q->where(function ($q1) use ($followedIds) {
                    $q1->whereIn('posts.user_id', $followedIds)
                       ->where('posts.created_at', '>=', now()->subDays(7));
                })
                // OU posts tendances (48h) — toutes sources
                ->orWhere(function ($q2) {
                    $q2->where('posts.created_at', '>=', now()->subHours(48));
                });
            })
            ->leftJoinSub($affinityGrouped, 'affinity', function ($join) {
                $join->on('posts.user_id', '=', 'affinity.author_id');
            })
            ->leftJoin('post_likes', 'post_likes.post_id', '=', 'posts.id')
            ->leftJoin('comments', 'comments.post_id', '=', 'posts.id')
            ->select('posts.*')
            ->selectRaw('COUNT(DISTINCT post_likes.id) as likes_count')
            ->selectRaw('COUNT(DISTINCT comments.id) as comments_count')
            ->selectRaw('COALESCE(affinity.affinity_score, 0) as affinity_score')
            ->selectRaw('
                (
                    EXP(-0.1 * TIMESTAMPDIFF(HOUR, posts.created_at, NOW()) / 24)
                    + COUNT(DISTINCT post_likes.id) * 0.3
                    + COUNT(DISTINCT comments.id) * 0.5
                    + COALESCE(affinity.affinity_score, 0) * 2.0
                ) as feed_score
            ')
            ->groupBy($groupByColumns)
            ->orderByDesc('feed_score')
            ->paginate($perPage);
    }
}