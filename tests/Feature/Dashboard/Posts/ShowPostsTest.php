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
        'status' => PostStatus::Published->value,
    ], 3)->posts;

    $response = $this->get(route('posts.index'));

    $response->assertOk()
        ->assertInertia(static fn (AssertableInertia $page) => $page->component('dashboard/posts/Index')
            ->has('posts')
            ->has('posts.data', 3)
            ->where('posts.data.0.id', $posts[2]->id)
            ->where('posts.data.1.id', $posts[1]->id)
            ->where('posts.data.2.id', $posts[0]->id)
        );
});

it('fetches all kinds of posts', function () {
    $user = new NewUser()->login($this)->user;
    $publishedPosts = new NewPost([
        'user_id' => $user->id,
        'status' => PostStatus::Published->value,
    ], 2)->posts;
    $draftPosts = new NewPost([
        'user_id' => $user->id,
        'status' => PostStatus::Draft->value,
    ], 2)->posts;

    $response = $this->get(route('posts.index'));

    $response->assertOk()
        ->assertInertia(static fn (AssertableInertia $page) => $page->component('dashboard/posts/Index')
            ->has('posts')
            ->has('posts.data')
            ->count('posts.data', 4)
            ->where('posts.data.0.id', $draftPosts[1]->id)
            ->where('posts.data.1.id', $draftPosts[0]->id)
            ->where('posts.data.2.id', $publishedPosts[1]->id)
            ->where('posts.data.3.id', $publishedPosts[0]->id)
        );
});

it('redirects guests to login page when trying to access the dashboard posts page', function () {
    $response = $this->get(route('posts.index'));

    $response->assertRedirect(route('login'));
});

it('renders the posts page component with sorted posts in descending order', function () {
    $user = new NewUser()->login($this)->user;
    $postOne = new NewPost([
        'status' => PostStatus::Published->value,
        'published_at' => now()->subDay(),
    ])->first();
    $postTwo = new NewPost([
        'status' => PostStatus::Published->value,
        'published_at' => now(),
    ])->first();
    $postThree = new NewPost([
        'status' => PostStatus::Draft->value,
    ])->first();

    $response = $this->get(route('posts.index'));

    $response
        ->assertOk()
        ->assertInertia(function (AssertableInertia $component) use ($postOne, $postTwo, $postThree) {
            $component->component('dashboard/posts/Index')
                ->has('posts.data', 3)
                ->where('posts.data.0.id', $postThree->id)
                ->where('posts.data.1.id', $postTwo->id)
                ->where('posts.data.2.id', $postOne->id);
        });
});
