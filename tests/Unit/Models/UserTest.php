<?php

declare(strict_types=1);

use App\Models\Post;
use Database\Factories\PostFactory;
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
    $posts = PostFactory::times(3)->create([
        'user_id' => $user->id,
    ]);
    $postIds = $posts->pluck('id')->all();

    expect($user->posts)->toHaveCount(3);

    foreach ($user->posts as $post) {
        expect($post)->toBeInstanceOf(Post::class)
            ->and($post->id)->toBeIn($postIds);
    }
});
