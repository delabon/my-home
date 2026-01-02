<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Contracts\Pagination\Paginator;

final class PaginatePostsAction
{
    /**
     * @return Paginator<int, Post>
     */
    public function execute(
        int $perPage = 10,
        ?PostStatus $status = null
    ): Paginator {
        return Post::query()
            ->when(
                $status,
                static fn ($q) => $q->where('status', $status)
            )
            ->simplePaginate(perPage: $perPage);
    }
}
