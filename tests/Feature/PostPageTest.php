<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use Inertia\Testing\AssertableInertia;
use Tests\NewPost;

it('renders the post page successfully', function () {
    $post = new NewPost([
        'status' => PostStatus::Published->value,
    ])->first();

    $response = $this->get(route('posts.view', $post));

    $response
        ->assertOk()
        ->assertInertia(function (AssertableInertia $component) use ($post) {
            $component->component('Post')
                ->has('post', 1)
                ->where('post.data.id', $post->id)
                ->where('post.data.title', $post->title)
                ->where('post.data.slug', $post->slug)
                ->where('post.data.body', $post->body)
                ->where('post.data.formatted_created_at', $post->formatted_created_at);
        });
});
