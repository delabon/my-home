<?php

declare(strict_types=1);

use App\Actions\Posts\CreatePostAction;
use App\DTOs\NewPostDTO;
use Tests\NewPost;
use Tests\NewUser;

it('creates a new post successfully', function () {
    $user = new NewUser()->user;
    $postData = NewPost::validPostData();
    $dto = new NewPostDTO(
        title: $postData['title'],
        body: $postData['body'],
        status: $postData['status'],
    );
    $action = new CreatePostAction();

    $post = $action->execute($user, $dto);

    expect($user->posts->count())->toBeOne()
        ->and($post->id)->toBe($user->posts()->first()->id)
        ->and($post->title)->toBe($postData['title'])
        ->and($post->body)->toBe($postData['body'])
        ->and($post->status->value)->toBe($postData['status']);
});
