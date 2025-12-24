<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use Tests\NewPost;
use Tests\NewUser;

it('displays the published posts successfully', function () {
    new NewUser()->login($this)->user;
    $posts = new NewPost([
        'status' => PostStatus::Published->value,
    ], 2)->posts;

    $page = visit(route('posts.index'));

    $page->assertTitle('All Posts - '.config('app.name'))
        ->assertSee('All Posts')
        ->assertSee($posts[0]->title)
        ->assertSee($posts[1]->title);
});

it('displays empty posts message when no posts available', function () {
    new NewUser()->login($this)->user;

    $page = visit(route('posts.index'));

    $page->assertTitle('All Posts - '.config('app.name'))
        ->assertSee('No posts yet!');
});
