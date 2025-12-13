<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use Inertia\Testing\AssertableInertia;
use Tests\NewPost;
use Tests\NewUser;

/**
 * List all published posts
 */

it('renders the posts page successfully', function () {
    $user = new NewUser()->login($this)->user;
    $posts = new NewPost([
        'user_id' => $user->id,
        'status' => PostStatus::Published->value
    ], 3)->posts;

    $response = $this->get(route('posts.index'));

    $response->assertOk()
        ->assertInertia(static fn (AssertableInertia $page) =>
            $page->component('dashboard/posts/Index')
                ->has('posts')
                ->has('posts.data')
                ->where('posts.data.0.id', $posts[0]->id)
                ->where('posts.data.1.id', $posts[1]->id)
                ->where('posts.data.2.id', $posts[2]->id)
        );
});

it('only fetches the published posts', function () {
    $user = new NewUser()->login($this)->user;
    $posts = new NewPost([
        'user_id' => $user->id,
        'status' => PostStatus::Draft->value,
    ], 2)->posts;
    $posts[0]->update([
        'status' => PostStatus::Published
    ]);

    $response = $this->get(route('posts.index'));

    $response->assertOk()
        ->assertInertia(static fn (AssertableInertia $page) =>
        $page->component('dashboard/posts/Index')
            ->has('posts')
            ->has('posts.data')
            ->count('posts.data', 1)
            ->where('posts.data.0.id', $posts[0]->id)
        );
});

it('redirects guests to login page when trying to access the dashboard posts page', function () {
    $response = $this->get(route('posts.index'));

    $response->assertRedirect(route('login'));
});
