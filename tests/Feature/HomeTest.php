<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use Inertia\Testing\AssertableInertia;
use Tests\NewPost;

it('renders the home page component with posts and pagination', function () {
    $posts = new NewPost([
        'status' => PostStatus::Published->value,
    ], 21)->posts;
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertInertia(function (AssertableInertia $component) use ($posts) {
            $component->component('Home')
                ->has('posts.links')
                ->has('posts.links.prev')
                ->has('posts.links.next')
                ->has('posts.data', 10)
                ->where('posts.data.0.id', $posts[0]->id)
                ->where('posts.data.0.title', $posts[0]->title)
                ->where('posts.data.0.slug', $posts[0]->slug)
                ->where('posts.data.0.formatted_published_at', $posts[0]->formatted_published_at);
        });
});

it('renders the home page component with only published posts', function () {
    $publishedPosts = new NewPost([
        'status' => PostStatus::Published->value,
    ], 2)->posts;
    new NewPost([
        'status' => PostStatus::Draft->value,
    ], 3)->posts;
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertInertia(function (AssertableInertia $component) use ($publishedPosts) {
            $component->component('Home')
                ->has('posts.data', 2)
                ->where('posts.data.0.id', $publishedPosts[0]->id)
                ->where('posts.data.0.title', $publishedPosts[0]->title)
                ->where('posts.data.1.id', $publishedPosts[1]->id)
                ->where('posts.data.1.title', $publishedPosts[1]->title);
        });
});

it('renders the home page component with sorted published posts in descending order', function () {
    $postOne = new NewPost([
        'status' => PostStatus::Published->value,
        'published_at' => now()->subDay(),
    ])->first();
    $postTwo = new NewPost([
        'status' => PostStatus::Published->value,
        'published_at' => now(),
    ])->first();
    new NewPost([
        'status' => PostStatus::Draft->value,
    ])->first();
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertInertia(function (AssertableInertia $component) use ($postOne, $postTwo) {
            $component->component('Home')
                ->has('posts.data', 2)
                ->where('posts.data.0.id', $postTwo->id)
                ->where('posts.data.1.id', $postOne->id);
        });
});
