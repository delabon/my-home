<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\Models\Post;

final class SoftDeletePostAction
{
    public function execute(Post $post): void
    {
        $post->delete();
    }
}
