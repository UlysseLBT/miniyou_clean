<?php

namespace App\Policies;

use App\Models\Community;
use App\Models\User;

class CommunityPolicy
{
    public function view(?User $user, Community $community): bool
    {
        // Public => tout le monde peut voir
        if (!$community->is_private) return true;

        // Privé => uniquement membre/owner
        if (!$user) return false;

        return $community->owner_id === $user->id
            || $community->users()->where('users.id', $user->id)->exists();
    }
}
