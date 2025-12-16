<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\Models\Post;
use Illuminate\Contracts\Pagination\Paginator;

final class PaginatePostsAction
{
    /**
     * @return Paginator<int, Post>
     */
    public function execute(int $perPage = 10): Paginator
    {
        return Post::query()
            ->published()
            ->simplePaginate(perPage: $perPage);
    }
}
