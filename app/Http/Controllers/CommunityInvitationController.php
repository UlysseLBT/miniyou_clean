<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommunityInvitationController extends Controller
{
    public function store(Request $request, Community $community)
    {
        abort_unless($community->owner_id === $request->user()->id, 403);

        $data = $request->validate([
            'invitee_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $inviteeId = $data['invitee_id'] ?? null;

        // option: inviter quelqu’un en particulier, sinon lien partageable
        if ($inviteeId) {
            $alreadyMember = $community->members()->where('users.id', $inviteeId)->exists()
                || $community->owner_id === $inviteeId;

            abort_if($alreadyMember, 422, "Cet utilisateur est déjà membre.");
        }

        $invitation = CommunityInvitation::create([
            'community_id' => $community->id,
            'inviter_id'   => $request->user()->id,
            'invitee_id'   => $inviteeId,
            'token'        => Str::random(64),
            'status'       => 'pending',
            'expires_at'   => now()->addDays(7),
        ]);

        // tu peux afficher ce lien dans la vue
        $link = route('invitations.accept', $invitation->token);

        return back()->with('status', "Invitation créée. Lien: $link");
    }

    public function accept(Request $request, string $token)
    {
        $inv = CommunityInvitation::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        if ($inv->expires_at && $inv->expires_at->isPast()) {
            $inv->update(['status' => 'expired']);
            abort(410, "Invitation expirée.");
        }

        $user = $request->user();

        // Si invitation ciblée, seul le bon user peut accepter
        if ($inv->invitee_id && $inv->invitee_id !== $user->id) {
            abort(403);
        }

        $community = Community::findOrFail($inv->community_id);

        $community->members()->syncWithoutDetaching([
            $user->id => ['role' => 'member']
        ]);

        $inv->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        return redirect()
            ->route('communities.show', $community)
            ->with('status', 'Invitation acceptée !');
    }

    public function revoke(Request $request, Community $community, CommunityInvitation $invitation)
    {
        abort_unless($community->owner_id === $request->user()->id, 403);
        abort_unless($invitation->community_id === $community->id, 404);

        $invitation->update(['status' => 'revoked']);

        return back()->with('status', 'Invitation annulée.');
    }
}

