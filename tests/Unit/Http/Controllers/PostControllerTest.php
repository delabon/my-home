<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\NewPost;

it('returns an Inertia response with post data successfully', function () {
    $post = new NewPost([
        'status' => PostStatus::Published->value,
    ])->first();
    $request = Request::create(route('posts.view', $post));
    $controller = new PostController();

    $inertiaResponse = $controller($request, $post);

    $response = $inertiaResponse->toResponse($request);

    expect($inertiaResponse)->toBeInstanceOf(InertiaResponse::class)
        ->and($response)->toBeInstanceOf(Response::class)
        ->and($response->status())->toBe(Response::HTTP_OK);
});

it('throws a NotFoundHttpException when the status of the post is not published', function () {
    $post = new NewPost([
        'status' => PostStatus::Draft->value,
    ])->first();
    $request = Request::create(route('posts.view', $post));
    $controller = new PostController();

    expect(static fn () => $controller($request, $post))
        ->toThrow(NotFoundHttpException::class, 'Post not found.');
});
