<?php

declare(strict_types=1);

use App\Models\Post;
use Tests\NewPost;
use Tests\NewUser;

test('to array', function () {
    $user = new NewUser()->user;

    expect($user->toArray())->toHaveKeys([
        'id',
        'name',
        'email',
        'email_verified_at',
        'created_at',
        'updated_at',
    ]);
});

it('has many posts', function () {
    $user = new NewUser()->user;
    $posts = new NewPost([
        'user_id' => $user->id,
    ], 3)->posts;

    $postIds = $posts->pluck('id')->all();

    expect($user->posts)->toHaveCount(3);

    foreach ($user->posts as $post) {
        expect($post)->toBeInstanceOf(Post::class)
            ->and($post->id)->toBeIn($postIds);
    }
});
