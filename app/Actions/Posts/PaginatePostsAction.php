<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\Enums\OrderDirection;
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
        ?PostStatus $status = null,
        ?string $orderColumn = null,
        OrderDirection $orderDirection = OrderDirection::Desc,
    ): Paginator {
        return Post::query()
            ->when(
                $status,
                static fn ($q) => $q->where('status', $status)
            )
            ->when(
                ! empty($orderColumn),
                static fn ($q) => $q->orderBy($orderColumn, $orderDirection->value)
            )
            ->simplePaginate(perPage: $perPage);
    }
}
