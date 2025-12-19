<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use App\Models\Post;
use Tests\NewPost;
use Tests\NewUser;

it('creates a new post successfully', function () {
    $user = new NewUser()->login($this)->user;
    $postData = NewPost::validPostData();

    $page = visit(route('posts.create'));

    $page
        ->assertTitle('Create a New Post - '.config('app.name'))
        ->assertSee('Create a new post')
        ->fill('[name="title"]', $postData['title'])
        ->fill('[name="slug"]', $postData['slug'])
        ->fill('[name="body"]', $postData['body'])
        ->select('[name="status"]', $postData['status'])
        ->click('Create')
        ->wait(3)
        ->assertRoute('posts.index')
        ->assertSee($postData['title'])
        ->assertSee('Post has been created.');

    $posts = Post::all();
    $firstPost = $posts->first();
    $nowTimestamp = now()->timestamp;

    expect($posts->count())->toBe(1)
        ->and($firstPost)->toBeInstanceOf(Post::class)
        ->and($firstPost->user_id)->toBe($user->id)
        ->and($firstPost->title)->toBe($postData['title'])
        ->and($firstPost->slug)->toBe($postData['slug'])
        ->and($firstPost->body)->toBe($postData['body'])
        ->and($firstPost->status)->toBe(PostStatus::Published)
        ->and($firstPost->created_at->timestamp)->toBeLessThanOrEqual($nowTimestamp)
        ->and($firstPost->updated_at->timestamp)->toBeLessThanOrEqual($nowTimestamp);
});
