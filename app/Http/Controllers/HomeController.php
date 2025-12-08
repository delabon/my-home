<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\PaginatePostsAction;
use Illuminate\Http\Request;
use Inertia\Inertia;

final class HomeController extends Controller
{
    private const int PER_PAGE = 10;

    public function __invoke(Request $request, PaginatePostsAction $action)
    {
        $posts = $action->execute(perPage: self::PER_PAGE);

        return Inertia::render('Home', [
            'posts' => $posts
        ]);
    }
}
