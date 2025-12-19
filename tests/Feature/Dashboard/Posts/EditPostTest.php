<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia;
use Tests\NewPost;
use Tests\NewUser;

/**
 * Edit post page
 */
it('renders the edit post page successfully', function () {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    $response = $this->get(route('posts.edit', $post));

    $response->assertOk()
        ->assertInertia(static fn (AssertableInertia $page) => $page->component('dashboard/posts/Edit')
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

it('returns forbidden response when trying to edit a post with non-owner user', function () {
    new NewUser()->login($this)->user;
    $post = new NewPost()->first();

    $response = $this->get(route('posts.edit', $post));

    $response->assertForbidden();
});

/**
 * update post endpoint
 */
it('updates a post successfully', function () {
    $user = new NewUser()->login($this)->user;
    $postData = NewPost::validPostData();
    $postData['user_id'] = $user->id;
    $post = new NewPost($postData)->first();
    $oldUpdatedAtTimestamp = $post->updated_at->timestamp;

    $response = $this->patch(route('posts.update', $post), [
        'title' => 'This title has been editing',
        'slug' => 'updated-slug',
        'body' => 'This body has been editing',
        'status' => PostStatus::Draft->value,
    ]);

    $response->assertRedirectToRoute('posts.index')
        ->assertSessionHas('success', 'Post has been updated.');

    $post->refresh();

    expect(Post::count())->toBeOne()
        ->and($post->user_id)->toBe($user->id)
        ->and($post->title)->toBe('This title has been editing')
        ->and($post->slug)->toBe('updated-slug')
        ->and($post->body)->toBe('This body has been editing')
        ->and($post->status)->toBe(PostStatus::Draft)
        ->and($post->updated_at->timestamp)->toBeGreaterThanOrEqual($oldUpdatedAtTimestamp);
});

it('updates a post with a slug generated from the title successfully', function () {
    $user = new NewUser()->login($this)->user;
    $postData = NewPost::validPostData();
    $postData['user_id'] = $user->id;
    $post = new NewPost($postData)->first();
    $updatedTitle = 'This title has been editing';

    $response = $this->patch(route('posts.update', $post), [
        'title' => $updatedTitle,
        'body' => 'This body has been editing',
        'status' => PostStatus::Draft->value,
    ]);

    $response->assertRedirectToRoute('posts.index')
        ->assertSessionHas('success', 'Post has been updated.');

    $post->refresh();

    expect(Post::count())->toBeOne()
        ->and($post->slug)->toBe(Str::slug($updatedTitle));
});

it('redirects guests to login page when trying to access the update post endpoint', function () {
    $response = $this->patch(route('posts.update', 1));

    $response->assertRedirect(route('login'));
});

it('returns a too many requests response when trying to update a post too many times', function () {
    $user = new NewUser()->login($this)->user;
    $postData = NewPost::validPostData();
    $postData['user_id'] = $user->id;
    $post = new NewPost($postData)->first();

    for ($i = 0; $i < 10; ++$i) {
        $response = $this->patch(route('posts.update', $post), [
            'title' => 'This title has been editing',
            'slug' => 'updated-slug',
            'body' => 'This body has been editing',
            'status' => PostStatus::Draft->value,
        ]);

        $response->assertRedirectToRoute('posts.index')
            ->assertSessionHas('success', 'Post has been updated.');
    }

    $response = $this->patch(route('posts.update', $post), [
        'title' => 'This title has been editing',
        'slug' => 'updated-slug',
        'body' => 'This body has been editing',
        'status' => PostStatus::Draft->value,
    ]);

    $response->assertTooManyRequests();
});

it('returns forbidden response when trying to update a post with a non-owner user', function () {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'status' => NewPost::VALID_STATUS,
    ])->first();

    $response = $this->patch(route('posts.update', $post), [
        'title' => 'This title has been editing',
        'body' => 'This body has been editing',
        'status' => PostStatus::Draft->value,
    ]);

    $response->assertForbidden();

    $post->refresh();

    expect($post->user_id)->not()->toBe($user->id)
        ->and($post->title)->not()->toBe('This title has been editing')
        ->and($post->body)->not()->toBe('This body has been editing')
        ->and($post->status)->not()->toBe(PostStatus::Draft);
});

it('fails with invalid titles', function (string $invalidTitle, string $expectedMessage) {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    $postData = NewPost::validPostData();
    $postData['title'] = $invalidTitle;

    $response = $this->patch(
        route('posts.update', $post),
        $postData
    );

    $response->assertRedirectBack()
        ->assertSessionHasErrors([
            'title' => $expectedMessage,
        ]);
})->with('invalid_title_data');

it('fails with invalid slugs', function (string $invalidSlug, string $expectedMessage) {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    $postData = NewPost::validPostData();
    $postData['slug'] = $invalidSlug;

    $response = $this->patch(
        route('posts.update', $post),
        $postData
    );

    $response->assertRedirectBack()
        ->assertSessionHasErrors([
            'slug' => $expectedMessage,
        ]);
})->with('invalid_slug_data');

it('fails with non unique slug', function () {
    $user = new NewUser()->login($this)->user;
    $slug = 'my-unique-slug';
    new NewPost([
        'user_id' => $user->id,
        'slug' => $slug,
    ])->first();

    $post2 = new NewPost([
        'user_id' => $user->id,
    ])->first();
    $postData = $post2->toArray();
    $postData['slug'] = $slug;

    $response = $this->patch(
        route('posts.update', $post2),
        $postData
    );

    $response->assertRedirectBack()
        ->assertSessionHasErrors([
            'slug' => 'The slug has already been taken.',
        ]);
});

it('fails with invalid body', function (string $invalidBody, string $expectedMessage) {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    $postData = NewPost::validPostData();
    $postData['body'] = $invalidBody;

    $response = $this->patch(
        route('posts.update', $post),
        $postData
    );

    $response->assertRedirectBack()
        ->assertSessionHasErrors([
            'body' => $expectedMessage,
        ]);
})->with('invalid_body_data');

it('fails with invalid status data', function (string $invalidStatus, string $expectedMessage) {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    $postData = NewPost::validPostData();
    $postData['status'] = $invalidStatus;

    $response = $this->patch(
        route('posts.update', $post),
        $postData
    );

    $response->assertRedirectBack()
        ->assertSessionHasErrors([
            'status' => $expectedMessage,
        ]);
})->with('invalid_status_data');
