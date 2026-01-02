<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Support\Carbon;
use Tests\NewPost;
use Tests\NewUser;

it('renders the edit post page successfully', function () {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'user_id' => $user->id,
    ])->first();

    $page = visit(route('posts.edit', $post));

    $page
        ->assertTitle('Edit Post - '.config('app.name'))
        ->assertSee('Edit post: '.$post->title)
        ->assertValue('title', $post->title)
        ->assertValue('slug', $post->slug)
        ->assertValue('[name="body"]', $post->body)
        ->assertSelected('status', $post->status->value)
        ->assertSee('Save');
});

it('updates a post successfully', function () {
    $user = new NewUser()->login($this)->user;
    $postData = NewPost::validPostData();
    $postData['user_id'] = $user->id;
    $postData['status'] = PostStatus::Draft->value;
    $post = new NewPost($postData)->first();
    $oldCreatedAtTimestamp = $post->created_at->timestamp;
    $oldUpdatedAtTimestamp = $post->updated_at->timestamp;
    $updatedPostData = [
        'title' => 'The post title is updated',
        'slug' => 'post-slug-is-updated',
        'body' => 'The post body is updated',
        'status' => PostStatus::Published->value,
    ];

    $page = visit(route('posts.edit', $post));

    $page
        ->fill('[name="title"]', $updatedPostData['title'])
        ->fill('[name="slug"]', $updatedPostData['slug'])
        ->fill('.tiptap-body .tiptap', $updatedPostData['body'])
        ->select('[name="status"]', $updatedPostData['status'])
        ->click('Save')
        ->wait(3)
        ->assertRoute('posts.index')
        ->wait(1)
        ->assertSee($updatedPostData['title'])
        ->assertSee('Post has been updated.');

    $post->refresh();

    expect(Post::count())->toBeOne()
        ->and($post->user_id)->toBe($user->id)
        ->and($post->title)->toBe($updatedPostData['title'])
        ->and($post->slug)->toBe($updatedPostData['slug'])
        ->and($post->body)->toBe('<p>'.$updatedPostData['body'].'</p>')
        ->and($post->status)->toBe(PostStatus::Published)
        ->and($post->published_at)->toBeInstanceOf(Carbon::class)
        ->and($post->published_at->timestamp)->toBeGreaterThanOrEqual($oldCreatedAtTimestamp)
        ->and($post->created_at->timestamp)->toBe($oldCreatedAtTimestamp)
        ->and($post->updated_at->timestamp)->toBeGreaterThanOrEqual($oldUpdatedAtTimestamp);
});
