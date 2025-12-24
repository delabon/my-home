<?php

declare(strict_types=1);

use App\Models\Post;
use Tests\NewPost;
use Tests\NewUser;

it('soft deletes a post successfully', function () {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    $page = visit(route('posts.edit', $post));

    $page->click('Delete Post')
        ->wait(3)
        ->assertRoute('posts.index')
        ->wait(1)
        ->assertDontSee($post->title)
        ->assertSee('No posts yet!')
        ->assertSee('Post has been deleted.');

    $post->refresh();

    expect(Post::count())->toBe(0);
});
