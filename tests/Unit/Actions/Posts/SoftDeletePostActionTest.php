<?php

declare(strict_types=1);

use App\Actions\Posts\SoftDeletePostAction;
use Tests\NewPost;

use function Pest\Laravel\assertDatabaseCount;

it('soft deletes a post successfully', function () {
    $post = new NewPost()->first();

    $action = new SoftDeletePostAction();

    $action->execute($post);

    $post->refresh();

    expect($post->trashed())->toBeTrue()
        ->and($post->deleted_at)->not()->toBeNull();

    assertDatabaseCount('posts', 1);
});

it('throws a logic exception when trying to soft delete an already soft deleted post.', function () {
    $post = new NewPost()->first();
    $post->delete();

    $action = new SoftDeletePostAction();

    expect(static fn () => $action->execute($post))
        ->toThrow(LogicException::class, 'You cannot soft delete an already deleted post.');
});
