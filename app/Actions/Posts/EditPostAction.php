<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\DTOs\NewPostDTO;
use App\Models\Post;

final class EditPostAction
{
    public function execute(Post $post, NewPostDTO $dto): Post
    {
        $post->update($dto->toArray());

        return $post;
    }
}
