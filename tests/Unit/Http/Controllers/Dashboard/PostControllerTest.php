<?php

declare(strict_types=1);

use App\Actions\Posts\CreatePostAction;
use App\Actions\Posts\EditPostAction;
use App\Actions\Posts\PaginatePostsAction;
use App\Actions\Posts\SoftDeletePostAction;
use App\Enums\PostStatus;
use App\Http\Controllers\Dashboard\PostController;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\EditPostRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Response as InertiaResponse;
use Tests\NewPost;
use Tests\NewUser;

use function Pest\Laravel\assertDatabaseCount;

test('index method renders the dashboard posts list component', function () {
    $request = Request::create(route('posts.index'));
    $action = new PaginatePostsAction();
    $controller = new PostController();

    $inertiaResponse = $controller->index($action);
    $response = $inertiaResponse->toResponse($request);

    expect($inertiaResponse)->toBeInstanceOf(InertiaResponse::class)
        ->and($response)->toBeInstanceOf(Response::class)
        ->and($response->status())->toBe(Response::HTTP_OK);
});

test('create method renders the dashboard create post component', function () {
    $request = Request::create(route('posts.create'));
    $controller = new PostController();

    $inertiaResponse = $controller->create();
    $response = $inertiaResponse->toResponse($request);

    expect($inertiaResponse)->toBeInstanceOf(InertiaResponse::class)
        ->and($response)->toBeInstanceOf(Response::class)
        ->and($response->status())->toBe(Response::HTTP_OK);
});

it('stores a new post successfully', function () {
    $uri = route('posts.store');
    $user = new NewUser()->user;
    $request = CreatePostRequest::create(
        uri: $uri,
        method: 'POST',
        parameters: NewPost::validPostData()
    );
    $validator = validator($request->all(), $request->rules());
    $request->setValidator($validator);
    $request->setUserResolver(static fn () => $user);
    $action = new CreatePostAction();
    $controller = new PostController();

    assertDatabaseCount('posts', 0);

    $response = $controller->store($request, $action);

    expect($response)->toBeInstanceOf(RedirectResponse::class)
        ->and($response->getTargetUrl())->toBe($uri)
        ->and($response->getSession()->has('success'))->toBeTrue()
        ->and($response->getSession()->get('success'))->toBe('Post has been created.');

    assertDatabaseCount('posts', 1);
});

test('method edit renders the dashboard edit post component', function () {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();
    $request = Request::create(route('posts.edit', $post));
    $controller = new PostController();

    $inertiaResponse = $controller->edit($post);
    $response = $inertiaResponse->toResponse($request);

    expect($inertiaResponse)->toBeInstanceOf(InertiaResponse::class)
        ->and($response)->toBeInstanceOf(Response::class)
        ->and($response->status())->toBe(Response::HTTP_OK);
});

test('method edit throws exception when trying to edit a post with non-author user', function () {
    new NewUser()->login($this)->user;
    $post = new NewPost()->first();
    $controller = new PostController();

    expect(static fn () => $controller->edit($post))
        ->toThrow(AuthorizationException::class, 'This action is unauthorized.');
});

it('updates a post successfully', function () {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'user_id' => $user->id,
        'status' => PostStatus::Published->value,
    ])->first();
    $updatedPostData = [
        'title' => 'Updated title',
        'slug' => 'updated-slug',
        'status' => PostStatus::Draft->value,
        'body' => 'Updated body that is valid.',
    ];
    $request = EditPostRequest::create(
        uri: route('posts.update', $post),
        method: 'PATCH',
        parameters: $updatedPostData
    );
    $validator = validator($request->all(), $request->rules());
    $request->setValidator($validator);
    $action = new EditPostAction();
    $controller = new PostController();

    $response = $controller->update($post, $request, $action);

    $post->refresh();

    expect($response)->toBeInstanceOf(RedirectResponse::class)
        ->and($response->getTargetUrl())->toBe(route('posts.index'))
        ->and($response->getSession()->has('success'))->toBeTrue()
        ->and($response->getSession()->get('success'))->toBe('Post has been updated.')
        ->and($post->title)->toBe($updatedPostData['title'])
        ->and($post->slug)->toBe($updatedPostData['slug'])
        ->and($post->status->value)->toBe($updatedPostData['status'])
        ->and($post->body)->toBe($updatedPostData['body']);
});

it('deletes a post successfully', function () {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'user_id' => $user->id,
        'status' => PostStatus::Published->value,
    ])->first();
    $action = new SoftDeletePostAction();
    $controller = new PostController();

    $response = $controller->destroy($post, $action);

    $post->refresh();

    expect($response)->toBeInstanceOf(RedirectResponse::class)
        ->and($response->getTargetUrl())->toBe(route('posts.index'))
        ->and($response->getSession()->has('success'))->toBeTrue()
        ->and($response->getSession()->get('success'))->toBe('Post has been deleted.')
        ->and($post->trashed())->toBeTrue();

    assertDatabaseCount('posts', 1);
});

test('method destroy throws exception when trying to delete a post with non-author user', function () {
    new NewUser()->login($this)->user;
    $post = new NewPost()->first();
    $action = new SoftDeletePostAction();
    $controller = new PostController();

    expect(static fn () => $controller->destroy($post, $action))
        ->toThrow(AuthorizationException::class, 'This action is unauthorized.');
});
