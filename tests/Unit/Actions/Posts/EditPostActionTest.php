<?php

declare(strict_types=1);

use App\Actions\Posts\EditPostAction;
use App\DTOs\NewPostDTO;
use App\Enums\PostStatus;
use Tests\NewPost;
use Tests\NewUser;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('updates a post successfully', function () {
    $user = new NewUser()->user;
    $post = new NewPost([
        'user_id' => $user->id,
        'status' => PostStatus::Draft->value,
    ])->first();
    $updatedPostData = [
        'title' => 'This title has been updated',
        'slug' => 'updated-slug',
        'body' => 'This body has been updated',
        'status' => PostStatus::Published,
    ];
    $dto = new NewPostDTO(
        title: $updatedPostData['title'],
        slug: $updatedPostData['slug'],
        body: $updatedPostData['body'],
        status: $updatedPostData['status']
    );
    $action = new EditPostAction();

    $updatedPost = $action->execute($post, $dto);

    expect($user->posts->count())->toBeOne()
        ->and($post->id)->toBe($updatedPost->id)
        ->and($updatedPost->title)->toBe($updatedPostData['title'])
        ->and($updatedPost->body)->toBe($updatedPostData['body'])
        ->and($updatedPost->status)->toBe($updatedPostData['status']);

    assertDatabaseCount('posts', 1);
    assertDatabaseHas('posts', [
        'title' => $updatedPostData['title'],
        'slug' => $updatedPostData['slug'],
        'body' => $updatedPostData['body'],
        'status' => $updatedPostData['status']->value,
    ]);
});

it('does not update the published_at field when the post already published', function () {
    $user = new NewUser()->user;
    $publishedAt = now()->subYear();
    $post = new NewPost([
        'user_id' => $user->id,
        'status' => PostStatus::Published->value,
        'published_at' => $publishedAt,
    ])->first();
    $updatedPostData = [
        'title' => 'This title has been updated',
        'slug' => 'updated-slug',
        'body' => 'This body has been updated',
        'status' => PostStatus::Published,
    ];
    $dto = new NewPostDTO(
        title: $updatedPostData['title'],
        slug: $updatedPostData['slug'],
        body: $updatedPostData['body'],
        status: $updatedPostData['status']
    );
    $action = new EditPostAction();

    $updatedPost = $action->execute($post, $dto);

    expect($updatedPost->published_at->timestamp)->toBe($publishedAt->timestamp);
});
