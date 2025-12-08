<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Post;
use Illuminate\Contracts\Pagination\Paginator;

final class PaginatePostsAction
{
    public function execute(int $perPage = 10): Paginator
    {
        return Post::query()
            ->published()
            ->simplePaginate(perPage: $perPage);
    }
}
