<?php

declare(strict_types=1);

use Tests\NewPost;
use Tests\NewUser;

it('renders the edit post page successfully', function () {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    $page = visit(route('posts.edit', $post));

    $page
        ->assertTitle('Edit Post - ' . config('app.name'))
        ->assertSee('Edit post: ' . $post->title)
        ->assertValue('title', $post->title)
        ->assertValue('[name="body"]', $post->body)
        ->assertSelected('status', $post->status->value)
        ->assertSee('Save');
});
