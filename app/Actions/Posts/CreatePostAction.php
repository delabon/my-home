<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\DTOs\NewPostDTO;
use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;

final class CreatePostAction
{
    public function execute(User $user, NewPostDTO $dto): Post
    {
        $isPublished = $dto->status === PostStatus::Published;
        $postData = $dto->toArray();
        $postData['published_at'] = $isPublished
            ? now()
            : null;

        /** @var Post $post */
        $post = $user->posts()->create($postData);

        return $post;
    }
}
