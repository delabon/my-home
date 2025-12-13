<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use Inertia\Testing\AssertableInertia;
use Tests\NewPost;
use Tests\NewUser;

it('renders the edit post page successfully', function () {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    $response = $this->get(route('posts.edit', $post));

    $response->assertOk()
        ->assertInertia(static fn (AssertableInertia $page) =>
            $page->component('dashboard/posts/Edit')
                ->has('statuses')
                ->has('post')
                ->where('post.id', $post->id)
                ->where('post.title', $post->title)
                ->where('post.body', $post->body)
                ->where('post.status', $post->status->value)
        );
});

it('redirects guests to login page when trying to access the edit post page', function () {
    $response = $this->get(route('posts.edit', 1));

    $response->assertRedirect(route('login'));
});

