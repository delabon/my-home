<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\Models\Post;
use LogicException;

final class SoftDeletePostAction
{
    /**
     * @throws LogicException
     */
    public function execute(Post $post): void
    {
        if ($post->trashed()) {
            throw new LogicException('You cannot soft delete an already deleted post.');
        }

        $post->delete();
    }
}
