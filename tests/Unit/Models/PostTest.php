<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Carbon;
use Tests\NewPost;
use Tests\NewUser;

test('to array', function () {
    $post = new NewPost([
        'status' => PostStatus::Published->value,
        'published_at' => now(),
    ])->first();

    expect($post->toArray())->toHaveKeys([
        'id',
        'user_id',
        'title',
        'slug',
        'body',
        'status',
        'published_at',
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

it('casts published_at into Carbon instance', function () {
    $now = now();
    $post = new NewPost([
        'status' => PostStatus::Published->value,
        'published_at' => $now,
    ])->first();

    expect($post->published_at)->toBeInstanceOf(Carbon::class)
        ->and($post->published_at->timestamp)->toBe($now->timestamp);
});

it('belongs to a user', function () {
    $user = new NewUser()->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    expect($post->user)->toBeInstanceOf(User::class)
        ->and($post->user->id)->toBe($user->id);
});

test('status should be unique', function () {
    expect(static function () {
        $post1 = new NewPost([
            'slug' => 'my-post',
        ])->first();
        $post2 = new NewPost([
            'slug' => 'my-post',
        ])->first();
    })->toThrow(UniqueConstraintViolationException::class);
});

it('formats the published_at date correctly', function () {
    $now = now();
    $post = new NewPost([
        'status' => PostStatus::Published->value,
        'published_at' => $now,
    ])->first();

    expect($post->formatted_published_at)->toBeString()
        ->and($post->formatted_published_at)->toBe($now->format(Post::DATE_FORMAT));
});

test('formatted_published_at returns null when the post is not published', function () {
    $post = new NewPost([
        'status' => PostStatus::Draft->value,
    ])->first();

    expect($post->formatted_published_at)->toBeNull();
});
