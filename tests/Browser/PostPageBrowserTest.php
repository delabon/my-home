<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use Tests\NewPost;

it('renders the post page correctly', function () {
    $post = new NewPost([
        'status' => PostStatus::Published->value,
    ])->first();
    $page = visit(route('posts.view', $post));

    $pageTitle = sprintf(
        '%s - Blog - %s',
        $post->title,
        config('app.name')
    );
    $page->assertTitle($pageTitle)
        ->assertSee($post->title)
        ->assertSee($post->formatted_published_at)
        ->assertSee($post->body);
});
