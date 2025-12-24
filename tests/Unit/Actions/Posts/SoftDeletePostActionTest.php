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
