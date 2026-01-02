<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Posts\PaginatePostsAction;
use App\Enums\PostStatus;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class HomeController extends Controller
{
    private const int PER_PAGE = 10;

    public function __invoke(Request $request, PaginatePostsAction $action): Response
    {
        $posts = $action->execute(perPage: self::PER_PAGE, status: PostStatus::Published);

        return Inertia::render('Home', [
            'title' => config('app.homepage.title', ''),
            'description' => config('app.homepage.description', ''),
            'posts' => PostResource::collection($posts),
        ]);
    }
}
