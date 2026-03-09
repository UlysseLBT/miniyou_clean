<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;

class FollowController extends Controller
{
    public function toggle(User $user): RedirectResponse
    {
        $auth = auth()->user();

        if ($auth->id === $user->id) {
            return back();
        }

        if ($auth->isFollowing($user)) {
            $auth->following()->detach($user->id);
        } else {
            $auth->following()->attach($user->id);

            // Notif seulement au moment du follow (pas du unfollow)
            Notification::create([
                'user_id' => $user->id,
                'type'    => 'new_follower',
                'data'    => [
                    'follower_id'   => $auth->id,
                    'follower_name' => $auth->name,
                    'url'           => route('users.show', $auth->id),
                ],
            ]);
        }

        return back();
    }
}