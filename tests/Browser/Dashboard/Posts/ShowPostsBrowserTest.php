<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use Tests\NewPost;
use Tests\NewUser;

it('displays all posts successfully', function () {
    new NewUser()->login($this)->user;
    $publishedPosts = new NewPost([
        'status' => PostStatus::Published->value,
    ], 2)->posts;
    $draftPosts = new NewPost([
        'status' => PostStatus::Published->value,
    ], 2)->posts;

    $page = visit(route('posts.index'));

    $page->assertTitle('All Posts - '.config('app.name'))
        ->assertSee('All Posts')
        ->assertSee($publishedPosts[0]->title)
        ->assertSee($publishedPosts[1]->title)
        ->assertSee($draftPosts[0]->title)
        ->assertSee($draftPosts[1]->title);
});

it('displays empty posts message when no posts available', function () {
    new NewUser()->login($this)->user;

    $page = visit(route('posts.index'));

    $page->assertTitle('All Posts - '.config('app.name'))
        ->assertSee('No posts yet!');
});
