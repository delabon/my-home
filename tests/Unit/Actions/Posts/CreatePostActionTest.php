<?php

declare(strict_types=1);

use App\Actions\Posts\CreatePostAction;
use App\DTOs\NewPostDTO;
use App\Enums\PostStatus;
use Tests\NewPost;
use Tests\NewUser;

use function Pest\Laravel\assertDatabaseCount;

it('creates a new post successfully', function () {
    $user = new NewUser()->user;
    $postData = NewPost::validPostData();
    $status = PostStatus::from($postData['status']);
    $dto = new NewPostDTO(
        title: $postData['title'],
        slug: $postData['slug'],
        body: $postData['body'],
        status: $status,
    );
    $action = new CreatePostAction();

    $post = $action->execute($user, $dto);

    expect($user->posts->count())->toBeOne()
        ->and($post->id)->toBe($user->posts()->first()->id)
        ->and($post->title)->toBe($postData['title'])
        ->and($post->body)->toBe($postData['body'])
        ->and($post->status)->toBe($status);

    assertDatabaseCount('posts', 1);
});
