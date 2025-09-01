<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Post;

class PostPolicy {
  public function delete(User $user, Post $post): bool {
    return $user->id === $post->user_id || $user->role === 'admin';
  }
}
