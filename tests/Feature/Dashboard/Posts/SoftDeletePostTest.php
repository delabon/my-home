<?php

declare(strict_types=1);

use App\Enums\PostStatus;
use Tests\NewPost;
use Tests\NewUser;

use function Pest\Laravel\assertDatabaseCount;

it('soft deletes a post successfully', function () {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'user_id' => $user->id,
        'status' => PostStatus::Published->value,
    ])->first();

    $response = $this->delete(route('posts.destroy', $post));

    $response->assertRedirectToRoute('posts.index')
        ->assertSessionHas('success', 'Post has been deleted.');

    $post->refresh();

    assertDatabaseCount('posts', 1);
    expect($post->trashed())->toBeTrue()
        ->and($post->deleted_at)->not()->toBeNull();
});

it('soft deletes only one post and leave the other', function () {
    $user = new NewUser()->login($this)->user;
    $posts = new NewPost([
        'user_id' => $user->id,
        'status' => PostStatus::Published->value,
    ], 2)->posts;

    $response = $this->delete(route('posts.destroy', $posts[0]));

    $response->assertRedirectToRoute('posts.index')
        ->assertSessionHas('success', 'Post has been deleted.');

    $posts[0]->refresh();
    $posts[1]->refresh();

    assertDatabaseCount('posts', 2);
    expect($posts[0]->trashed())->toBeTrue()
        ->and($posts[0]->deleted_at)->not()->toBeNull()
        ->and($posts[1]->trashed())->toBeFalse()
        ->and($posts[1]->deleted_at)->toBeNull();
});

it('returns a not found response when trying to delete a non-existent post', function () {
    new NewUser()->login($this)->user;

    $response = $this->delete(route('posts.destroy', 1));

    $response->assertNotFound();
});

it('returns a too many requests response when trying to delete posts too many times', function () {
    $user = new NewUser()->login($this)->user;
    $posts = new NewPost([
        'user_id' => $user->id,
        'status' => PostStatus::Published->value,
    ], 11)->posts;

    for ($i = 0; $i < 10; ++$i) {
        $response = $this->delete(route('posts.destroy', $posts[$i]));

        $response->assertRedirectToRoute('posts.index')
            ->assertSessionHas('success', 'Post has been deleted.');

        $posts[$i]->refresh();

        expect($posts[$i]->trashed())->toBeTrue();
    }

    $response = $this->delete(route('posts.destroy', $posts[10]));

    $response->assertTooManyRequests();
    assertDatabaseCount('posts', 11);

    $posts[10]->refresh();
    expect($posts[10]->trashed())->toBeFalse();

});

it('returns a not found response when trying to delete an already deleted post', function () {
    $user = new NewUser()->login($this)->user;
    $post = new NewPost([
        'user_id' => $user->id,
        'status' => PostStatus::Published->value,
    ])->first();
    $post->delete();

    $response = $this->delete(route('posts.destroy', $post));

    $response->assertNotFound();

    $post->refresh();

    expect($post->trashed())->toBeTrue();
});

it('redirects to the login page when trying to delete a post when not logged in', function () {
    $post = new NewPost([
        'status' => PostStatus::Published->value,
    ])->first();

    $response = $this->delete(route('posts.destroy', $post));

    $response->assertRedirectToRoute('login');

    $post->refresh();
    expect($post->trashed())->toBeFalse();
});

test('non-author cannot soft delete posts of other authors', function () {
    new NewUser()->login($this)->user;
    $post = new NewPost([
        'status' => PostStatus::Published->value,
    ])->first();

    $response = $this->delete(route('posts.destroy', $post));

    $response->assertForbidden();

    $post->refresh();
    expect($post->trashed())->toBeFalse();
});
