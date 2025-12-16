<?php

declare(strict_types=1);

use App\Actions\Posts\EditPostAction;
use App\DTOs\NewPostDTO;
use App\Enums\PostStatus;
use Tests\NewPost;
use Tests\NewUser;

use function Pest\Laravel\assertDatabaseCount;

it('updates a post successfully', function () {
    $user = new NewUser()->user;
    $post = new NewPost([
        'user_id' => $user->id,
        'status' => PostStatus::Draft->value,
    ])->first();
    $updatedPostData = [
        'title' => 'This title has been updated',
        'body' => 'This title has been updated',
        'status' => PostStatus::Published,
    ];
    $dto = new NewPostDTO(
        title: $updatedPostData['title'],
        body: $updatedPostData['body'],
        status: $updatedPostData['status'],
    );
    $action = new EditPostAction();

    $action->execute($post, $dto);

    $updatedPost = $user->posts()->first();

    expect($user->posts->count())->toBeOne()
        ->and($post->id)->toBe($updatedPost->id)
        ->and($updatedPost->title)->toBe($updatedPostData['title'])
        ->and($updatedPost->body)->toBe($updatedPostData['body'])
        ->and($updatedPost->status)->toBe($updatedPostData['status']);

    assertDatabaseCount('posts', 1);
});
