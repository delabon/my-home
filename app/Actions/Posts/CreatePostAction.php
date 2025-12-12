<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\DTOs\NewPostDTO;
use App\Models\Post;
use App\Models\User;

final class CreatePostAction
{
    public function execute(User $user, NewPostDTO $dto): Post
    {
        return $user->posts()->create($dto->toArray());
    }
}
