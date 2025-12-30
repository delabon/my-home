<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PostStatus;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PostController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Request $request, Post $post): Response
    {
        if ($post->status !== PostStatus::Published) {
            throw new NotFoundHttpException('Post not found.');
        }

        return Inertia::render('Post', [
            'post' => new PostResource($post),
        ]);
    }
}
