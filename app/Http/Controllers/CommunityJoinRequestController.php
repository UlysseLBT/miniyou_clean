<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityJoinRequest;
use Illuminate\Http\Request;

class CommunityJoinRequestController extends Controller
{
    /**
     * L'utilisateur envoie une demande pour rejoindre une communauté privée
     */
    public function store(Request $request, Community $community)
    {
        abort_unless($community->visibility === 'private', 403);

        $user = $request->user();

        // Déjà membre/owner ?
        $isMember = $community->owner_id === $user->id
            || $community->members()->where('users.id', $user->id)->exists();

        abort_if($isMember, 403);

        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        CommunityJoinRequest::updateOrCreate(
            ['community_id' => $community->id, 'user_id' => $user->id],
            ['status' => 'pending', 'message' => $data['message'] ?? null]
        );

        return back()->with('status', 'Demande envoyée au propriétaire.');
    }

    /**
     * L'utilisateur annule sa demande
     */
    public function cancel(Request $request, Community $community)
    {
        $user = $request->user();

        CommunityJoinRequest::where('community_id', $community->id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->update(['status' => 'canceled']);

        return back()->with('status', 'Demande annulée.');
    }

    /**
     * Le propriétaire accepte la demande
     */
    public function approve(Request $request, Community $community, CommunityJoinRequest $joinRequest)
    {
        abort_unless($community->owner_id === $request->user()->id, 403);
        abort_unless($joinRequest->community_id === $community->id, 404);

        $community->members()->syncWithoutDetaching([
            $joinRequest->user_id => ['role' => 'member'],
        ]);

        $joinRequest->update([
            'status'     => 'accepted',
            'handled_by' => $request->user()->id,
            'handled_at' => now(),
        ]);

        return back()->with('status', 'Demande acceptée.');
    }

    /**
     * Le propriétaire refuse la demande
     */
    public function deny(Request $request, Community $community, CommunityJoinRequest $joinRequest)
    {
        abort_unless($community->owner_id === $request->user()->id, 403);
        abort_unless($joinRequest->community_id === $community->id, 404);

        $joinRequest->update([
            'status'     => 'denied',
            'handled_by' => $request->user()->id,
            'handled_at' => now(),
        ]);

        return back()->with('status', 'Demande refusée.');
    }
}
