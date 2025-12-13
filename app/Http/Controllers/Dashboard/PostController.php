<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Actions\Posts\CreatePostAction;
use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\EditPostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class PostController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('dashboard/posts/Index');
    }

    public function create(): Response
    {
        return Inertia::render('dashboard/posts/Create', [
            'statuses' => PostStatus::options(),
        ]);
    }

    public function store(CreatePostRequest $request, CreatePostAction $action): RedirectResponse
    {
        $action->execute($request->user(), $request->toDto());

        return to_route('posts.index')
            ->with('success', 'Post has been created.');
    }

    public function edit(Post $post): Response
    {
        return Inertia::render('dashboard/posts/Edit', [
            'post' => $post,
            'statuses' => PostStatus::options(),
        ]);
    }

    public function update(Post $post, EditPostRequest $request): RedirectResponse
    {
        $post->update($request->validated());

        return to_route('posts.index')
            ->with('success',  'Post has been updated.');
    }
}
