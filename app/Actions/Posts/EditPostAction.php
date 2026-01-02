<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\DTOs\NewPostDTO;
use App\Enums\PostStatus;
use App\Models\Post;

final class EditPostAction
{
    public function execute(Post $post, NewPostDTO $dto): Post
    {
        $isPublished = $dto->status === PostStatus::Published;
        $postData = $dto->toArray();
        $postData['published_at'] = $isPublished
            ? $post->status === PostStatus::Published
                ? $post->published_at
                : now()
            : null;

        $post->update($postData);

        return $post;
    }
}
