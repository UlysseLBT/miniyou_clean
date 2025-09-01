<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Media;

class MediaPolicy {
  public function delete(User $user, Media $media): bool {
    return $user->id === $media->user_id || $user->role === 'admin';
  }
}
