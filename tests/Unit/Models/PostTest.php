<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use App\Models\User;
use Tests\NewPost;
use Tests\NewUser;

test('to array', function () {
    $post = new NewPost([
        'status' => PostStatus::Draft->value,
    ])->first();

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
    $post = new NewPost([
        'status' => PostStatus::Draft->value,
    ])->first();

    expect($post->status)->toBeInstanceOf(PostStatus::class);
});

it('belongs to a user', function () {
    $user = new NewUser()->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    expect($post->user)->toBeInstanceOf(User::class)
        ->and($post->user->id)->toBe($user->id);
});
