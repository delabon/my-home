<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

final class PostPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Post $post): bool
    {
        return $this->manage($user, $post);
    }

    public function edit(User $user, Post $post): bool
    {
        return $this->manage($user, $post);
    }

    public function softDelete(User $user, Post $post): bool
    {
        return $this->manage($user, $post);
    }

    private function manage(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }
}
