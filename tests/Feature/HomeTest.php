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
                ->where('posts.data.0.formatted_created_at', $posts[0]->formatted_created_at);
        });
});
