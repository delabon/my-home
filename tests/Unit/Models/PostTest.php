<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use App\Models\User;
use Database\Factories\PostFactory;
use Database\Factories\UserFactory;

test('to array', function () {
    $post = PostFactory::new()->create();

    expect($post->toArray())->toHaveKeys([
        'id',
        'user_id',
        'title',
        'body',
        'status',
        'created_at',
        'updated_at',
    ]);
});

it('casts status into PostStatus enum', function () {
    $post = PostFactory::new()->create();

    expect($post->status)->toBeInstanceOf(PostStatus::class);
});

it('belongs to a user', function () {
    $user = UserFactory::new()->create();
    $post = PostFactory::new()->create([
        'user_id' => $user->id,
    ]);

    expect($post->user)->toBeInstanceOf(User::class)
        ->and($post->user->id)->toBe($user->id);
});
