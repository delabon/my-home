<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class PostController extends Controller
{
    public function __invoke(Request $request, Post $post): Response
    {
        return Inertia::render('Post', [
            'post' => new PostResource($post),
        ]);
    }
}
