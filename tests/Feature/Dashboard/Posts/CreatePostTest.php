<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use App\Models\Post;
use Inertia\Testing\AssertableInertia;
use Tests\NewPost;
use Tests\NewUser;

it('renders the create post page successfully', function () {
    new NewUser()->login($this);

    $response = $this->get(route('posts.create'));

    $response->assertOk()
        ->assertInertia(function (AssertableInertia $component) {
            $component->component('dashboard/posts/Create')
                ->has('statuses');
        });
});

it('redirects guests to login page when trying to access the create post page', function () {
    $response = $this->get(route('posts.create'));

    $response->assertRedirect(route('login'));
});

it('redirects guests to login page when trying to access the store post endpoint', function () {
    $response = $this->post(
        route('posts.store'),
        NewPost::validPostData()
    );

    $response->assertRedirect(route('login'));
});

it('returns too many requests response when trying to abuse the store post endpoint', function () {
    new NewUser()->login($this);

    for ($i = 0; $i < 10; ++$i) {
        $response = $this->post(route('posts.store'), [
            'title' => 'Post number '.$i,
            'body' => 'Super long Body number '.$i,
            'status' => PostStatus::Published->value,
        ]);
        $response->assertRedirect(route('posts.index'));
    }

    $response = $this->post(route('posts.store'), [
        'title' => 'Post number '. 11,
        'body' => 'Body number '. 11,
        'status' => PostStatus::Published->value,
    ]);
    $response->assertTooManyRequests();
});

it('create a post successfully', function () {
    $user = new NewUser()->login($this)->user;
    $postData = NewPost::validPostData();

    $response = $this->post(
        route('posts.store'),
        $postData
    );

    $response->assertRedirect(route('posts.index'))
        ->assertSessionHas('success', 'Post has been created.');

    $posts = Post::all();
    $firstPost = $posts->first();
    $nowTimestamp = now()->timestamp;

    expect($posts->count())->toBe(1)
        ->and($firstPost)->toBeInstanceOf(Post::class)
        ->and($firstPost->user_id)->toBe($user->id)
        ->and($firstPost->title)->toBe($postData['title'])
        ->and($firstPost->body)->toBe($postData['body'])
        ->and($firstPost->status)->toBe(PostStatus::Published)
        ->and($firstPost->created_at->timestamp)->toBeLessThanOrEqual($nowTimestamp)
        ->and($firstPost->updated_at->timestamp)->toBeLessThanOrEqual($nowTimestamp);
});

dataset('invalid_title_data', [
    [
        '',
        'The title field is required.',
    ],
    [
        'U',
        'The title field must be at least 2 characters.',
    ],
    [
        str_repeat('a', 256),
        'The title field must not be greater than 255 characters.',
    ],
]);

it('fails with invalid titles', function (string $invalidTitle, string $expectedMessage) {
    new NewUser()->login($this)->user;
    $postData = NewPost::validPostData();
    $postData['title'] = $invalidTitle;

    $response = $this->post(
        route('posts.store'),
        $postData
    );

    $response->assertRedirectBack()
        ->assertSessionHasErrors([
            'title' => $expectedMessage,
        ]);
})->with('invalid_title_data');

dataset('invalid_body_data', [
    [
        '',
        'The body field is required.',
    ],
    [
        'ABCD',
        'The body field must be at least 20 characters.',
    ],
    [
        str_repeat('a', 5001),
        'The body field must not be greater than 5000 characters.',
    ],
]);

it('fails with invalid body', function (string $invalidBody, string $expectedMessage) {
    new NewUser()->login($this)->user;
    $postData = NewPost::validPostData();
    $postData['body'] = $invalidBody;

    $response = $this->post(
        route('posts.store'),
        $postData
    );

    $response->assertRedirectBack()
        ->assertSessionHasErrors([
            'body' => $expectedMessage,
        ]);
})->with('invalid_body_data');

dataset('invalid_status_data', [
    [
        '',
        'The status field is required.',
    ],
    [
        'ABCD',
        'The selected status is invalid.',
    ],
]);

it('fails with invalid status data', function (string $invalidStatus, string $expectedMessage) {
    new NewUser()->login($this)->user;
    $postData = NewPost::validPostData();
    $postData['status'] = $invalidStatus;

    $response = $this->post(
        route('posts.store'),
        $postData
    );

    $response->assertRedirectBack()
        ->assertSessionHasErrors([
            'status' => $expectedMessage,
        ]);
})->with('invalid_status_data');
