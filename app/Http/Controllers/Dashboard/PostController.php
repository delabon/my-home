<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Actions\Posts\CreatePostAction;
use App\Actions\Posts\EditPostAction;
use App\Actions\Posts\PaginatePostsAction;
use App\Actions\Posts\SoftDeletePostAction;
use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\EditPostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class PostController extends Controller
{
    use AuthorizesRequests;

    public function index(PaginatePostsAction $action): Response
    {
        return Inertia::render('dashboard/posts/Index', [
            'posts' => PostResource::collection($action->execute()),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('dashboard/posts/Create', [
            'statuses' => PostStatus::options(),
        ]);
    }

    public function store(CreatePostRequest $request, CreatePostAction $action): RedirectResponse
    {
        /** @phpstan-ignore argument.type */
        $action->execute($request->user(), $request->toDto());

        return to_route('posts.index')
            ->with('success', 'Post has been created.');
    }

    public function edit(Post $post): Response
    {
        $this->authorize('edit', $post);

        return Inertia::render('dashboard/posts/Edit', [
            'post' => $post,
            'statuses' => PostStatus::options(),
        ]);
    }

    public function update(Post $post, EditPostRequest $request, EditPostAction $action): RedirectResponse
    {
        $action->execute($post, $request->toDto());

        return to_route('posts.index')
            ->with('success', 'Post has been updated.');
    }

    public function destroy(Post $post, SoftDeletePostAction $action): RedirectResponse
    {
        $this->authorize('soft-delete', $post);

        $action->execute($post);

        return to_route('posts.index')
            ->with('success', 'Post has been deleted.');
    }
}
